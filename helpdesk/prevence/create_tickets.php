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
   $name = mysqli_real_escape_string($conn, $_POST['ticketName']);
   //if the department name is already in the database, don't add it again
   $sql = "SELECT ticketTypeName FROM ticket_types WHERE ticketTypeName='$name'";
   $result = mysqli_query($conn, $sql);
   $ticket = mysqli_fetch_assoc($result);
   if ($ticket) {
      $message[] = 'Ticket type already exists.';
   } else {
      $departmentName = $_POST['departmentName'];
      if(departmentExists($conn,$departmentName)) {
         $department = returnDepartmentId($conn, $departmentName);
         $departmentId = $department['departmentId'];
         $query = "INSERT INTO `ticket_types` (ticketTypeName, departmentId) VALUES ('$name', '$departmentId');";
         $result2 = mysqli_query($conn, $query);
         if ($result2) {
            $message[] = 'Ticket type was successfuly created.';
         } else {
            $message[] = 'Query failed.';
         }
      }
      else {
         $message[] = 'Department does not exist.';
      }
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
         <h1 class="title">Add a new ticket type</h1>
      </section>

      <section class="checkout">

         <form method="post" id="adminForm">
            <div class="flex">
               <div class="inputBox">
                  <input type="text" class="textInput" name="ticketName" placeholder="Ticket type name" required />
               </div>
               <div class="inputBox">
                  <input type="text" class="textInput" name="departmentName" placeholder="Department responsible" required />
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