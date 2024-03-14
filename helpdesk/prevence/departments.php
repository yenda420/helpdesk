<?php

require 'config.php';
require 'functions.php';

session_start();

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    header('location:index.php');
}
if (isset($_POST['delete_dep'])) {
    $dep_id = $_POST['dep_id'];
    if (deleteDepartment($conn, $dep_id)) {
        $_SESSION['message'] = "Department deleted successfully";
    } else {
        $_SESSION['message'] = "Error deleting department";
    }
    $unassigned_admins = returnAllBackendUsers($conn);
    foreach ($unassigned_admins as $unassigned_admin) {
        if (!isAdminInDepartment($conn, $unassigned_admin['adminId'])) {
            $stmt = $conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, 0)");
            $stmt->bind_param("i", $unassigned_admin['adminId']);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
if (isset($_POST['change_dep'])) {
    $dep_id = $_POST['dep_id'];
    $ticket_types = $_POST['ticket_types'];
    $admins = $_POST['admins'];
    $dep_name = $_POST['dep_name'];
    $_SESSION['message'] = changeDepartments($conn, $dep_id, $ticket_types, $admins, $dep_name);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<?php
if (isset($_SESSION['message'])) {
    $message[] = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="dashboard">
        <section class="users">

            <h1 class="title">Departments</h1>

            <div class="box-container">
                <?php
                $stmt = $conn->prepare("SELECT * FROM departments");
                $stmt->execute();
                $select_deps = $stmt->get_result();
                while ($fetch_deps = $select_deps->fetch_assoc()) {
                    ?>
                    <div class="box">
                        <div class="breaking">
                            <p> ID: <span>
                                    <?php echo $fetch_deps['departmentId']; ?>
                                </span> </p>
                        </div>
                        <div class="breaking">
                            <p> Name: <span>
                                    <?php echo $fetch_deps['departmentName']; ?>
                                </span> </p>
                        </div>
                        <form method="POST">
                            <div class="breaking">
                                <p> Ticket types:
                                    <?php
                                    $ticket_types = array();
                                    $ticket_types = returnTicketTypesForDepartmentName($conn, $fetch_deps['departmentName']);
                                    $last_index = count($ticket_types) - 1;
                                    echo count($ticket_types);
                                    echo "<textarea name='ticket_types' rows='" . count($ticket_types) . "' cols='21'>";
                                    foreach ($ticket_types as $ticket_type) {
                                        $ticketTypeName = $ticket_type['ticketTypeName'] . ',';
                                        echo str_replace(',', "\n", $ticketTypeName);
                                    }
                                    echo "</textarea>";
                                    ?>
                                </p>
                            </div>
                            <div class="breaking">
                                <p> Admins:
                                    <?php
                                    $admins = array();
                                    $admins = returnAllBackendsForDepartmentId($conn, $fetch_deps['departmentId']);
                                    echo count($admins);
                                    echo "<textarea name='admins' rows='" . count($admins) . "'cols='21'>";
                                    foreach ($admins as $admin) {
                                        $admin = $admin['adminEmail'] . ',';
                                        echo str_replace(',', "\n", $admin);
                                    }
                                    echo "</textarea>";
                                    ?>
                                </p>
                            </div>

                            <input type="hidden" name="dep_id" value="<?php echo $fetch_deps['departmentId']; ?>">
                            <input type="hidden" name="dep_name" value="<?php echo $fetch_deps['departmentName']; ?>">
                            <?php
                            if ($fetch_deps['departmentName'] != 'Super-admin' && $fetch_deps['departmentName'] != 'Unassigned') {
                                ?>
                                <button type="submit" name="delete_dep" class="delete-btn"
                                    onclick="return confirmDeletingDepartment()">Delete</button>
                                <?php
                            }
                            ?>
                            <button type="submit" name="change_dep" class="btn"
                                onclick="return confirmAlteringDepartments()">Change</button>
                        </form>
                    </div>
                    <?php
                }
                ;
                if (mysqli_num_rows($select_deps) == 0) {
                    echo '<p class="empty">No departments</p>';
                }
                ?>
            </div>
        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>