<?php

include 'config.php';
require('functions.php');

session_start();
if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:index.php');
}
if (isset($_POST['delete_user'])) {
   $user_id = $_POST['user_id'];
   // Perform the deletion query
   $delete_query = mysqli_query($conn, "DELETE FROM `tickets` WHERE userId = '$user_id'");
   if ($delete_query) {
      mysqli_query($conn, "DELETE FROM `users` where userId = '$user_id'");
      $message[] = "User deleted successfully";
   } else {
      $message[] = "Error deleting user";
   }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>účty</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php';
   if (!isset($_POST['users'])) {
      $_POST['users'] = null;
   }
   ?>
   <section class="dashboard">
      <section class="users">

         <h1 class="title">Users</h1>
         <form method="post">
            <div class="flex">
               <div class="inputBox">
                  <select name="users" class="select" required>
                     <option value="all">--- Choose a user type ---</option>
                     <option <?php if($_POST['users'] == 'frontend') echo 'selected'?> value="frontend">Frontend</option>
                     <option <?php if($_POST['users'] == 'backend') echo 'selected'?> value="backend">Backend</option>
                  </select>
               </div>
               <div class="inputBox">
                  <button type="submit" name="filter" class="btn">Filter</button>
               </div><br>
            </div>

         <div class="box-container">
            <?php
             $frontendUsers = returnAllFrontendUsers($conn);
             $backendUsers = returnAllBackendUsers($conn);
            if (isset($_POST['filter']) && $_POST['users'] == 'frontend' || ($_POST['users'] == 'all' || !isset($_POST['filter']))) {
               foreach ($frontendUsers as $user) {
                  echo '<div class="box">
                  <div class="breaking"><p> Name : <span>'.$user['userName'].'</span> </p></div>
                  <div class="breaking"><p> Surname : <span>'.$user['userSurname'].'</span> </p></div>
                  <div class="breaking"><p> Email : <span>'.$user['userEmail'].'</span> </p></div>
                  <input type="hidden" name="user_id" value="' . $user['userId'] . '"><br>
                  <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                  </div>';
               }
            }
            if (isset($_POST['filter']) && $_POST['users'] == 'backend' || ($_POST['users'] == 'all' || !isset($_POST['filter']))) {
               foreach ($backendUsers as $user) {
                  echo '<div class="box">
                  <div class="breaking"><p> Name : <span>'.$user['adminName'].'</span> </p></div>
                  <div class="breaking"><p> Surname : <span>'.$user['adminSurname'].'</span> </p></div>
                  <div class="breaking"><p> Email : <span>'.$user['adminEmail'].'</span> </p></div>
                  <div class="breaking"><p> Department : <span>'.returnDepartmentName($conn, $user['departmentId'])['departmentName'].'</span> </p></div>
                  <input type="hidden" name="user_id" value="' . $user['adminId'] . '"> <br>
                  <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                  </div>';
               }
            }
            //if there are no users
            if (count($frontendUsers) == 0 && count($backendUsers) == 0){
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


