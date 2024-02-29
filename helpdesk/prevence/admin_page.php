<?php

include 'config.php';

session_start();

if(isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:index.php'); 
}
if(isset($_POST['delete_request'])) {
   $request_id = $_POST['request_id'];
   // Perform the deletion query
   $delete_query = mysqli_query($conn, "DELETE FROM `requests` WHERE requestId = $request_id");
   if($delete_query) {
      $message[] = "Request deleted successfully";
   } else {
      $message[] = "Error deleting request";
   }
}
//if you press accept, the user is created and inputted into the users table
if(isset($_POST['accept_request'])) {
   $request_id = $_POST['request_id'];
   $select_request = mysqli_query($conn, "SELECT * FROM `requests` WHERE requestId = $request_id") or die('query failed');
   $fetch_request = mysqli_fetch_assoc($select_request);
   $req_name = $fetch_request['reqName'];
   $req_surname = $fetch_request['reqSurname'];
   $req_password = $fetch_request['reqPasswd'];
   $req_email = $fetch_request['reqEmail'];
   $insert_user = mysqli_query($conn, "INSERT INTO `users` (userName, userSurname, userPasswd, userType,email) VALUES ('$req_name', '$req_surname', '$req_password', 'frontend','$req_email')") or die('query failed');
   if($insert_user) {
       $delete_query = mysqli_query($conn, "DELETE FROM `requests` WHERE requestId = $request_id");
       if($delete_query) {
         $message[] = "User created successfully";
       } else {
         $message[] = "Error deleting request";
       }
   } else {
      $message[] = "Error creating user"; 
   }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>AdminSpace</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">
   <section class="users">

   <h1 class="title">Requests</h1>

   <div class="box-container">
      <?php
         $select_requests = mysqli_query($conn, "SELECT * FROM `requests`") or die('query failed');
         while($fetch_requests = mysqli_fetch_assoc($select_requests)){
      ?>
      <div class="box">
         <p> ID : <span><?php echo $fetch_requests['requestId']; ?></span> </p>
         <p> Name : <span><?php echo $fetch_requests['reqName']; ?></span> </p>
         <p> Surname : <span><?php echo $fetch_requests['reqSurname']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_requests['reqEmail']; ?></span> </p>
         <!-- Add the delete button -->
         <form method="POST">
            <input type="hidden" name="request_id" value="<?php echo $fetch_requests['requestId']; ?>">
            <button type="submit" name="delete_request" class="delete-btn">Delete</button>
            <button type="submit" name="accept_request" class="btn">Accept</button>
         </form>
      </div>
      <?php
         };
         if(mysqli_num_rows($select_requests) == 0) {
            echo '<p class="empty">No requests</p>';
         }
      ?>
   </div>
</section>
</section>
<script src="js/admin_script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>