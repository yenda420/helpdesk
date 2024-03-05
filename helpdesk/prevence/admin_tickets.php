<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    header('location:index.php');
}

require("config.php");
require("functions.php");

$users = returnAllFrontendUsers($conn);

$ticketTypes = returnTicketTypesForDepartmentName($conn, $_SESSION['department']);

$fullQuery = "
    SELECT DISTINCT tck.ticketId, tck.title, tck.ticketDesc, tck.ticketDate, tck.userId, tck.ticketTypeId 
    FROM tickets tck, ticket_types tps 
    WHERE 1 = 1
";

if (!empty($_POST['users'])) {
    $user = returnUser($conn, $_POST['users']);

    if (isset($user['userId'])) {
        $userId = $user['userId'];
        $fullQuery .= " AND tck.userId = $userId";
    }
}

if (!empty($_POST['types'])) {
    $fullQuery .= " AND tps.ticketTypeId = '{$_POST['types']}' AND tps.ticketTypeId = tck.ticketTypeId";
}

if (!empty($_POST["date"])) {
    $fullQuery .= " AND ticketDate = '{$_POST['date']}'";
}

$fullQueryResult = mysqli_query($conn, $fullQuery);
$tickets = mysqli_fetch_all($fullQueryResult, MYSQLI_ASSOC);

if (isset($_POST['delete_ticket'])) {
    $ticket_id = $_POST['ticket_id'];
    // Perform the deletion query
    $delete_query = mysqli_query($conn, "DELETE FROM `tickets` WHERE ticketId = '$ticket_id'");
    if ($delete_query) {
        // Refresh the page after deletion
        header("Refresh:0");
    } else {
        echo "Error deleting request.";
    }
}

if (!isset($_POST['users'])) {
    $_POST['users'] = null;
}

if (!isset($_POST['types'])) {
    $_POST['types'] = null;
}

require("admin_header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
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
                            <?php foreach ($users as $user) { ?>
                                <option <?php if ($_POST['users'] == $user['userId'])
                                    echo "selected" ?>
                                        value="<?= $user["userId"] ?>">
                                    <?= $user["userName"] ?>
                                    <?= $user["userSurname"] ?>
                                    <?= $user["userEmail"] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="inputBox">
                        <select name="types">
                            <option value="" selected>--- Choose a type ---</option>
                            <?php foreach ($ticketTypes as $ticketType) { ?>
                                <option <?php if ($_POST['types'] == $ticketType['ticketTypeId'])
                                    echo "selected" ?>
                                        value="<?= $ticketType["ticketTypeId"] ?>">
                                    <?= $ticketType["ticketTypeName"] ?>
                                <?php } ?>
                        </select>
                    </div>

                    <div class="inputBox">
                        <input type="date" id="date" name="date" value="<?php if (isset($_POST['date'])) {
                            echo $_POST['date'];
                        } ?>">
                    </div>

                </div>
                <input type="submit" value="Show tickets" class="btn" name="show_tickets">
            </div>
        </form>

        <?php
        if (empty($_POST["users"]) && empty($_POST["types"]) && empty($_POST["date"])) {         //List of all tickets:
            if (numberOfTickets($conn) != 0) { ?>
                <div class="box-container">
                    <?php
                    foreach ($tickets as $ticket) {
                        $user = returnUserForSelectedTicket($conn, $ticket["ticketId"]);
                        $ticketType = returnTicketTypeName($conn, $ticket['ticketTypeId'])['ticketTypeName']; ?>

                        <div class="box">
                            <div class="breaking">
                                <p>ID: <span>
                                        <?= $ticket["ticketId"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Title: <span>
                                        <?= $ticket["title"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Users name: <span>
                                        <?= $user["userName"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Users surname: <span>
                                        <?= $user["userSurname"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Users email: <span>
                                        <?= $user["userEmail"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Ticket type: <span>
                                        <?= $ticketType ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Description: <span>
                                        <?= $ticket["ticketDesc"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Ticket date: <span>
                                        <?= $ticket['ticketDate'] ?>
                                    </span></p>
                            </div>
                            <form method="POST" onsubmit="return confirmDeletingTicket();">
                                <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticketId']; ?>"> <br>
                                <button type="submit" name="delete_ticket" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { //No ticket instances database  ?>
                <div class="box-container notickets">
                    <p class="empty">
                        No ticket instances database
                    </p>
                </div>
            <?php } ?>
        <?php } else {
            if (mysqli_num_rows($fullQueryResult) > 0) { ?>
                <div class="box-container">
                    <?php foreach ($tickets as $ticket) { //List of selected tickets: 
                                    $ticketType = returnTicketTypeName($conn, $ticket['ticketTypeId'])['ticketTypeName']; ?>

                        <div class="box">
                            <div class="breaking">
                                <p>ID: <span>
                                        <?= $ticket["ticketId"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Title: <span>
                                        <?= $ticket["title"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Ticket type: <span>
                                        <?= $ticketType ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Description: <span>
                                        <?= $ticket["ticketDesc"] ?>
                                    </span></p>
                            </div>
                            <div class="breaking">
                                <p>Ticket date: <span>
                                        <?= $ticket['ticketDate'] ?>
                                    </span></p>
                            </div>
                            <form method="POST" onsubmit="return confirmDeletingTicket();">
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