<?php

include 'config.php';
include 'functions.php';

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
         <h1 class="title">Your Tickets</h1>
         <div class="box-container">

            <?php
            $user_email = $_SESSION['user_email'];
            $userId = $_SESSION['user_id'];
            if (numberOfTicketsForUserId($conn, $userId) != 0) {

               $select_tickets = mysqli_query($conn, "SELECT * FROM `tickets`") or die('query failed');

               while ($fetch_tickets = mysqli_fetch_assoc($select_tickets)) {
                     if ($userId == $fetch_tickets['userId']) {
                        ?>
                        <div class="box">
                           <p> Title: <span>
                                 <?php echo $fetch_tickets['title']; ?>
                              </span> </p>
                           <p> Type: <span>
                                 <?php echo $fetch_tickets['ticketType']; ?>
                              </span> </p>
                           <p> Date: <span>
                                 <?php echo $fetch_tickets['ticketDate']; ?>
                              </span> </p>
                           <p> Description: <span>
                                 <?php echo $fetch_tickets['ticketDesc']; ?>
                              </span> </p>
                        </div>
                        <?php
                     }
                  }
                  
            } else { ?>
               <p class="empty">No tickets</p>
            <?php } ?>

         </div>
      </section>
   </section>
   <script src="js/script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>