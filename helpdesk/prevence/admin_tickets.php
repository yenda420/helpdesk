<?php
session_start();

require("config.php");
require("functions.php");

$users = returnAllUsers($conn);

if (isset($_POST["users"])) {
    $tickets = empty($_POST["users"]) ? returtnAllTickets($conn) : returnTicketsForSelectedUser($conn, $_POST);
} else {
    $tickets = returtnAllTickets($conn);
}

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
            <div class="inputBox">
                <select name="users" required>
                    <option selected value="">--- Choose a user ---</option>
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
            <input type="submit" value="Show tickets" class="btn" name="show_tickets">
            </div>

        </form>

        <?php if (empty($_POST["users"])) { 
                
                if (numberOfTickets($conn) != 0) {
        ?>
                    <div class="box-container">
                        <?php
                        foreach ($tickets as $ticket) {
                            $user = returnUserForSelectedTicket($conn, $ticket["ticketId"]);
                            ?>
                            <div class="box">
                                <p>ID: <span>
                                        <?= $ticket["ticketId"] ?>
                                    </span></p>
                                <p>Title: <span>
                                        <?= $ticket["title"] ?>
                                    </span></p>
                                <p>Users name: <span>
                                        <?= $user["userName"] ?>
                                    </span></p>
                                <p>Users surname: <span>
                                        <?= $user["userSurname"] ?>
                                    </span></p>
                                <p>Users email: <span>
                                        <?= $user["email"] ?>
                                    </span></p>
                                <p>Ticket type: <span>
                                        <?= $ticket["ticketType"] ?>
                                    </span></p>
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
            <?php } else { ?>
                    <h1 class="title">
                        No ticket instances in database
                    </h1>
            <?php } ?>
        <?php } else {
            if (isset($_POST["users"]) && $_POST["users"] != 0) {
                $user = returnUser($conn, $_POST["users"]);

                if (numberOfTicketsForUser($conn, $_POST) != 0) {
                    ?>
                    <h1 class="title">Tickets for
                        <?= $user["userName"] ?>
                        <?= $user["userSurname"] ?>
                    </h1>
                    <div class="box-container">
                    <?php foreach ($tickets as $ticket) { ?>
                        
                    <div class="box">
                        <div class="breaking"><p>ID: <span>
                                <?= $ticket["ticketId"] ?>
                            </span></p></div>
                        <p>Title: <span>
                                <?= $ticket["title"] ?>
                            </span></p>
                        <p>Ticket type: <span>
                                <?= $ticket["ticketType"] ?>
                            </span></p>
                        <div class="breaking"><p>Description: <span>
                                <?= $ticket["ticketDesc"] ?>
                            </span></p></div>
                    </div>

                    <?php }
                } else { ?>
                    </div>
                    <h1 class="title">
                        <?= $user["userName"] ?>
                        <?= $user["userSurname"] ?> currently has no tickets
                    </h1>
                <?php }
            }
        } ?>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>