<?php
$user_name = $sessionManager->getUserName();
$user_surname = $sessionManager->getUserSurname();
$user_email = $sessionManager->getUserEmail();
$message = $sessionManager->getMessage();

if ($message) {
   $messageManager->displayMessages([$message]);
}
?>

<header class="header">

   <div class="header-2">
      <div class="flex">
      <div class="logo">
         <a href="home.php">
            <img src="../img/logo.png" alt="logo">
         </a>
      </div>

         <nav class="navbar">
            <a href="home.php">My tickets</a>
            <a href="user_messages.php">New messages</a>
            <a href="user_send.php">Send a ticket</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
         </div>

         <div class="user-box">
            <p>Name : <span><?php echo $user_name?></span></p>
            <p>Surname: <span><?php echo $user_surname?></span></p>
            <p>Email : <span><?php echo $user_email ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>