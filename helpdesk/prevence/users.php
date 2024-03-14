<?php

include 'config.php';
require('functions.php');
error_reporting(E_ALL ^ E_WARNING);

session_start();
if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:index.php');
}
if (isset($_POST['delete_user'])) {
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
}
if (isset($_POST["change_dept"])) {
   if (isset($_POST['admin_id']) && isset($_POST['department'])) {
      $admin_id = $_POST['admin_id'];
      $department_names = $_POST['department'];
      $message[] = changeAdminDepartment($conn, $admin_id, $department_names);
    
   }
}
if (isset($_POST['filter'])) {
   if ($_POST['users'] == 'frontend') {
      $backendUsers = null;
      $frontendUsers = returnAllFrontendUsers($conn);
   } else if ($_POST['users'] == 'backend') {
      $frontendUsers = null;
      $backendUsers = returnAllBackendUsers($conn);
   } else {
      $frontendUsers = returnAllFrontendUsers($conn);
      $backendUsers = returnAllBackendUsers($conn);
   }
} else {
   $frontendUsers = returnAllFrontendUsers($conn);
   $backendUsers = returnAllBackendUsers($conn);
}
if (!isset($_POST['userSearch'])) {
   $_POST['userSearch'] = null;
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
   <link rel="stylesheet" href="css/searchbar.css">

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
               <div class="filters">
                  <div class="inputBox">
                     <select name="users" class="" required>
                        <option value="all">--- Choose a user type ---</option>
                        <option <?php if ($_POST['users'] == 'frontend')
                           echo 'selected' ?> value="frontend">Frontend
                           </option>
                           <option <?php if ($_POST['users'] == 'backend')
                           echo 'selected' ?> value="backend">Backend
                           </option>
                        </select>
                     </div>
                     <div class="inputBox" align="center">
                        <select name="userSearch" id="userSearch">
                           <option style="font-size: 1.8rem;" value="">Select a user or type to search</option>

                        <?php foreach ($frontendUsers as $user) {
                           ?>
                           <option style="font-size: 1.8rem;" <?php if ($_POST['userSearch'] == $user['userId'])
                              echo "selected"; ?> value="<?= $user['userId'] ?>">
                              <?= $user['userEmail'] ?>
                           </option>
                        <?php }

                        foreach ($backendUsers as $user) {
                           ?>
                           <option style="font-size: 1.8rem;" <?php if ($_POST['userSearch'] == $user['adminId'])
                              echo "selected"; ?> value="<?= $user['adminId'] ?>">
                              <?= $user['adminEmail'] ?>
                           </option>
                        <?php } ?>


                     </select>
                     <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.8.0/slimselect.min.js"
                        integrity="sha512-mG8eLOuzKowvifd2czChe3LabGrcIU8naD1b9FUVe4+gzvtyzSy+5AafrHR57rHB+msrHlWsFaEYtumxkC90rg=="
                        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                     <script>
                        new SlimSelect({
                           select: "#userSearch"
                        });
                     </script>
                  </div>

               </div>
               <div class="inputBox">
                  <button type="submit" name="filter" class="btn">Show users</button>
               </div><br>

               <div class="box-container">
                  <?php
                  if (empty($_POST["userSearch"])) {

                     if (isset($frontendUsers)) {
                        foreach ($frontendUsers as $user) {
                           echo '<div class="box">
                                       <div class="breaking"><p> ID: <span>' . $user['userId'] . '</span> </p></div>
                                       <div class="breaking"><p> Name: <span>' . $user['userName'] . '</span> </p></div>
                                       <div class="breaking"><p> Surname: <span>' . $user['userSurname'] . '</span> </p></div>
                                       <div class="breaking"><p> Email: <span>' . $user['userEmail'] . '</span> </p></div>';
                           if ($_SESSION['department'][0] == 'Super-admin') {
                              echo '<input type="hidden" name="user_id" value="' . $user['userId'] . '"><br>
                                       <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingUser()">Delete</button>
                                    </div>';
                           } else {
                              echo '</div>';
                           }
                        }
                     }


                     if (isset($backendUsers)) {
                        foreach ($backendUsers as $user) {
                           echo '<form method="post">';
                           echo '<div class="box">
                                      <div class="breaking"><p> ID: <span>' . $user['adminId'] . '</span> </p></div>
                                      <div class="breaking"><p> Name: <span>' . $user['adminName'] . '</span> </p></div>
                                      <div class="breaking"><p> Surname: <span>' . $user['adminSurname'] . '</span> </p></div>
                                      <div class="breaking"><p> Email: <span>' . $user['adminEmail'] . '</span> </p></div>';
                           echo '<div class="breaking" style="overflow:hidden;"><p> Department : <span align="center" style="justify-content: center;">';
                           $select_departments = mysqli_query($conn, "SELECT * FROM `department_lists` where adminId = '{$user['adminId']}'") or die('query failed');
                           $departmentNames = [];
                           if (mysqli_num_rows($select_departments) > 0) {
                              while ($fetch_departments = mysqli_fetch_assoc($select_departments)) {
                                 $departmentNames[] = returnDepartmentName($conn, $fetch_departments['departmentId']);
                              }
                           }
                           echo "<textarea name='department' rows='" . count($departmentNames) . "' cols='21'>";
                           echo implode("\n", $departmentNames);
                           echo '</textarea>';
                           echo '</span></p></div>';
                           if ($_SESSION['department'][0] == 'Super-admin') {
                              echo '<input type="hidden" name="admin_id" value="' . $user['adminId'] . '"> <br>
                                          <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingAdmin()">Delete</button>
                                          <button type="submit" name="change_dept" class="btn" onclick="return confirmChangingDepartments()">Change</button>
                                      </div>';
                           } else {
                              echo '</div>';
                           }
                           echo '</form>';
                        }
                     }



                  } else {
                     if (isset($_POST['userSearch'])) {
                        $oneUser[] = returnUser($conn, $_POST['userSearch']);
                        // echo count($oneUser, 1);
                        if (count($oneUser, 1) == 1 && isset($backendUsers)) {
                           //echo "prazdne";
                           $oneUser[] = returnAdmin($conn, $_POST['userSearch']);
                           //var_dump($oneUser[1]);
                           //echo count($oneUser, 1);
                  
                           echo '<div class="box">
                           <div class="breaking"><p> ID: <span>' . $oneUser[1]['adminId'] . '</span> </p></div>
                           <div class="breaking"><p> Name: <span>' . $oneUser[1]['adminName'] . '</span> </p></div>
                           <div class="breaking"><p> Surname: <span>' . $oneUser[1]['adminSurname'] . '</span> </p></div>
                           <div class="breaking"><p> Email: <span>' . $oneUser[1]['adminEmail'] . '</span> </p></div>';
                           echo '<div class="breaking"><p> Department: <span>';
                           $select_departments = mysqli_query($conn, "SELECT * FROM `department_lists` where adminId = '{$oneUser[1]['adminId']}'") or die('query failed');
                           $departmentNames = [];
                           if (mysqli_num_rows($select_departments) > 0) {
                              while ($fetch_departments = mysqli_fetch_assoc($select_departments)) {
                                 $departmentNames[] = returnDepartmentName($conn, $fetch_departments['departmentId']);
                              }
                           }
                           echo '<input style="width:fit-content; max-width: 20rem" type="text" name="department" value="' . implode(', ', $departmentNames) . '">';
                           echo '</span> </p></div>';
                           if ($_SESSION['department'][0] == 'Super-admin') {
                              echo '<input type="hidden" name="admin_id" value="' . $oneUser[1]['adminId'] . '"> <br>
                                       <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingAdmin()">Delete</button><button type="submit" name="change_dept" class="btn" onclick="return confirmChangingDepartments()">Change</button>
                                 </div>';
                           } else {
                              echo '</div>';
                           }

                        } else {
                           //var_dump($oneUser[0]['userId']);
                           if (!isset($frontendUsers))
                              echo '<p class="empty">No Frontend Users</p>';
                        }
                        if (count($oneUser, 1) != 1 && isset($frontendUsers) && $oneUser[0]['userId'] != null) {
                           echo '<div class="box">
                           <div class="breaking"><p> ID: <span>' . $oneUser[0]['userId'] . '</span> </p></div>
                           <div class="breaking"><p> Name: <span>' . $oneUser[0]['userName'] . '</span> </p></div>
                           <div class="breaking"><p> Surname: <span>' . $oneUser[0]['userSurname'] . '</span> </p></div>
                           <div class="breaking"><p> Email: <span>' . $oneUser[0]['userEmail'] . '</span> </p></div>';
                           if ($_SESSION['department'][0] == 'Super-admin') {
                              echo '<input type="hidden" name="user_id" value="' . $oneUser[0]['userId'] . '"><br>
                           <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingUser()">Delete</button>
                        </div>';
                           } else {
                              echo '</div>';
                           }
                           echo '</form>';
                        } else {
                           if (!isset($backendUsers))
                              echo '<p class="empty">No Backend Users</p>';
                        }
                     }
                  }

                  //if there are no users
                  if (isset($backendUsers) && isset($frontendUsers)) {
                     if (count($frontendUsers) == 0 && count($backendUsers) == 0) {
                        echo '<p class="empty">No users</p>';
                     }
                     if (count($frontendUsers) == 0 && $_POST['users'] == 'frontend') {
                        echo '<p class="empty">No frontend users</p>';
                     }
                     if (count($backendUsers) == 0 && $_POST['users'] == 'backend') {
                        echo '<p class="empty">No backend users</p>';
                     }
                  }



                  ?>
               </div>
            </div>
         </form>
      </section>
   </section>
   <script src="js/admin_script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>