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
            }, 8000);
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
         <img src="../img/techbase_logo.png" alt="logo">
      </a>

      <nav class="navbar iconsParent" id="navbar-responsive">
         <?php
            if ($depNames[0] == 'Super-admin') {
               echo '
                  <a href="admin_page.php" class="active">Requests</a>

                  <div class="dropdown iconChild">
                     <button class="dropbtn" style="width:75px">Users
                        <i class="fa fa-caret-down"></i>
                     </button>
                     <div class="dropdown-content">
                        <a href="users.php">See all users</a>
                        <a href="create_admin.php">Create backend user</a>
                     </div>
                  </div>

                  <div class="dropdown iconChild">
                     <button class="dropbtn dropbtnWidth">Departments
                        <i class="fa fa-caret-down"></i>
                     </button>
                     <div class="dropdown-content">
                        <a href="departments.php">See all departments</a>
                        <a href="create_department.php">Create department</a>
                     </div>
                  </div>
         
                  <div class="dropdown iconChild">
                     <button class="dropbtn" style="width:85px">Tickets
                        <i class="fa fa-caret-down"></i>
                     </button>
                     <div class="dropdown-content dropdown-contentRight">
                        <a href="admin_tickets.php">See all tickets</a>
                        <a href="tck_types.php">See all ticket types</a>
                        <a href="create_tickets.php">Create ticket types</a>
                     </div>
                  </div>
               ';
            } else {
               echo '
                  <a href="admin_page.php" class="active">Requests</a>
                  <a href="admin_tickets.php">Tickets</a>
                  <a href="users.php" class="active">Users</a>
               ';
            }
         ?>

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
      </nav>


   <form method="POST" action="searchbar.php" class="searchbar" style="margin-right: 2rem;"> 
      <div class="iconsParent">
         <div style="flex:1;margin-right:1rem;">
           <input class="search" placeholder="Search" type="text" name="keyword" />
         </div>
         <div style="flex:1; width:fit-content;">
            <button style="margin-bottom:10px" class="btn" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
         </div>
      </div>
    </form>

      <div class="icons iconsParent">
         <div id="menu-btn" class="fas fa-bars iconsChild"></div>
         <div id="user-btn" class="fas fa-user iconsChild"></div>
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