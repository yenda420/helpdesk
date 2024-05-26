<?php

include 'config.php';
require('functions.php');

session_start();
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   header('location:index.php');
}
if (isset($_POST['send_btn'])) {
   $title = $_POST['title'];
   $type = $_POST['type'];
   $description = $_POST['description'];
   $date = date('Y-m-d');
   $stmt = $conn->prepare("INSERT INTO `tickets` (`title`, `status`, `ticketDesc`,`ticketDate`,`userId`,`ticketTypeId`) VALUES (?,'Waiting',?,?,?,?)");
   $stmt->bind_param("sssii", $title, $description, $date, $user_id, $type);
   $send_query = $stmt->execute();
   if ($send_query) {
      $_SESSION['message'] = "Ticket sent successfully";
   } else {
      $_SESSION['message'] = "Error sending ticket";
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
                  $ticket_types = returnTicketTypes($conn);
                  foreach ($ticket_types as $ticket_type) {
                     echo "<option value='{$ticket_type['ticketTypeId']}'>{$ticket_type['ticketTypeName']}</option>";
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