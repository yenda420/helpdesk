<?php
if (isset($message)) {
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
?>

<header class="header">

   <div class="header-2">
      <div class="flex">
      <div class="logo">
         <a href="home.php">
            <img src="img/techbase_logo.png" alt="logo">
         </a>
      </div>

         <nav class="navbar">
            <a href="user_messages.php">My messages</a>
            <a href="home.php">My tickets</a>
            <a href="user_send.php">Send a ticket</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
         </div>

         <div class="user-box">
            <p>Name : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Surname: <span><?php echo $_SESSION['user_surname']; ?></span></p>
            <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>