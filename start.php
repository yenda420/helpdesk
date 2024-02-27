<?php

include 'config.php';
session_start();

if(isset($_POST['login'])){

    $name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    //echo $name . " " . $last_name . " " . $pass . "<br>";

   $select_users = mysqli_query($conn, "SELECT * FROM `users` where userName = '$name' and userSurname = '$last_name' and userPasswd = '$pass'") or die('query failed');
   //$select_users = mysqli_query($conn, "SELECT * FROM `users` ") or die('query failed');
   if(mysqli_num_rows($select_users) > 0){
    while($fetch_users = mysqli_fetch_assoc($select_users)){
        echo $fetch_users['userName'] . " " . $fetch_users['userSurname'] . " " . $fetch_users['userPasswd'] . " " . $fetch_users['userType'] . "<br>";
        if($fetch_users['userType'] == 'backend'){
            $_SESSION['admin_name'] = $fetch_users['userName'];
            $_SESSION['admin_email'] = $fetch_users['userSurname'];
            $_SESSION['admin_id'] = $fetch_users['userId'];
            header('location:admin_page.php');
        }
        if($fetch_users['userType'] == 'frontend'){
            $_SESSION['user_name'] = $fetch_users['userName'];
            $_SESSION['user_email'] = $fetch_users['userSurname'];
            $_SESSION['user_id'] = $fetch_users['userId'];
            header('location:home.php');
        }
    }   
}
}
if(isset($_POST['request'])){
    $req_name = mysqli_real_escape_string($conn, $_POST['req_name']);
    $req_surname = mysqli_real_escape_string($conn, $_POST['req_surname']);
    $req_password = mysqli_real_escape_string($conn, $_POST['req_password']);
    $insert_request = mysqli_query($conn, "INSERT INTO `requests` (reqName, reqSurname, reqPasswd) VALUES ('$req_name', '$req_surname', '$req_password')") or die('query failed');
    if($insert_request){
        echo "Request sent";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Helpdesk</title>
</head>
<body>
<h1>Tickets</h1>
    <form action="" method="post">
        <input type="text" name="req_name" placeholder="First name">
        <input type="text" name="req_surname" placeholder="Last name">
        <input type="password" name="req_password" placeholder="Password">
        <input type="submit" name="request" value="Send">
    </form>
<h1>Login</h1>
<form action="" method="post">
        <input type="text" name="first_name" placeholder="First name">
        <input type="text" name="last_name" placeholder="Last name">
        <input type="password" name="password" placeholder="Password">
    
        <input type="submit" name="login" value="Login">
       
    </form>
</body>
</html>