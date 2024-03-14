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
    $stmt = $conn->prepare("DELETE FROM `tickets` WHERE ticketTypeId = ?");
    $stmt->bind_param("i", $type_id);
    $delete_tickets = $stmt->execute();
    if ($delete_tickets) {
        $stmt = $conn->prepare("DELETE FROM `ticket_types` WHERE ticketTypeId = ?");
        $stmt->bind_param("i", $type_id);
        $delete_type = $stmt->execute();
        if ($delete_type) {
            $_SESSION['message'] = "Ticket type deleted successfully";
        } else {
            $_SESSION['message'] = "Error deleting ticket type";
        }
    } else {
        $_SESSION['message'] = "Error deleting ticket type";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
if (isset($_POST['change_type'])) {
    $type_id = $_POST['type_id'];
    $type_name = $_POST['type_name'];
    $department_responsible = $_POST['department_responsible'];
    $_SESSION['message'] = changeTicketTypeDepartment($conn, $type_id, $type_name, $department_responsible);
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
                $stmt = $conn->prepare("SELECT * FROM ticket_types");
                $stmt->execute();
                $select_types = $stmt->get_result();
                while ($fetch_types = $select_types->fetch_assoc()) {
                    ?>
                    <form method="POST">
                        <div class="box">
                            <div class="breaking">
                                <p> ID: <span>
                                        <?php echo $fetch_types['ticketTypeId']; ?>
                                    </span> </p>
                            </div>
                            <div class="breaking">
                                <p> Type name: <span>
                                        <input type="text" name="type_name"
                                            value="<?php echo $fetch_types['ticketTypeName']; ?>">
                                    </span> </p>
                            </div>
                            <div class="breaking">
                                <p>Department: <span>
                                        <input type="text" name="department_responsible"
                                            value="<?php echo returnDepartmentName($conn, $fetch_types['departmentId']); ?>">
                                    </span> </p>
                            </div>

                            <input type="hidden" name="type_id" value="<?php echo $fetch_types['ticketTypeId']; ?>">
                            <button type="submit" name="delete_type" class="delete-btn"
                                onclick="return confirmDeletingTicketType()">Delete</button>
                            <button type="submit" name="change_type" class="btn"
                                onclick="return confirmChangingTicketType()">Change</button>
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