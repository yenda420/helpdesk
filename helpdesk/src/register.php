<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/UserRequest.php';
require 'classes/Utility.php';

$sessionManager = new SessionManager();
$messageManager = new MessageManager();

$sessionManager->startSession();

$database = new Database();
$db = $database->getConnection();
$userRequest = new UserRequest($db);

if (isset($_POST['submit'])) {
    $sessionManager->setMessage($userRequest->registerUser($_POST));
}

$message = $sessionManager->getMessage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request an account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php
    if ($message) {
        $messageManager->displayMessages([$message]);
        $sessionManager->unsetSession('message');
    }
    ?>

    <div class="form-container">
        <form action="" method="post">
            <h3>Request an account</h3>
            <input type="text" name="name" placeholder="Name" required class="box">
            <input type="text" name="surname" placeholder="Surname" required class="box">
            <input type="email" name="email" placeholder="Email" required class="box">
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Create password" class="box" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <div class="password-container">
                <input type="password" id="cpassword" name="cpassword" placeholder="Confirm password" class="box"
                    required>
                <i class="fas fa-eye" id="toggleConfirmPassword"></i>
            </div>
            <input type="submit" name="submit" value="Send request" class="btn">
            <p>Have an account? <a href="index.php">Login</a></p>
        </form>
    </div>
<script src="js/passwordEye.js"></script>
</body>

</html>