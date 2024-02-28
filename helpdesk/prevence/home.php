<?php

include 'config.php';

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Techbase</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <section class="dashboard">
      <section class="users">
         <h1 class="title">Account</h1>
         <div class="box-container">

            <?php
            $user_email =  $_SESSION['user_email'];
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
               ?>
               <div class="box">
               <?php 
               if($fetch_users['email'] == $user_email)
               {
                  echo "<p> ID: <span>".$fetch_users['userId']."</span> </p>";
               echo "<p> Name: <span>".$fetch_users['userName']."</span> </p>";
               echo "<p> Surname: <span>".$fetch_users['userSurname']."</span> </p>";
               echo "<p> Email: <span>".$fetch_users['email']."</span> </p>";
               }
               ?>
               <?php
            }
            ;
            ?>

         </div>
      </section>
   </section>
   <script src="js/admin_script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>