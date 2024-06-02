<?php

require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/UserManager.php';
require 'classes/DepartmentManager.php';
require 'classes/TicketManager.php';
require 'classes/TicketTypeManager.php';
require 'classes/Utility.php';


$sessionManager = new SessionManager();
$messageManager = new MessageManager();

$sessionManager->startSession();

$admin_id = $sessionManager->getAdminId();
if (!$admin_id) {
    header('location:index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$userManager = new UserManager($db);
$departmentManager = new DepartmentManager($db);
$ticketManager = new TicketManager($db);
$ticketTypeManager = new TicketTypeManager($db);


if (isset($_POST['delete_ticket'])) {
    $ticket_id = $_POST['ticket_id'];
    
    // Prepare the deletion query for messages
    $stmt = $db->prepare("
        DELETE messages
        FROM messages
        JOIN conversation ON messages.conversationId = conversation.convoId
        JOIN tickets ON conversation.ticketId = tickets.ticketId
        WHERE tickets.ticketId = ?
    ");
    $stmt->bind_param("i", $ticket_id);
    $deleteMessages = $stmt->execute();
    
    if ($deleteMessages) {
        // Prepare the deletion query for conversations
        $stmt = $db->prepare("
            DELETE FROM conversation 
            WHERE ticketId = ?
        ");
        $stmt->bind_param("i", $ticket_id);
        $deleteConversations = $stmt->execute();
        
        if ($deleteConversations) {
            // Prepare the deletion query for tickets
            $stmt = $db->prepare("DELETE FROM tickets WHERE ticketId = ?");
            $stmt->bind_param("i", $ticket_id);
            $deleteTickets = $stmt->execute();
            
            if ($deleteTickets) {
                $sessionManager->setMessage("Ticket deleted successfully");
            } else {
                $sessionManager->setMessage("Error deleting ticket");
            }
        } else {
            $sessionManager->setMessage("Error deleting conversations");
        }
    } else {
        $sessionManager->setMessage("Error deleting messages");
    }
    
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST["usr_msg_send"])) {
    $msdId = $_POST["usr_msg"];
    $stmt = $db->prepare("INSERT INTO `conversation` (userId, adminId, ticketId) values (?, ?, ?)");
    $stmt->bind_param("iii", $_POST["user_Id"], $_POST["admin_Id"], $_POST["ticket_Id"]);
    $insert_covno_query = $stmt->execute();
    $stmt->close();
    
    $convoId = $db->insert_id; 
    $stmt = $db->prepare("INSERT INTO `messages` (msgContent, senderAdminId, conversationId) values (?, ?, ?)");
    $stmt->bind_param("sii", $_POST["usr_msg"], $_POST["admin_Id"], $convoId);
    $insert_msg_query = $stmt->execute();
    if($insert_msg_query && $insert_covno_query) {
        $sessionManager->setMessage("Message sent successfully");
    } else {
        $sessionManager->setMessage("Failed to send message");
    }
    $stmt->close();

    $stmt = $db->prepare("UPDATE `tickets` SET `status`='Pending',`resolver`=? WHERE ticketId=?");
    $stmt->bind_param("ii", $_POST["admin_Id"], $_POST["ticket_Id"]);
    $update_status_query = $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$users = $userManager->getAllUsers();

$departmentNames = array();

foreach ($_SESSION['department'] as $department) {
    $departmentName = $department;
    if (!in_array($departmentName, $departmentNames))
        array_push($departmentNames, $departmentName);
}

$ticketTypes = array();

foreach ($departmentNames as $departmentName) {
    $ticketTypesForDepartment = $departmentManager->returnTicketTypesForDepartmentName($departmentName);
    foreach ($ticketTypesForDepartment as $ticketType) {
        array_push($ticketTypes, $ticketType);
    }
}

$ticketStatusQuery = "
    SELECT COLUMN_TYPE 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'helpdesk' 
    AND TABLE_NAME = 'tickets'
    AND COLUMN_NAME = 'status';
";

$ticketStatusQueryResult = mysqli_query($db, $ticketStatusQuery);
$enumString = array_shift(mysqli_fetch_all($ticketStatusQueryResult, MYSQLI_ASSOC)[0]);

$start = strpos($enumString, '(') + 1;
$end = strrpos($enumString, ')');
$enumString = substr($enumString, $start, $end - $start);
$enumValues = explode(',', str_replace("'", "", $enumString));

$fullQuery = "
    SELECT DISTINCT tck.ticketId, tck.title, tck.status, tck.ticketDesc, tck.ticketDate, tck.userId, tck.ticketTypeId 
    FROM tickets tck inner join ticket_types tps on tck.ticketTypeId = tps.ticketTypeId
    WHERE 1=1
";

if($_SESSION['department'][0] != 'Super-admin') {
    $fullQuery .=  "AND (tps.departmentId = {$_SESSION['departmentId'][0]}";
    foreach ($_SESSION['departmentId'] as $departmentId) {
        if ($departmentId != $_SESSION['departmentId'][0]) {
            $fullQuery .= " OR tps.departmentId = $departmentId";
        }
    }
    $fullQuery .= ")";
}

if (!empty($_POST['users'])) {
    $user = $userManager->getUserById($_POST['users']);

    if (isset($user['userId'])) {
        $userId = $user['userId'];
        $fullQuery .= " AND tck.userId = $userId";
    }
}

if (!empty($_POST['types'])) {
    $fullQuery .= " AND tps.ticketTypeId = '{$_POST['types']}' AND tps.ticketTypeId = tck.ticketTypeId";
}

if (!empty($_POST['enumValues'])) {
    $fullQuery .= " AND tck.status = '{$_POST['enumValues']}'";
}

if (!empty($_POST['start']) && !empty($_POST['end'])) {
    $startDate = $_POST["start"];
    $endDate = $_POST["end"];

    $fullQuery .= " AND tck.ticketDate >= '$startDate' AND tck.ticketDate <= '$endDate'";
}

$fullQuery .= " ORDER BY tck.ticketDate;";

$fullQueryResult = mysqli_query($db, $fullQuery);
$tickets = mysqli_fetch_all($fullQueryResult, MYSQLI_ASSOC);

if (isset($_POST['clear_filters'])) {
    $_POST['users'] = null;
    $_POST['types'] = null;
    $_POST['enumValues'] = null;
    $_POST['start'] = null;
    $_POST['end'] = null;
    header("Refresh:0");
}

if (!isset($_POST['users'])) {
    $_POST['users'] = null;
}

if (!isset($_POST['types'])) {
    $_POST['types'] = null;
}

if (!isset($_POST['types'])) {
    $_POST['enumValues'] = null;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="js/calendar.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/searchbar.css">
    <link rel="stylesheet" href="css/daterange.css">
</head>

<?php require("admin_header.php"); ?>

<body>
    <section class="dashboard">
        <section class="tickets">
            <h1 class="title">Tickets</h1>
        </section>

        <form method="post" class="pure-form" id="filters-form">
            <div class="flex">
                <div class="filters">

                    <div class="inputBox" align="center">
                        <select name="users" id="usersid" class="">
                            <option style="font-size: 1.8rem;" value="">Select a user</option>

                            <?php foreach ($users as $user) { ?>
                                <option style="font-size: 1.8rem;" <?php if ($_POST['users'] == $user['userId'])
                                    echo "selected" ?>
                                        value="<?= $user["userId"] ?>">
                                    <?= $user["userEmail"] ?>
                                </option>
                            <?php } ?>
                        </select>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.8.0/slimselect.min.js"
                            integrity="sha512-mG8eLOuzKowvifd2czChe3LabGrcIU8naD1b9FUVe4+gzvtyzSy+5AafrHR57rHB+msrHlWsFaEYtumxkC90rg=="
                            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                        <script>
                            new SlimSelect({
                                select: "#usersid"
                            });
                        </script>
                    </div>

                    <div class="inputBox" align="center">
                        <select name="types" id="typesid" class="">
                            <option style="font-size: 1.8rem;" value="">Select a ticket type</option>
                            <?php foreach ($ticketTypes as $ticketType) { ?>
                                <option style="font-size: 1.8rem;" <?php if ($_POST['types'] == $ticketType['ticketTypeId'])
                                    echo "selected" ?>
                                        value="<?= $ticketType["ticketTypeId"] ?>">
                                    <?= $ticketType["ticketTypeName"] ?>
                                <?php } ?>
                        </select>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.8.0/slimselect.min.js"
                            integrity="sha512-mG8eLOuzKowvifd2czChe3LabGrcIU8naD1b9FUVe4+gzvtyzSy+5AafrHR57rHB+msrHlWsFaEYtumxkC90rg=="
                            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                        <script>
                            new SlimSelect({
                                select: "#typesid"
                            });
                        </script>
                    </div>

                    <div class="inputBox" align="center">
                    <select name="enumValues" id="enumValues #typesidd" class="cursor-pointer">
                            <option style="font-size: 1.8rem;" value="">Select a ticket status</option>
                            <?php foreach ($enumValues as $value) { ?>
                                <option style="font-size: 1.8rem;" <?php if ($_POST['enumValues'] == $value)
                                    echo "selected" ?>
                                        value="<?= $value ?>">
                                    <?= $value ?>
                                <?php } ?>
                        </select>
                    </div>

                    <input type="hidden" id="start" name="start">
                    <input type="hidden" id="end" name="end">
                    <div id="reportrange" class="dateRange inputBox">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span>Select a date</span> <i class="fa fa-caret-down"></i>
                    </div>

                    <div class="inputBox" align="center">
                        <input type="submit" value="Clear filters" class="btn" name="clear_filters">
                    </div>
                </div>
                
                <input type="submit" value="Show tickets" class="btn" name="show_tickets">
            </div>
        </form>

        <?php
        if (empty($_POST["users"]) && empty($_POST["types"]) && empty($_POST["start"]) &&
            empty($_POST["end"]) && empty($_POST["enumValues"])) { //List of all tickets:
            if ($ticketManager->numberOfTickets() != 0) { ?>
                <div class="box-container">
                    <?php
                    foreach ($tickets as $ticket) {
                        $user = $ticketManager->returnUserForSelectedTicket($ticket["ticketId"]);
                        $ticketType = $ticketTypeManager->returnTicketTypeName($ticket['ticketTypeId'])['ticketTypeName'];
                        $ticketDate = date_create($ticket['ticketDate']); ?>

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
                                <p>Status: <span>
                                        <?= $ticket["status"] ?>
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
                                        <?= date_format($ticketDate, 'd.m.Y'); ?>
                                    </span></p>
                            </div>
                            <?php
                            
                            if($ticket["status"] === "Waiting") {
                                echo '<form method="POST" class="reply-form" >
                                        <input type="hidden" name="admin_Id" value="'.$admin_id.'" />
                                        <input type="hidden" name="user_Id" value="'.$user["userId"].'" />
                                        <input type="hidden" name="ticket_Id" value="'.$ticket["ticketId"].'" />
                                        <textarea placeholder="Send a message to the user" name="usr_msg" class="msg_send_admin_form"></textarea>
                                        <button type="submit" name="usr_msg_send" class="btn reply-send-btn">Send</button>
                                    </form>';
                            }

                            ?>
                            <form method="POST" onsubmit="return confirmDeletingTicket();">
                                <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticketId']; ?>"> <br>
                                <button type="submit" name="delete_ticket" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { //No ticket instances database      ?>
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
                            $user = $ticketManager->returnUserForSelectedTicket($ticket["ticketId"]);
                            $ticketType = $ticketTypeManager->returnTicketTypeName($ticket['ticketTypeId'])['ticketTypeName'];
                            $ticketDate = date_create($ticket['ticketDate']); ?>

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
                                <p>Status: <span>
                                        <?= $ticket["status"] ?>
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
                                        <?= date_format($ticketDate, 'd.m.Y'); ?>
                                    </span></p>
                            </div>
                            <?php
                            
                            if($ticket["status"] === "Waiting") {
                                echo '<form method="POST" >
                                <input type="hidden" name="admin_Id" value="'.$admin_id.'" />
                                <input type="hidden" name="user_Id" value="'.$user["userId"].'" />
                                <input type="hidden" name="ticket_Id" value="'.$ticket["ticketId"].'" />
                                <input type="text" placeholder="Send a message to the user" name="usr_msg" class="msg_send_admin_form" />
                                <button type="submit" name="usr_msg_send" class="btn">Send</button>
                            </form>';
                            }

                            ?>
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