<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
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
            <a href=".php">My tickets</a>
            <a href=".php">Send a ticket</a>
         </nav>

         <div class="user-box">
         <p>Jméno: <span><?php echo $_SESSION['user_name']; ?></span></p>
         <p>Email: <span><?php echo $_SESSION['user_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div><a href="register.php">register</a></div>
         </div>
      </div>
   </div>

</header>