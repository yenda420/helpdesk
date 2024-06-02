<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/DepartmentManager.php';
require 'classes/UserManager.php';
require 'classes/Utility.php';
require 'classes/TicketTypeManager.php';

$sessionManager = new SessionManager();
$messageManager = new MessageManager();

$sessionManager->startSession();

$admin_id = $sessionManager->getAdminId();
if (!$admin_id) {
    header('location:index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$departmentManager = new DepartmentManager($db);

if (isset($_POST['delete_dep'])) {
    $dep_id = $_POST['dep_id'];
    if ($departmentManager->deleteDepartment($dep_id)) {
        $sessionManager->setMessage("Department deleted successfully");
    } else {
        $sessionManager->setMessage("Error deleting department");
    }

    $unassigned_admins = $departmentManager->returnAllBackendUsers();
    foreach ($unassigned_admins as $unassigned_admin) {
        if (!$departmentManager->isAdminInDepartment($unassigned_admin['adminId'])) {
            $departmentManager->assignAdminToUnassigned($unassigned_admin['adminId']);
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
    $sessionManager->setMessage($departmentManager->changeDepartments($dep_id, $ticket_types, $admins, $dep_name));
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
                $stmt = $db->prepare("SELECT * FROM departments");
                $stmt->execute();
                $select_deps = $stmt->get_result();
                while ($fetch_deps = $select_deps->fetch_assoc()) {
                ?>
                <div class="box">
                    <div class="breaking">
                        <p> ID: <span><?php echo $fetch_deps['departmentId']; ?></span> </p>
                    </div>
                    <div class="breaking">
                        <p> Name: <span><?php echo $fetch_deps['departmentName']; ?></span> </p>
                    </div>
                    <form method="POST">
                        <div class="breaking">
                            <p> Ticket types:
                                <?php
                                $ticket_types = $departmentManager->returnTicketTypesForDepartmentName($fetch_deps['departmentName']);
                                echo count($ticket_types);
                                echo "<textarea name='ticket_types' rows='" . count($ticket_types) . "' cols='21'>";
                                foreach ($ticket_types as $ticket_type) {
                                    echo $ticket_type['ticketTypeName'] . "\n";
                                }
                                echo "</textarea>";
                                ?>
                            </p>
                        </div>
                        <div class="breaking">
                            <p> Admins:
                                <?php
                                $admins = $departmentManager->returnAllBackendsForDepartmentId($fetch_deps['departmentId']);
                                echo count($admins);
                                echo "<textarea name='admins' rows='" . count($admins) . "' cols='21'>";
                                foreach ($admins as $admin) {
                                    echo $admin['adminEmail'] . "\n";
                                }
                                echo "</textarea>";
                                ?>
                            </p>
                        </div>

                        <input type="hidden" name="dep_id" value="<?php echo $fetch_deps['departmentId']; ?>">
                        <input type="hidden" name="dep_name" value="<?php echo $fetch_deps['departmentName']; ?>">
                        <?php if ($fetch_deps['departmentName'] != 'Super-admin' && $fetch_deps['departmentName'] != 'Unassigned'): ?>
                        <button type="submit" name="delete_dep" class="delete-btn" onclick="return confirmDeletingDepartment()">Delete</button>
                        <?php endif; ?>
                        <button type="submit" name="change_dep" class="btn" onclick="return confirmAlteringDepartments()">Change</button>
                    </form>
                </div>
                <?php
                }
                if ($select_deps->num_rows == 0) {
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
