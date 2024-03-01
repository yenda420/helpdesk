<?php

   include 'config.php';
   require('functions.php');

   if (isset($_POST['submit'])) {

      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $surname = mysqli_real_escape_string($conn, $_POST['surname']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $pass = mysqli_real_escape_string($conn, hash('sha256', $_POST['password']));
      $cpass = mysqli_real_escape_string($conn, hash('sha256', $_POST['cpassword']));

      $sqlInsert = "
         INSERT INTO `requests` 
         SET reqName='$name', reqSurname='$surname', reqEmail='$email', reqPasswd='$pass';
      ";

      if ($pass == $cpass) {
         if (strlen($_POST['password']) >= 8) {
            if (preg_match('/[A-Z]/', $_POST['password'])) {
               if (preg_match('/\d/', $_POST['password'])) {
                  if (preg_match("/[^a-zA-Z0-9]/", $_POST['password'])) {
                     if (!emailInDatabase($conn, $email)) {
                        if (mysqli_query($conn, $sqlInsert)) {
                           $message[] = 'Request for an account was successful.';
                        } else {
                           $message[] = 'Query failed.';
                        }
                     } else {
                        $message[] = 'Aaccount with this email already exists.';
                     }
                  } else {
                     $message[] = 'Password needs at least 1 special character.';
                  }
               } else {
                  $message[] = 'Password needs at least 1 number.';
               }
            } else {
               $message[] = 'Password needs at least 1 upper case character.';
            }
         } else {
            $message[] = 'Password needs at least 8 characters.';
         }
      } else {
         $message[] = 'Passwords don\'t match!';
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Request</title>
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
      <h3>Request an account</h3>
      <input type="text" name="name" placeholder="Name" required class="box">
      <input type="text" name="surname" placeholder="Surname" required class="box">
      <input type="email" name="email" placeholder="Email" required class="box">
      <input type="password" name="password" placeholder="Create password" required class="box">
      <input type="password" name="cpassword" placeholder="Confirm password" required class="box">
      <input type="submit" name="submit" value="Send request" class="btn">
      <p>Have an account? <a href="index.php">Login</a></p>
   </form>

</div>

</body>
</html>
