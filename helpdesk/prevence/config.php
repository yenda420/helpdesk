<?php

$conn = mysqli_connect("172.31.1.103", "helpdesk", "Helpdesk.123", "helpdesk") or die("Connection failed");
$conn -> set_charset("utf8");

//Extract from database:

/*$select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
if(mysqli_num_rows($select_users) > 0){
    while($fetch_users = mysqli_fetch_assoc($select_users)){
        echo $fetch_users['userName'] . " " . $fetch_users['userSurname'] . " " . $fetch_users['userPasswd'] . " " . $fetch_users['userType'] . "<br>";
    }
}else{
    echo 'Zatím nic!';
}*/

?>