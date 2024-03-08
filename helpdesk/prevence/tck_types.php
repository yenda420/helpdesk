<?php

require 'config.php';
require 'functions.php';

session_start();

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    header('location:index.php');
}
if (isset($_POST['delete_type'])) {
    $type_id = $_POST['type_id'];
    // Perform the deletion query
    $delete_tickets = mysqli_query($conn, "DELETE FROM `tickets` WHERE ticketTypeId = $type_id");
    if ($delete_tickets) {
        $delete_type = mysqli_query($conn, "DELETE FROM `ticket_types` WHERE ticketTypeId = $type_id");
        if ($delete_type) {
            $message[] = "Ticket type deleted successfully";
        } else {
            $message[] = "Error deleting ticket type";
        }
    } else {
        $message[] = "Error deleting ticket type";
    }
}
if (isset($_POST['change_type'])) {
    $type_id = $_POST['type_id'];
    $type_name = $_POST['type_name'];
    $department_responsible = $_POST['department_responsible'];
    $departmentId = returnDepartmentId($conn, $department_responsible)['departmentId'];
    $update_type = mysqli_query($conn, "UPDATE `ticket_types` SET ticketTypeName = '$type_name', departmentId = $departmentId WHERE ticketTypeId = $type_id");
    if ($update_type) {
        $message[] = "Ticket type updated successfully";
    } else {
        $message[] = "Error updating ticket type";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket_types</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="dashboard">
        <section class="users">

            <h1 class="title">Ticket types</h1>

            <div class="box-container">
                <?php
                $select_types = mysqli_query($conn, "SELECT * FROM `ticket_types`") or die('query failed');
                while ($fetch_types = mysqli_fetch_assoc($select_types)) {
                    ?>
                    <form method="POST">
                        <div class="box">
                            <div class="breaking">
                                <p> ID : <span>
                                        <?php echo $fetch_types['ticketTypeId']; ?>
                                    </span> </p>
                            </div>
                            <div class="breaking">
                                <p> Type name : <span>
                                        <input type="text" name="type_name" value="<?php echo $fetch_types['ticketTypeName']; ?>">
                                    </span> </p>
                            </div>
                            <div class="breaking">
                                <p>Department responsible : <span>
                                    <input type="text" name="department_responsible" value="<?php echo returnDepartmentName($conn,$fetch_types['departmentId']); ?>">
                                    </span> </p>
                            </div>

                            <input type="hidden" name="type_id" value="<?php echo $fetch_types['ticketTypeId']; ?>">
                            <button type="submit" name="delete_type" class="delete-btn"
                                onclick="return confirmDeletingTicketType()">Delete</button>
                                <button type="submit" name="change_type" class="btn"
                                onclick="return confirmDeletingTicketType()">Change</button>
                        </div>
                    </form>
                    <?php
                }
                ;
                if (mysqli_num_rows($select_types) == 0) {
                    echo '<p class="empty">No requests</p>';
                }
                ?>
                </form>
            </div>
        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>