<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/UserManager.php';
require 'classes/Utility.php';

$sessionManager = new SessionManager();
$messageManager = new MessageManager();

$sessionManager->startSession();

$adminId = $sessionManager->getAdminId();
$adminDepartment = $sessionManager->getDepartments()[0];
if (!$adminId || $adminDepartment != 'Super-admin') {
    header('location:admin_page.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$userManager = new UserManager($db);

if (isset($_POST['submit'])) {
    $_SESSION['message'] = $userManager->createAdmin($_POST);
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
    <title>Create an admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="js/jquery.js"></script>
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="dashboard">
        <section class="tickets">
            <h1 class="title">Create an Admin</h1>
        </section>

        <section class="checkout">

            <form method="post" id="adminForm">
                <div class="flex">
                    <div class="inputBox">
                        <input type="text" class="textInput" name="createAdminName" placeholder="Name" required />
                    </div>
                    <div class="inputBox">
                        <input type="text" class="textInput" name="createAdminSurname" placeholder="Surname" required />
                    </div>
                    <div class="inputBox">
                        <input type="email" class="textInput" name="createAdminEmail" placeholder="Email" required />
                    </div>
                    <div class="inputBox">
                        <input type="password" class="textInput" name="createAdminPasswd" placeholder="Password" required />
                    </div>
                    <div class="inputBox">
                        <input type="password" class="textInput" name="createAdminPasswdConf" placeholder="Confirm password" required />
                    </div><br/>
                        <?php
                        $departments = $userManager->returnDepartments();
                        $i = 0;

                        foreach ($departments as $department) {
                            $i++;
                            echo '<div class="" style="display: flex;
                            align-items: center;
                            margin-bottom: 10px;">';
                            echo "<input style='margin-right: 5px; order: -1;' type='checkbox' class='department' id=" . $i . " 
                                name='department[]' value=" . $department['departmentName'] . ">";
                            echo "<label for=" . $i . ">" . $department['departmentName'] . "</label></div>";
                        }
                        ?>
                </div>
                <input type="submit" value="Create" class="btn" name="submit">
            </form>

        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>
