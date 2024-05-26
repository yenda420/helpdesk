<?php

require 'config.php';
require('functions.php');

session_start();
if (isset($_SESSION['admin_id'])) {
   if ($_SESSION['department'][0]!='Super-admin') {
      header('location:admin_page.php');
   }
} else {
   header('location:index.php');
}

if (isset($_POST['submit'])) {
   $name = $_POST['createDepartmentName'];
   $stmt = $conn->prepare("SELECT departmentName FROM departments WHERE departmentName=?");
   $stmt->bind_param('s', $name);
   $stmt->execute();
   $result = $stmt->get_result();
   $department = $result->fetch_assoc();
   if ($department) {
       $message[] = 'Department already exists.';
   } else {
       $stmt = $conn->prepare("INSERT INTO `departments` (departmentName) VALUES (?)");
       $stmt->bind_param('s', $name);
       if ($stmt->execute()) {
           $_SESSION['message'] = 'Department was successfuly created.';
       } else {
           $_SESSION['message'] = 'Query failed.';
       }
   }
   $stmt->close();
   header("Location: " . $_SERVER['PHP_SELF']);
   exit;
}
?>
<?php
if(isset($_SESSION['message'])){
   $message[] = $_SESSION['message'];
   unset($_SESSION['message']);
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
                  <input type="text" class="textInput" name="createDepartmentName" placeholder="Department name" required />
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