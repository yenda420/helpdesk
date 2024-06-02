<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/UserManager.php';
require 'classes/MessageManager.php';
require 'classes/DepartmentManager.php';
require 'classes/Utility.php';

$messageManager = new MessageManager();
$sessionManager = new SessionManager();
$sessionManager->startSession();

$adminId = $sessionManager->getAdminId();
$adminDepartment = $sessionManager->getDepartments()[0];
if (!$adminId || $adminDepartment != 'Super-admin') {
    header('location:admin_page.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$departmentManager = new DepartmentManager($db);

if (isset($_POST['submit'])) {
    $name = $_POST['createDepartmentName'];
    if ($departmentManager->Utility->departmentExists($name)) {
        $sessionManager->setMessage('Department already exists.');
    } else {
        if ($departmentManager->createDepartment($name)) {
            $sessionManager->setMessage('Department was successfully created.');
        } else {
            $sessionManager->setMessage('Query failed.');
        }
    }
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
    <title>Add a department</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="dashboard">
        <section class="tickets">
            <h1 class="title">Add a department</h1>
        </section>

        <section class="checkout">
            <form method="post" id="adminForm">
                <div class="flex">
                    <div class="inputBox">
                        <input type="text" class="textInput" name="createDepartmentName" placeholder="Department name" required />
                    </div>
                </div>
                <input type="submit" value="Add" class="btn" name="submit">
            </form>
        </section>
    </section>

    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>
