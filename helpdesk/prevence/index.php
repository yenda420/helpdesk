<?php

include 'config.php';
require('functions.php');
session_start();
if (isset($_POST['submit'])) {

   $pass = hash('sha256', mysqli_real_escape_string($conn, $_POST['password']));
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   //echo $name . " " . $last_name . " " . $pass . "<br>";

   $select_users = mysqli_query($conn, "SELECT * FROM `users` where userPasswd = '$pass' and userEmail='$email'") or die('query failed');
   $select_admins = mysqli_query($conn, "SELECT * FROM `admins` where adminPasswd = '$pass' and adminEmail='$email'") or die('query failed');
   //$select_users = mysqli_query($conn, "SELECT * FROM `users` ") or die('query failed');
   if (mysqli_num_rows($select_users) > 0) {
      while ($fetch_users = mysqli_fetch_assoc($select_users)) {
            $_SESSION['user_name'] = $fetch_users['userName'];
            $_SESSION['user_email'] = $fetch_users['userEmail'];
            $_SESSION['user_id'] = $fetch_users['userId'];
            $_SESSION['user_surname'] = $fetch_users['userSurname'];
            header('location:home.php');
      }
   } else if (mysqli_num_rows($select_admins) > 0) {
      $_SESSION['admin_departments'] = array();
      while ($fetch_admins = mysqli_fetch_assoc($select_admins)) {
         $_SESSION['admin_name'] = $fetch_admins['adminName'];
         $_SESSION['admin_email'] = $fetch_admins['adminEmail'];
         $_SESSION['admin_id'] = $fetch_admins['adminId'];
         $_SESSION['admin_surname'] = $fetch_admins['adminSurname'];
         //there is a table department_lists, an admin can have multiple departments
         $select_departments = mysqli_query($conn, "SELECT * FROM `department_lists` where adminId = '{$_SESSION['admin_id']}'") or die('query failed');
         if (mysqli_num_rows($select_departments) > 0) {
            while ($fetch_departments = mysqli_fetch_assoc($select_departments)) {
               //if there are more departmentIds for the same adminId, save them in an array
               $_SESSION['departmentId'][]= $fetch_departments['departmentId'];
               //now do the same for the department names
               $_SESSION['department'][] = returnDepartmentName($conn, $fetch_departments['departmentId']);

              
            }
         } else {
            $message[] = 'Invalid email or password';
         }
         header('location:admin_page.php');
      }
   } else {
      $message[] = 'Invalid email or password';
   }

}

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
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            <script>
               setTimeout(function() {
                  document.querySelector(".message").style.opacity = "0";
                  document.querySelector(".message").style.transition = "all 0.5s";
                  setTimeout(function() {
                     document.querySelector(".message").remove();
                  }, 500);
               }, 3500);
            </script>
         </div>
         ';
      }
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