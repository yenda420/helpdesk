<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">



   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">Techbase</a>

         <nav class="navbar">
            <a href="home.php">My tickets</a>
            <a href=".php">Send a ticket</a>
         </nav>

         <div class="icons">
            <a href="home.php">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div></a>
         </div>
      </div>
   </div>

</header>