<?php

session_start();
session_unset();
session_destroy();

include 'config.php';
require('functions.php');

session_start();

if (isset($_POST['submit'])) {
   $pass = hash('sha256', mysqli_real_escape_string($conn, $_POST['password']));
   $email = mysqli_real_escape_string($conn, $_POST['email']);

   // Prepare a SELECT statement for users
   $stmt = $conn->prepare("SELECT * FROM `users` where userPasswd = ? and userEmail = ?");
   $stmt->bind_param("ss", $pass, $email);
   $stmt->execute();
   $select_users = $stmt->get_result();

   // Prepare a SELECT statement for admins
   $stmt = $conn->prepare("SELECT * FROM `admins` where adminPasswd = ? and adminEmail = ?");
   $stmt->bind_param("ss", $pass, $email);
   $stmt->execute();
   $select_admins = $stmt->get_result();

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

         // Prepare a SELECT statement for departments
         $stmt = $conn->prepare("SELECT * FROM `department_lists` where adminId = ?");
         $stmt->bind_param("i", $_SESSION['admin_id']);
         $stmt->execute();
         $select_departments = $stmt->get_result();

         if (mysqli_num_rows($select_departments) > 0) {
            while ($fetch_departments = mysqli_fetch_assoc($select_departments)) {
               $_SESSION['departmentId'][] = $fetch_departments['departmentId'];
               $_SESSION['department'][] = returnDepartmentName($conn, $fetch_departments['departmentId']);
            }
         } else {
            $_SESSION['message'] = 'Invalid email or password';
         }
         header('location:admin_page.php');
      }
   } else {
      $_SESSION['message'] = 'Invalid email or password';
   }
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