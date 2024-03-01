<?php
session_start();

require("config.php");
require("functions.php");

$users = returnAllUsers($conn);

$fullQuery = "SELECT * FROM tickets WHERE 1 = 1";

if (!empty($_POST['users'])) { 
    $user = returnUser($conn, $_POST['users']);
    
    if (isset($user['userId'])) {
        $userId = $user['userId'];
        $fullQuery .= " AND userId = $userId";
    }
}

if (!empty($_POST['types'])) {
    $fullQuery .= " AND ticketType = '{$_POST['types']}'";
}

if (!empty($_POST["date"])) {
    $fullQuery .= " AND ticketDate = '{$_POST['date']}'";
}

$fullQueryResult = mysqli_query($conn, $fullQuery);
$tickets = mysqli_fetch_all($fullQueryResult, MYSQLI_ASSOC);

if(isset($_POST['delete_ticket'])) {
    $ticket_id = $_POST['ticket_id'];
    // Perform the deletion query
    $delete_query = mysqli_query($conn, "DELETE FROM `tickets` WHERE ticketId = '$ticket_id'");
    if($delete_query) {
        // Refresh the page after deletion
        header("Refresh:0");   
    } else {
        echo "Error deleting request.";
    }
 }

require("admin_header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminSpace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

    <section class="dashboard">
        <section class="tickets">
            <h1 class="title">Tickets</h1>
        </section>

        <form method="post">
            <div class="flex">
            <div class="box-container">
            <div class="inputBox">
                <select name="users">
                    <option value="" selected>--- Choose a user ---</option>
                    <?php foreach ($users as $user) { 
                            if ($user["userType"] != 'backend') {
                    ?>
                        <option value="<?= $user["userId"] ?>">
                            <?= $user["userName"] ?>
                            <?= $user["userSurname"] ?>
                            <?= $user["email"] ?>
                        </option>
                    <?php 
                            }
                        }
                    ?>
                </select>
                </div>
            <div class="inputBox">
                <select name="types">
                    <option value="" selected>--- Choose a type ---</option>
                    <?php
                        $type_query = mysqli_query($conn, "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'tickets' AND COLUMN_NAME = 'ticketType'");
                        $type_row = mysqli_fetch_assoc($type_query);
                        $types = explode(",", str_replace("'", "", substr($type_row['COLUMN_TYPE'], 5, (strlen($type_row['COLUMN_TYPE'])-6))));

                        foreach($types as $type) {
                            echo "<option value='$type'>$type</option>";
                         }
                    ?>
                </select>
            </div>
            <div class="inputBox">
                <input type="date" name="date">
            </div>
            </div>
            <input type="submit" value="Show tickets" class="btn" name="show_tickets">

        </form>

        <?php if (empty($_POST["users"]) && empty($_POST["types"]) && empty($_POST["date"])) {         //List of all tickets:
                if (numberOfTickets($conn) != 0) {
        ?>
                    <div class="box-container">
                        <?php
                        foreach ($tickets as $ticket) {
                            $user = returnUserForSelectedTicket($conn, $ticket["ticketId"]);
                            ?>
                            <div class="box">
                                    <div class="breaking"><p>ID: <span>
                                        <?= $ticket["ticketId"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Title: <span>
                                        <?= $ticket["title"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Users name: <span>
                                        <?= $user["userName"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Users surname: <span>
                                        <?= $user["userSurname"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Users email: <span>
                                        <?= $user["email"] ?>
                                    </span></p></div>
                                    <div class="breaking"> <p>Ticket type: <span>
                                        <?= $ticket["ticketType"] ?>
                                    </span></p></div>
                                    <div class="desc"><p>Description: <span>
                                        <?= $ticket["ticketDesc"] ?>
                                    </span></p></div>
                                    <form method="POST">
                                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticketId']; ?>"> <br>
                                        <button type="submit" name="delete_ticket" class="delete-btn">Delete</button>
                                    </form>
                            </div>
                        <?php } ?>
                    </div>
            <?php } else {        //No ticket instances database ?>
                    <div class="box-container notickets">
                        <p class="empty">
                            No ticket instances database
                        </p>
                    </div>
            <?php } ?>
        <?php } else { 
                    if (mysqli_num_rows($fullQueryResult) > 0) { ?>
                        <div class="box-container">     <?php //List of selected tickets:
                            foreach ($tickets as $ticket) { ?>
                                <div class="box">
                                    <div class="breaking"><p>ID: <span>
                                        <?= $ticket["ticketId"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Title: <span>
                                        <?= $ticket["title"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Ticket type: <span>
                                        <?= $ticket["ticketType"] ?>
                                    </span></p></div>
                                    <div class="breaking"><p>Description: <span>
                                        <?= $ticket["ticketDesc"] ?>
                                    </span></p></div>
                                    <form method="POST">
                                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticketId']; ?>"> <br>
                                        <button type="submit" name="delete_ticket" class="delete-btn">Delete</button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="box-container notickets">
                            <p class="empty">
                                No tickets found
                            </p>
                        </div>
                    <?php }
                } ?>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>