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
            <a href=".php">My tickets</a>
            <a href=".php">Send a ticket</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
         </div>

         <div class="user-box">
         <?php
            $user_email =  $_SESSION['user_email'];
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
               ?>
               <div class="box">
               <?php 
               if($fetch_users['email'] == $user_email)
               {
                  echo "<p> ID: <span>".$fetch_users['userId']."</span> </p>";
               echo "<p> Name: <span>".$fetch_users['userName']."</span> </p>";
               echo "<p> Surname: <span>".$fetch_users['userSurname']."</span> </p>";
               echo "<p> Email: <span>".$fetch_users['email']."</span> </p>";
               echo "<p> Type: <span>".$fetch_users['userType']."</span> </p>";
               }
            }
            ;
            ?>
            <a href="logout.php" class="delete-btn">logout</a>
            <div><a href="register.php">register</a></div>
         </div>
      </div>
   </div>

</header>