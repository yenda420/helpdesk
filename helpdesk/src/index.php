<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/AuthManager.php';

$messageManager = new MessageManager();
$sessionManager = new SessionManager();
$sessionManager->destroySession();
$sessionManager->startSession();

$database = new Database();
$authManager = new AuthManager($database);

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $authResult = $authManager->authenticateUser($email, $password);

    if ($authResult) {
        if ($authResult['role'] == 'user') {
            $user = $authResult['data'];
            $sessionManager->setSession('user_name', $user['userName']);
            $sessionManager->setSession('user_email', $user['userEmail']);
            $sessionManager->setSession('user_id', $user['userId']);
            $sessionManager->setSession('user_surname', $user['userSurname']);
            header('location:home.php');
        } elseif ($authResult['role'] == 'admin') {
            $admin = $authResult['data'];
            $sessionManager->setSession('admin_name', $admin['adminName']);
            $sessionManager->setSession('admin_email', $admin['adminEmail']);
            $sessionManager->setSession('admin_id', $admin['adminId']);
            $sessionManager->setSession('admin_surname', $admin['adminSurname']);

            $departments = $authManager->getAdminDepartments($admin['adminId']);
            foreach ($departments as $department) {
               $sessionManager->addToSessionArray('departmentId', $department['departmentId']);
               $sessionManager->addToSessionArray('department', $authManager->getDepartmentName($department['departmentId']));
                print_r($sessionManager->getDepartments());
            }
            header('location:admin_page.php');
        }
    } else {
        $sessionManager->setSession('message', 'Invalid email or password');
    }
}

$message = $sessionManager->getSession('message');
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php
   if ($message) {
      $messageManager->displayMessages([$message]);
   }
   ?>

   <div class="form-container">
      <form action="" method="post">
         <h3>Login</h3>
         <input type="email" name="email" placeholder="Email" class="box" required>
         <input type="password" name="password" placeholder="Password" class="box" required>
         <input type="submit" name="submit" value="Login" class="btn">
         <p>Don't have an account? <a href="register.php">Request an account</a></p>
      </form>
   </div>

</body>
</html>
