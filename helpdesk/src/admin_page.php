<?php

include 'config.php';

session_start();

if (isset($_SESSION['admin_id'])) {
   $admin_id = $_SESSION['admin_id'];
} else {
   header('location:index.php');
}
if (isset($_POST['delete_request'])) {
   $request_id = $_POST['request_id'];
   // Perform the deletion query
   $stmt = $conn->prepare("DELETE FROM `requests` WHERE requestId = ?");
   $stmt->bind_param("i", $request_id);
   $stmt->execute();
   if ($stmt->affected_rows > 0) {
      $_SESSION['message'] = "Request deleted successfully";
   } else {
      $_SESSION['message'] = "Error deleting request";
   }
   $stmt->close();
   header("Location: " . $_SERVER['PHP_SELF']);
   exit;
}
//if you press accept, the user is created and inputted into the users table
if (isset($_POST['accept_request'])) {
   $request_id = $_POST['request_id'];

   $stmt = $conn->prepare("SELECT * FROM `requests` WHERE requestId = ?");
   $stmt->bind_param("i", $request_id);
   $stmt->execute();
   $result = $stmt->get_result();
   $fetch_request = $result->fetch_assoc();

   $req_name = $fetch_request['reqName'];
   $req_surname = $fetch_request['reqSurname'];
   $req_password = $fetch_request['reqPasswd'];
   $req_email = $fetch_request['reqEmail'];

   $stmt = $conn->prepare("INSERT INTO `users` (userName, userSurname, userEmail, userPasswd) VALUES (?, ?, ?, ?)");
   $stmt->bind_param("ssss", $req_name, $req_surname, $req_email, $req_password);
   $insert_user = $stmt->execute();

   if ($insert_user) {
      $stmt = $conn->prepare("DELETE FROM `requests` WHERE requestId = ?");
      $stmt->bind_param("i", $request_id);
      $delete_query = $stmt->execute();
      if ($delete_query) {
         $_SESSION['message'] = "User added successfully";
      } else {
         $_SESSION['message'] = "Error deleting request";
      }
   } else {
      $_SESSION['message'] = "Error adding user";
   }
   $stmt->close();
   header("Location: " . $_SERVER['PHP_SELF']);
   exit;
}
?>
<?php
 if (isset($_SESSION['message'])) {
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
   <title>Requests</title>


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
            $stmt = $conn->prepare("SELECT * FROM `requests`");
            $stmt->execute();
            $select_requests = $stmt->get_result();
            while ($fetch_requests = $select_requests->fetch_assoc()) {
               ?>
               <div class="box">
                  <div class="breaking">
                     <p> ID: <span>
                           <?php echo $fetch_requests['requestId']; ?>
                        </span> </p>
                  </div>
                  <div class="breaking">
                     <p> Name: <span>
                           <?php echo $fetch_requests['reqName']; ?>
                        </span> </p>
                  </div>
                  <div class="breaking">
                     <p> Surname: <span>
                           <?php echo $fetch_requests['reqSurname']; ?>
                        </span> </p>
                  </div>
                  <div class="breaking">
                     <p> Email: <span>
                           <?php echo $fetch_requests['reqEmail']; ?>
                        </span> </p>
                  </div>
                  <!-- Add the delete button -->
                  <form method="POST">
                     <input type="hidden" name="request_id" value="<?php echo $fetch_requests['requestId']; ?>">
                     <button type="submit" name="delete_request" class="delete-btn"
                        onclick="return confirmDeletingRequest()">Delete</button>
                     <button type="submit" name="accept_request" class="btn"
                        onclick="return confirmAcceptingRequest()">Accept</button>
                  </form>
               </div>
               <?php
            }
            ;
            if (mysqli_num_rows($select_requests) == 0) {
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