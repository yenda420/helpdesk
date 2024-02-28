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
            $user_email = $_SESSION['user_email'];
            $select_tickets = mysqli_query($conn, "SELECT * FROM `tickets`") or die('query failed');
            while ($fetch_tickets = mysqli_fetch_assoc($select_tickets)) {

               $user_email = $_SESSION['user_email'];
               $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
               while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                  ?>
                  <div class="box">
                     <?php
                     if ($fetch_users['email'] == $user_email) {
                        $userId = $fetch_users['userId'];
                        //echo $userId;
                        if ($userId == $fetch_tickets['userId']) {
                           echo "<p> Ticket ID: <span>" . $fetch_tickets['ticketId'] . "</span> </p>";
                           echo "<p> Title: <span>" . $fetch_tickets['title'] . "</span> </p>";
                           echo "<p> Type: <span>" . $fetch_tickets['ticketType'] . "</span> </p>";
                           echo "<p> Description: <span>" . $fetch_tickets['ticketDesc'] . "</span> </p>";
                           echo "<p> Date: <span>" . $fetch_tickets['ticketDate'] . "</span> </p>";
                           echo "<p> User ID: <span>" . $fetch_tickets['userId'] . "</span> </p>";
                        }
                     }
               }
            }
            ;
            ?>

            </div>
      </section>
   </section>
   <script src="js/script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>