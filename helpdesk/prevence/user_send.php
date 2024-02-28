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
   $send_query = mysqli_query($conn, "INSERT INTO `tickets` (`title`, `ticketType`, `ticketDesc`,`ticketDate`,`userId`) VALUES ('$title', '$type', '$description','$date','$user_id')");
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
            <input type="text" name="title" required placeholder="Software issues">
         </div>
         <div class="inputBox">
            <span>Ticket type :</span>
            <select name="type">
               <option value="x">x</option>
               <option value="y">y</option>
               <option value="z">z</option>
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