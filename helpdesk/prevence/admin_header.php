<?php
if(isset($message)){
   error_reporting(E_ALL ^ E_WARNING);
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
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
?>

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Admin<span>Space</span></a>

      <nav class="navbar">
            <a href="admin_page.php">Requests</a>
            <a href="users.php">Users</a>
            <a href="admin_tickets.php">Tickets</a>
            <a href="create_admin.php">Create an Admin</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
   
         <p>Name: <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>Email: <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <p>Department: <span><?php echo $_SESSION['department']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
      </div>

   </div>

</header>