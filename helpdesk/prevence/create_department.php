<?php

include 'config.php';
require('functions.php');

session_start();

if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:index.php');
}

if (isset($_POST['submit'])) {
   $name = mysqli_real_escape_string($conn, $_POST['createDepartmentName']);
   $query = "INSERT INTO `departments` (departmentName) VALUES ('$name');";
   $result = mysqli_query($conn, $query);
   if($result){
      $message[] = 'Department was successfuly created.';
   } else {
      $message[] = 'Query failed.';
   }
 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add a department</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
   <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
      integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="dashboard">
      <section class="tickets">
         <h1 class="title">Add a department</h1>
      </section>

      <section class="checkout">

         <form method="post" id="adminForm">
            <div class="flex">
               <div class="inputBox">
                  <input type="text" name="createDepartmentName" placeholder="Department name" required />
               </div>
            </div>
            <input type="submit" value="Add" class="btn" name="submit">
         </form>

      </section>
   </section>
   <script src="js/admin_script.js"></script>
   <?php include 'footer.php'; ?>
</body>

</html>