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
   if ($_SESSION['department'] == 'All') {
      if (isset($_POST['user_id'])) {
         $user_id = $_POST['user_id'];
         $delete_query = mysqli_query($conn, "DELETE FROM `tickets` WHERE userId = $user_id");
         if ($delete_query) {
            $delete_query = mysqli_query($conn, "DELETE FROM `users` WHERE userId = $user_id");
            $message[] = "User deleted successfully";
         } else {
            $message[] = "Error deleting user";
         }
      } else if (isset($_POST['admin_id'])) {
         $admin_id = $_POST['admin_id'];
         $delete_query = mysqli_query($conn, "DELETE FROM `admins` WHERE adminId = $admin_id");
         if ($delete_query) {
            $message[] = "Admin deleted successfully";
         } else {
            $message[] = "Error deleting admin";
         }


      }
   } else {
      $message[] = "You don't have permission to delete users";
   }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

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
                     <option <?php if ($_POST['users'] == 'frontend')
                        echo 'selected' ?> value="frontend">Frontend
                        </option>
                        <option <?php if ($_POST['users'] == 'backend')
                        echo 'selected' ?> value="backend">Backend</option>
                     </select>
                  </div>
                  <div class="inputBox">
                     <button type="submit" name="filter" class="btn">Show users</button>
                  </div><br>
               </div>

               <div class="box-container">
                  <?php
                     $frontendUsers = returnAllFrontendUsers($conn);
                     $backendUsers = returnAllBackendUsers($conn);
                     if (isset($_POST['filter']) && $_POST['users'] == 'frontend' || ($_POST['users'] == 'all' || !isset($_POST['filter']))) {
                        foreach ($frontendUsers as $user) {
                           echo '<div class="box">
                                    <div class="breaking"><p> ID : <span>' . $user['userId'] . '</span> </p></div>
                                    <div class="breaking"><p> Name : <span>' . $user['userName'] . '</span> </p></div>
                                    <div class="breaking"><p> Surname : <span>' . $user['userSurname'] . '</span> </p></div>
                                    <div class="breaking"><p> Email : <span>' . $user['userEmail'] . '</span> </p></div>';
                           if ($_SESSION['department'] == 'Super-admin') {
                              echo '<input type="hidden" name="user_id" value="' . $user['userId'] . '"><br>
                                    <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingUser()">Delete</button>
                                 </div>';
                           } else {
                              echo '</div>';
                           }
                        }
                     }
                     if (isset($_POST['filter']) && $_POST['users'] == 'backend' || ($_POST['users'] == 'all' || !isset($_POST['filter']))) {
                        foreach ($backendUsers as $user) {
                           echo '<div class="box">
                                    <div class="breaking"><p> ID : <span>' . $user['adminId'] . '</span> </p></div>
                                    <div class="breaking"><p> Name : <span>' . $user['adminName'] . '</span> </p></div>
                                    <div class="breaking"><p> Surname : <span>' . $user['adminSurname'] . '</span> </p></div>
                                    <div class="breaking"><p> Email : <span>' . $user['adminEmail'] . '</span> </p></div>';
                           echo '<div class="breaking"><p> Department : <span>';
                           $select_departments = mysqli_query($conn, "SELECT * FROM `department_lists` where adminId = '{$user['adminId']}'") or die('query failed');
                           $departmentNames = [];
                           if (mysqli_num_rows($select_departments) > 0) {
                              while ($fetch_departments = mysqli_fetch_assoc($select_departments)) {
                                 $departmentNames[] = returnDepartmentName($conn, $fetch_departments['departmentId'])['departmentName'];
                              }
                           }
                           echo implode(', ', $departmentNames);
                           echo '</span> </p></div>';
                           if ($_SESSION['department'] == 'Super-admin') {
                              echo '<input type="hidden" name="admin_id" value="' . $user['adminId'] . '"> <br>
                                       <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingAdmin()">Delete</button>
                                 </div>';
                           } else {
                              echo '</div>';
                           }
                        }
                     }
                     //if there are no users
                     if (count($frontendUsers) == 0 && count($backendUsers) == 0) {
                        echo '<p class="empty">No users</p>';
                     }
                     if (count($frontendUsers) == 0 && $_POST['users'] == 'frontend') {
                        echo '<p class="empty">No frontend users</p>';
                     }
                     if (count($backendUsers) == 0 && $_POST['users'] == 'backend') {
                        echo '<p class="empty">No backend users</p>';
                     }


                     ?>
            </div>
         </form>
      </section>
   </section>
   <script src="js/admin_script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>