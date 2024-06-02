<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/TicketManager.php';

$messageManager = new MessageManager();
$sessionManager = new SessionManager();
$sessionManager->startSession();

$userId = $sessionManager->getUserId();
$userEmail = $sessionManager->getUserEmail();

if (!$userId) {
    header('location:index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$ticketManager = new TicketManager($db);

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
            if ($ticketManager->numberOfTicketsForUserId($userId) != 0) {
               $tickets = $ticketManager->getTicketsByUserId($userId);
               while ($ticket = $tickets->fetch_assoc()) {
                  $ticketDate = date_create($ticket['ticketDate']);
            ?>
               <div class="box">
                  <div class="breaking">
                     <p> Title: <span><?php echo $ticket['title']; ?></span> </p>
                  </div>
                  <div class="breaking">
                     <p> Status: <span><?php echo $ticket['status']; ?></span> </p>
                  </div>
                  <div class="breaking">
                     <p> Type: <span><?php echo $ticketManager->getTicketTypeName($ticket['ticketTypeId'])['ticketTypeName']; ?></span> </p>
                  </div>
                  <div class="breaking">
                     <p> Date: <span><?php echo date_format($ticketDate, 'd.m.Y'); ?></span> </p>
                  </div>
                  <div class="breaking">
                     <p> Description: <span><?php echo $ticket['ticketDesc']; ?></span> </p>
                  </div>
               </div>
            <?php
               }
            } else {
            ?>
               <p class="empty">No tickets</p>
            <?php
            }
            ?>

         </div>
      </section>
   </section>

   <script src="js/script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>
