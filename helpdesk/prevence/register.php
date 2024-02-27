<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $surname = mysqli_real_escape_string($conn, $_POST['surname']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, hash('sha256', $_POST['password']));
   $cpass = mysqli_real_escape_string($conn, hash('sha256', $_POST['cpassword']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND userPasswd = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'User already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'Passwords don\'t match!';
      }else{
         mysqli_query($conn, "INSERT INTO `requests` SET reqName='$name', reqSurname='$surname', reqEmail='$email', reqPasswd='$pass'") or die('query failed');
         $message[] = 'Request for an account was successful!';
         header('location:index.php');
      }
   }

}

?>

<!DOCTYPE html>
<html lang="cs">
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
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
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
