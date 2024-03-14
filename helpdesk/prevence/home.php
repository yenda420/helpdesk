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
   <title>Your Tickets</title>


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

               $stmt = $conn->prepare("SELECT * FROM tickets");
               $stmt->execute();
               $select_tickets = $stmt->get_result();

               while ($fetch_tickets = $select_tickets->fetch_assoc()) {
                  if ($userId == $fetch_tickets['userId']) {
                     $ticketDate = date_create($fetch_tickets['ticketDate']); ?>

                     <div class="box">
                        <div class="breaking">
                           <p> Title: <span>
                                 <?php echo $fetch_tickets['title']; ?>
                              </span> </p>
                        </div>
                        <div class="breaking">
                           <p> Status: <span>
                                 <?php echo $fetch_tickets['status']; ?>
                              </span> </p>
                        </div>
                        <div class="breaking">
                           <p> Type: <span>
                                 <?php echo returnTicketTypeName($conn, $fetch_tickets['ticketTypeId'])['ticketTypeName']; ?>
                              </span> </p>
                        </div>
                        <div class="breaking">
                           <p> Date: <span>
                                 <?php echo date_format($ticketDate, 'd.m.Y'); ?>
                              </span> </p>
                        </div>
                        <div class="breaking">
                           <p> Description: <span>
                                 <?php echo $fetch_tickets['ticketDesc']; ?>
                              </span> </p>
                        </div>
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