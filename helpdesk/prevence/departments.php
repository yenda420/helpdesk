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

}
if (isset($_POST['change_dep'])) {
    $dep_id = $_POST['dep_id'];
    $ticket_types = $_POST['ticket_types'];
    $ticket_types = explode("\n", $ticket_types);
    $ticket_types = array_filter($ticket_types);
    $ticket_types = array_map('trim', $ticket_types);
    $admins = $_POST['admins'];
    $admins = explode("\n", $admins);
    $admins = array_filter($admins);
    $admins = array_map('trim', $admins);
    $admin_ids = [];
    $delete_department = "DELETE FROM department_lists WHERE departmentId = $dep_id";
    if (mysqli_query($conn, $delete_department) && !in_array('', $admins)) {
        foreach ($admins as $admin) {
            $admin_ids[] = returnAdminId($conn, $admin);
        }

        foreach ($admin_ids as $admin_id) {
            if ($dep_id != 0 && $dep_id != 3) {
                if (isAdminUnassigned($conn, $admin_id['adminId']))
                    mysqli_query($conn, "UPDATE department_lists SET departmentId = $dep_id WHERE adminId = {$admin_id['adminId']}");
                else
                    mysqli_query($conn, "INSERT INTO department_lists (adminId, departmentId) VALUES ({$admin_id['adminId']}, $dep_id)");
            } else {
                mysqli_query($conn, "DELETE FROM department_lists WHERE adminId = {$admin_id['adminId']}");
                mysqli_query($conn, "INSERT INTO department_lists (adminId, departmentId) VALUES ({$admin_id['adminId']}, $dep_id)");
            }
        }
    }
    $ticket_types_all = returnTicketTypesForDepartmentName($conn, $_POST['dep_name']);
    foreach ($ticket_types_all as $ticket_type) {
        if (!in_array($ticket_type['ticketTypeName'], $ticket_types)) {
            $update_tickets = "UPDATE ticket_types SET departmentId = 0 WHERE ticketTypeName = '{$ticket_type['ticketTypeName']}'";
            mysqli_query($conn, $update_tickets);
        }
    }
    if ($dep_id != 3) {
        foreach ($ticket_types as $ticket_type) {
            $update_tickets = "UPDATE ticket_types SET departmentId = $dep_id WHERE ticketTypeName = '$ticket_type'";
            mysqli_query($conn, $update_tickets);
        }
    }
    $message[] = "Department has been changed successfully";
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
                $select_deps = mysqli_query($conn, "SELECT * FROM `departments`") or die('query failed');
                while ($fetch_deps = mysqli_fetch_assoc($select_deps)) {
                    ?>
                    <div class="box">
                        <div class="breaking">
                            <p> ID : <span>
                                    <?php echo $fetch_deps['departmentId']; ?>
                                </span> </p>
                        </div>
                        <div class="breaking">
                            <p> Name : <span>
                                    <?php echo $fetch_deps['departmentName']; ?>
                                </span> </p>
                        </div>
                        <form method="POST">
                            <div class="breaking">
                                <p> Ticket types :
                                    <?php
                                    $ticket_types = array();
                                    $ticket_types = returnTicketTypesForDepartmentName($conn, $fetch_deps['departmentName']);
                                    $last_index = count($ticket_types) - 1;
                                    echo count($ticket_types);
                                    echo "<textarea name='ticket_types' rows='" . count($ticket_types) . "' cols='22'>";
                                    foreach ($ticket_types as $ticket_type) {
                                        $ticketTypeName = $ticket_type['ticketTypeName'] . ',';
                                        echo str_replace(',', "\n", $ticketTypeName);
                                    }
                                    echo "</textarea>";
                                    ?>
                                </p>
                            </div>
                            <div class="breaking">
                                <p> Admins :
                                    <?php
                                    $admins = array();
                                    $admins = returnAllBackendsForDepartmentId($conn, $fetch_deps['departmentId']);
                                    echo count($admins);
                                    echo "<textarea name='admins' rows='" . count($admins) . "'cols='22'>";
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
                            <button type="submit" name="delete_dep" class="delete-btn"
                                onclick="return confirmDeletingDepartment()">Delete</button>
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