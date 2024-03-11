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
        $message[] = "Department deleted successfully";
    } else {
        $message[] = "Error deleting department";
    }
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
                            <p> ID: <span>
                                    <?php echo $fetch_deps['departmentId']; ?>
                                </span> </p>
                        </div>
                        <div class="breaking">
                            <p> Name: <span>
                                    <?php echo $fetch_deps['departmentName']; ?>
                                </span> </p>
                        </div>
                        <div class="breaking">
                            <p> Ticket types:
                                <?php
                                $ticket_types = array();
                                $ticket_types = returnTicketTypesForDepartmentName($conn, $fetch_deps['departmentName']);
                                $last_index = count($ticket_types) - 1;
                                foreach ($ticket_types as $index => $ticket_type) {
                                    echo '<span>' . $ticket_type['ticketTypeName'] . '</span>';
                                    if ($index != $last_index) {
                                        echo ' | ';
                                    }
                                }
                                ?>
                            </p>
                        </div>
                        <div class="breaking">
                            <p> Admins:
                                <?php
                                $admins = array();
                                $admins = returnAllBackendsForDepartmentId($conn, $fetch_deps['departmentId']);
                                foreach ($admins as $admin) {
                                    echo '<span>' . $admin['adminEmail'] . '</span> ';
                                }
                                ?>
                            </p>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="dep_id" value="<?php echo $fetch_deps['departmentId']; ?>"> <br>
                            <button type="submit" name="delete_dep" class="delete-btn"
                                onclick="return confirmDeletingDepartment()">Delete</button>
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