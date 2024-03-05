<?php

   include 'config.php';
   require('functions.php');

   session_start(); 

   if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
 } else {
    header('location:index.php'); 
 }

   if (isset($_POST['submit'])) {

      $name = mysqli_real_escape_string($conn, $_POST['createAdminName']);
      $surname = mysqli_real_escape_string($conn, $_POST['createAdminSurname']);
      $email = mysqli_real_escape_string($conn, $_POST['createAdminEmail']);
      $pass = mysqli_real_escape_string($conn, hash('sha256', $_POST['createAdminPasswd']));
      $cpass = mysqli_real_escape_string($conn, hash('sha256', $_POST['createAdminPasswdConf']));
      $depmnt = mysqli_real_escape_string($conn, $_POST['type']);

      $sqlInsert = "INSERT INTO `admins` (adminName, adminSurname, adminEmail, adminPasswd, departmentId) 
                     VALUES ('$name', '$surname', '$email','$cpass', '$depmnt')";

      if ($pass == $cpass) {
         if (strlen($_POST['createAdminPasswd']) >= 8) {
            if (preg_match('/[A-Z]/', $_POST['createAdminPasswd'])) {
               if (preg_match('/\d/', $_POST['createAdminPasswd'])) {
                  if (preg_match("/[^a-zA-Z0-9]/", $_POST['createAdminPasswd'])) {
                     if (!emailInDatabase($conn, $email)) {
                        if (mysqli_query($conn, $sqlInsert)) {
                           $message[] = 'Account was succesfully created.';
                        } else {
                           $message[] = 'Query failed.';
                        }
                     } else {
                        $message[] = 'Account with this email already exists.';
                     }
                  } else {
                     $message[] = 'Password needs at least 1 special character.';
                  }
               } else {
                  $message[] = 'Password needs at least 1 number.';
               }
            } else {
               $message[] = 'Password needs at least 1 upper case character.';
            }
         } else {
            $message[] = 'Password needs at least 8 characters.';
         }
      } else {
         $message[] = 'Passwords don\'t match!';
      }
   }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

<?php include 'admin_header.php'; ?>

    <section class="dashboard">
        <section class="tickets">
            <h1 class="title">Create an Admin</h1>
        </section>

        <section class="checkout">

            <form method="post" action="">
                <div class="flex">
                    <div class="inputBox">
                        <input type="text" name="createAdminName" placeholder="Name" required />
                    </div>
                    <div class="inputBox">
                        <input type="text" name="createAdminSurname" placeholder="Surname" required />
                    </div>
                    <div class="inputBox">
                        <input type="email" name="createAdminEmail" placeholder="Email" required />
                    </div>
                    <div class="inputBox">
                        <select name="type" required>
                           <option value="" selected>--- Choose a department ---</option>
                            <?php
                                $departments = returnDepartments($conn);
                                 foreach ($departments as $department) {
                                       echo "<option value='{$department['departmentId']}'>{$department['departmentName']}</option>";
                                 }
                              ?>
                        </select>
                    </div>
                    <div class="inputBox">
                        <input type="password" name="createAdminPasswd" placeholder="Password" required />
                    </div>
                    <div class="inputBox">
                        <input type="password" name="createAdminPasswdConf" placeholder="Confirm password" required />
                    </div>
                </div>
                <input type="submit" value="Create" class="btn" name="submit">
            </form>

        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>