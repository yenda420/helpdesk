<?php

require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/TicketTypeManager.php';
require 'classes/UserManager.php';
require 'classes/Database.php';
require 'classes/Utility.php';

$sessionManager = new SessionManager();
$messageManager = new MessageManager();

$database = new Database();
$db = $database->getConnection();
$ticketTypeManager = new TicketTypeManager($db);
$userManager = new UserManager($db);

$sessionManager->startSession();

$admin_id = $sessionManager->getAdminId();
if (!$admin_id) {
    header('location:index.php');
    exit;
}

if (isset($_POST['delete_type'])) {
    $typeId = $_POST['type_id'];
    if ($ticketTypeManager->deleteTicketType($typeId)) {
        $sessionManager->setMessage("Ticket type deleted successfully");
    } else {
        $sessionManager->setMessage("Error deleting ticket type");
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['change_type'])) {
    $typeId = $_POST['type_id'];
    $typeName = $_POST['type_name'];
    $departmentResponsible = $_POST['department_responsible'];
    
    $sessionManager->setMessage($ticketTypeManager->changeTicketType($typeId, $typeName, $departmentResponsible));
   
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Types</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="dashboard">
        <section class="users">

            <h1 class="title">Ticket Types</h1>
            <div class="box-container">
                <?php
                $ticketTypes = $ticketTypeManager->getTicketTypes();
                while ($type = $ticketTypes->fetch_assoc()) {
                ?>
                    <form method="POST">
                        <div class="box">
                            <div class="breaking">
                                <p> ID: <span><?= $type['ticketTypeId'] ?></span> </p>
                            </div>
                            <div class="breaking">
                                <p> Type name: <span>
                                        <input type="text" name="type_name" value="<?= $type['ticketTypeName'] ?>">
                                    </span> </p>
                            </div>
                            <div class="breaking">
                                <p>Department: <span>
                                        <input type="text" name="department_responsible" value="<?= $userManager->returnDepartmentName($type['departmentId']) ?>">
                                    </span> </p>
                            </div>

                            <input type="hidden" name="type_id" value="<?= $type['ticketTypeId'] ?>">
                            <button type="submit" name="delete_type" class="delete-btn" onclick="return confirmDeletingTicketType()">Delete</button>
                            <button type="submit" name="change_type" class="btn" onclick="return confirmChangingTicketType()">Change</button>
                        </div>
                    </form>
                <?php
                }
                if (mysqli_num_rows($ticketTypes) == 0) {
                    echo '<p class="empty">No requests</p>';
                }
                ?>
            </div>
        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>
