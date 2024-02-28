<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $pass = hash('sha256',mysqli_real_escape_string($conn, $_POST['password']));
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   //echo $name . " " . $last_name . " " . $pass . "<br>";

  $select_users = mysqli_query($conn, "SELECT * FROM `users` where userPasswd = '$pass' and email='$email'") or die('query failed');
  //$select_users = mysqli_query($conn, "SELECT * FROM `users` ") or die('query failed');
  if(mysqli_num_rows($select_users) > 0){
   while($fetch_users = mysqli_fetch_assoc($select_users)){
       echo $fetch_users['userName'] . " " . $fetch_users['userSurname'] . " " . $fetch_users['userPasswd'] . " " . $fetch_users['userType'] . "<br>";
       if($fetch_users['userType'] == 'backend'){
           $_SESSION['admin_name'] = $fetch_users['userName'];
           $_SESSION['admin_email'] = $fetch_users['email'];
           $_SESSION['admin_id'] = $fetch_users['userId'];
          header('location:admin_page.php');
       }
       if($fetch_users['userType'] == 'frontend'){
           $_SESSION['user_name'] = $fetch_users['userName'];
           $_SESSION['user_email'] = $fetch_users['email'];
           $_SESSION['user_id'] = $fetch_users['userId'];
           $_SESSION['user_surname'] = $fetch_users['userSurname'];
          header('location:home.php');
       }
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
   <title>Login</title>
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
      <h3>Login</h3>
      <input type="email" name="email" placeholder="Email"  class="box" required>
      <input type="password" name="password" placeholder="Password" class="box" required>
      <input type="submit" name="submit" value="Login" class="btn">
      <p>Don't have an account? <a href="register.php">Request an account</a></p>
   </form>

</div>

</body>
</html>
