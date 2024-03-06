<?php
if (isset($message)) {
   error_reporting(E_ALL ^ E_WARNING);
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         <script>
            setTimeout(function() {
               document.querySelector(".message").style.opacity = "0";
               document.querySelector(".message").style.transition = "all 0.5s";
               setTimeout(function() {
                  document.querySelector(".message").remove();
               }, 500);
            }, 3500);
         </script>
      </div>
      ';
   }
}
$departmentNames = array();
foreach ($_SESSION['department'] as $departmentArray) {
   $departmentNames[] = $departmentArray['departmentName'];
}
?>

<header class="header">

   <div class="flex">

     
         <a href="admin_page.php" class="logo">
            <img src="img/techbase_logo.png" alt="logo">
            <?php if ($departmentNames[0] == 'Super-admin') {
               echo '<p><span>Super</span>Admin</p>';
            } else {
               echo '<p><span>Admin<span></p>';
            } ?>
         </a>
    

      <nav class="navbar">
         <a href="admin_page.php">Requests</a>
         <a href="users.php">Users</a>
         <a href="admin_tickets.php">Tickets</a>
         <?php
         if ($departmentNames[0] == 'Super-admin') {
            echo '<a href="create_admin.php">Add Backend User</a>
               <a href="create_admin.php">Add Department</a>
               <a href="create_admin.php">Add Ticket</a>';
         }
         ?>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">


         <p>Name: <span>
               <?php echo $_SESSION['admin_name']; ?>
            </span></p>
         <p>Email: <span>
               <?php echo $_SESSION['admin_email']; ?>
            </span></p>
         <?php
         if($departmentNames[0] != 'Super-admin'){
            echo "<p>Departments: <span>";
            echo implode(', ', $departmentNames);
            echo "</span></p>";
         }
         else{
            echo "<p>";
            echo $departmentNames[0];
            echo "</p>";
         }
         ?>
         <a href="logout.php" class="delete-btn">logout</a>
      </div>

   </div>

</header>