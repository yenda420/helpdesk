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
         <a href="home.php" class="logo">Algaeland</a>

         <nav class="navbar">
            <a href=".php">My tickets</a>
            <a href=".php">Send a ticket</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
         <p>Jméno : <span><?php echo $_SESSION['user_name']; ?></span></p>
         <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div><a href="register.php">register</a></div>
         </div>
      </div>
   </div>

</header>