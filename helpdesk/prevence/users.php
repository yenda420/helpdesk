<?php

include 'config.php';

session_start();

$frontendUsers = returnAllFrontendUsers($conn);

if(isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:index.php');
}
if(isset($_POST['delete_user'])) {
   $user_id = $_POST['user_id'];
   // Perform the deletion query
   $delete_query = mysqli_query($conn, "DELETE FROM `tickets` WHERE userId = '$user_id'");
   if($delete_query) {
       mysqli_query($conn,"DELETE FROM `users` where userId = '$user_id'");
       $message[] = "User deleted successfully";
   } else {
       $message[] = "Error deleting user";
   }
}



?>

<!DOCTYPE html>
<html lang="cs">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>účty</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>
<section class="dashboard">
<section class="users">

   <h1 class="title">Users</h1>

   <div class="box-container">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         while($fetch_users = mysqli_fetch_assoc($select_users)){
            if ($fetch_users['userType'] != 'backend') {
      ?>
    <div class="box">
    <div class="breaking"><p> ID : <span><?php echo $fetch_users['userId']; ?></span> </p></div>
    <div class="breaking"><p> Name : <span><?php echo $fetch_users['userName']; ?></span> </p></div>
    <div class="breaking"><p> Surname : <span><?php echo $fetch_users['userSurname']; ?></span> </p></div>
    <div class="breaking"><p> Email : <span><?php echo $fetch_users['email']; ?></span> </p></div>
    <div class="breaking"><p> Type : <span><?php echo $fetch_users['userType']; ?></span> </p></div>
         <!-- Add the delete button -->
         <form method="POST">
            <input type="hidden" name="user_id" value="<?php echo $fetch_users['userId']; ?>"> <br>
            <button type="submit" name="delete_user" class="delete-btn">Delete</button>
         </form>
      </div>
      <?php
         }};
         if(mysqli_num_rows($frontendUsers) == 0) {
            echo '<p class="empty">No users</p>';
         }
      ?>
   </div>

</section>
</section>
<script src="js/admin_script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>