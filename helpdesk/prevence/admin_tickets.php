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

if (is_array($_SESSION['department'])) {


   foreach ($_SESSION['department'] as $departmentArray) {
      $depNames[] = $departmentArray;
   }
}
?>

<header class="header">
   <div class="flex">
      <a href="admin_page.php" class="logo">
         <img src="img/techbase_logo.png" alt="logo">
         <?php if ($depNames[0] == 'Super-admin') {
            echo '<p><span>Super</span>Admin</p>';
         } else {
            echo '<p><span>Admin<span></p>';
         } ?>
      </a>

      <nav class="navbar" id="navbar-responsive">
         <a href="admin_page.php" class="active">Requests</a>

         <div class="dropdown">
            <button class="dropbtn">Users
               <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
               <a href="users.php">See all users</a>
               <a href="create_admin.php">Create backend user</a>
            </div>
         </div>

         <div class="dropdown">
            <button class="dropbtn">Tickets
               <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
               <a href="admin_tickets.php">See all tickets</a>
               <a href="#">See all ticket types</a>
               <a href="create_tickets.php">Create ticket types</a>
            </div>
         </div>

         <script>
            function myFunction() {
               var nav = document.getElementById("navbar-responsive");
               if (nav.className === "navbar") {
                  nav.className += " responsive";
               } else {
                  nav.className = "navbar";
               }
            }
         </script>

         <?php
         if ($depNames[0] == 'Super-admin') {
            echo '
               <a href="create_admin.php">Add Backend User</a>
               <a href="create_department.php">Add Department</a>
               <a href="create_tickets.php">Add Ticket</a>
            ';
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
         if ($depNames[0] != 'Super-admin') {
            echo "<p>Departments: <span>";
            echo implode(", ", $depNames);
            echo "</span></p>";
         } else {
            echo "<p>";
            echo $depNames[0];
            echo "</p>";
         }
         ?>
         <a href="logout.php" class="delete-btn">logout</a>
      </div>
   </div>
</header>