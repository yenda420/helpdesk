<?php

include 'config.php';

session_start();

if(isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:start.php');
}
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `requests` WHERE requestId = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
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

   <h1 class="title">informace</h1>

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
         <a href="admin_users.php?delete=<?php echo $fetch_requests['requestId']; ?>" onclick="return confirm('odstranit tohoto uživatele?');" class="delete-btn">odstranit</a>
      </div>
      <?php
         };
      ?>
   </div>

</section>

</section>

<script src="js/admin_script.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>