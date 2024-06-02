<?php

require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/TicketManager.php';
require 'classes/TicketTypeManager.php';
require 'classes/Utility.php';
require 'classes/Database.php';
require 'classes/UserManager.php';

$sessionManager = new SessionManager();
$messageManager = new MessageManager();
$database = new Database();
$db = $database->getConnection();
$ticketManager = new TicketManager($db);
$ticketTypeManager = new TicketTypeManager($db);

$sessionManager->startSession();
$user_id = $sessionManager->getUserId();

if (!$user_id) {
    header('location:index.php');
    exit;
}

if (isset($_POST['send_btn'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $date = date('Y-m-d');
    $userId = $sessionManager->getUserId();

    if ($ticketManager->sendTicket($title, $type, $description, $date, $userId)) {
        $sessionManager->setMessage("Ticket sent successfully");
    } else {
        $sessionManager->setMessage("Error sending ticket");
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Send a ticket</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <section class="checkout">

      <form action="" method="post">
         <h3>What's wrong?</h3>
         <div class="flex">
            <div class="inputBox">
               <span>Ticket title: </span>
               <input type="text" name="title" required placeholder="An issue with..." maxlength="45">
            </div>
            <div class="inputBox">
               <span>Ticket type:</span>
               <select name="type" required>
                  <option value="" selected>--- Select type ---</option>
                  <?php
                  $ticketTypes = $ticketTypeManager->getTicketTypes();
                  while ($ticketType = $ticketTypes->fetch_assoc()) {
                     echo "<option value='{$ticketType['ticketTypeId']}'>{$ticketType['ticketTypeName']}</option>";
                  }
                  ?>
               </select>
            </div>
            <div class="inputBox">
               <span>Description:</span> <br>
               <textarea name="description" required placeholder="Describe the problem" maxlength="10000"></textarea>
            </div>
         </div>
         <input type="submit" value="Send" class="btn" name="send_btn">
      </form>

   </section>

   <script src="js/script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>
