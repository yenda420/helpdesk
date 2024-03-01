<?php

include 'config.php';

session_start();
if(isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   header('location:index.php');
}
if(isset($_POST['send_btn'])) {
   $title = $_POST['title'];
   $type = $_POST['type'];
   $description = $_POST['description'];
   $date = date('Y-m-d');
   if($type == "Billing and Payments") {
      $department = "Sales";
   } else {
      $department = "Logistics";
   }
   $send_query = mysqli_query($conn, "INSERT INTO `tickets` (`title`, `ticketType`, `ticketDesc`,`ticketDate`,`userId`,`department`) VALUES ('$title', '$type', '$description','$date','$user_id', '$department')");
   if($send_query) {
      $message[] = "Ticket sent successfully";
   } else {
      $message[] = "Error sending ticket";
   }
}
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
   <section class="checkout">

   <form action="" method="post">
      <h3>What's wrong?</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Ticket title: </span>
            <input type="text" name="title" required placeholder="An issue with...">
         </div>
         <div class="inputBox">
            <span>Ticket type :</span>
            <select name="type" required>
               <option value="" selected>---Select type---</option>
              <?php
                  //select all ticket types (ticketType, enum) from table tickets
                  $type_query = mysqli_query($conn, "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'tickets' AND COLUMN_NAME = 'ticketType'");
                  $type_row = mysqli_fetch_assoc($type_query);
                  $types = explode(",", str_replace("'", "", substr($type_row['COLUMN_TYPE'], 5, (strlen($type_row['COLUMN_TYPE'])-6))));
                  
                  foreach($types as $type) {
                     echo "<option value='$type'>$type</option>";
                  }
               ?>
            </select>
         </div>
         <div class="inputBox">
            <span>Description:</span> <br>
            <textarea name="description" required placeholder="Describe the problem"></textarea>
         </div>
      </div>
      <input type="submit" value="Send" class="btn" name="send_btn">
   </form>

</section>
   <script src="js/script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>