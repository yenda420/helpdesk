<?php

require 'classes/TicketManager.php';
require 'classes/TicketTypeManager.php';
require 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/ConvoMessageManager.php';
require 'classes/MessageManager.php';
require 'classes/UserManager.php';
require 'classes/Utility.php';


$sessionManager = new SessionManager();
$messageManager = new MessageManager();

$database = new Database();
$db = $database->getConnection();
$ticketTypeManager = new TicketTypeManager($db);
$ticketManager = new TicketManager($db);
$userManager = new UserManager($db);
$convoMessageManager = new ConvoMessageManager($db);

$sessionManager->startSession();

$user_id = $sessionManager->getUserId();
if (!$user_id) {
    header('location:index.php');
    exit;
}
$msgCount = 0;


if (isset($_POST['helped'])) {
    $ticketUpdated = $ticketManager->markAsResolved($_POST['sender_id'],$_POST['ticket_id']);
    $messageUpdated = $convoMessageManager->updateUserReplied($_POST['message_id']);

    if ($ticketUpdated && $messageUpdated) {
        $sessionManager->setMessage("Ticket marked as resolved");
    } else {
        $sessionManager->setMessage("Failed to mark ticket as resolved");
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['usr_msg_send'])) {
    $messageInserted = $convoMessageManager->insertMessage($_POST['convo_id'], $_POST['usr_msg'], $_SESSION['user_id']);
    $messageUpdated = $convoMessageManager->updateUserReplied($_POST['message_id']);

    if ($messageInserted && $messageUpdated) {
        $sessionManager->setMessage("Message sent successfully");
    } else {
        $sessionManager->setMessage("Failed to send message");
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require 'header.php'; ?>
    <section class="dashboard">
        <section class="users">
            <h1 class="title">Your Messages</h1>
            <div class="box-container">
                <?php
                foreach ($userManager->getUserConversations($_SESSION['user_id']) as $convo) {
                    foreach ($convoMessageManager->getMessagesFromAdmin($convo["convoId"]) as $message) {
                        if (!$message["userReplied"]) {
                            $admin = $userManager->getAdminDetails($message["senderAdminId"]);
                            $senderName = $admin['adminName'] . " " . $admin['adminSurname'];
                            $senderEmail = $admin['adminEmail'];
                            $ticketTitle = $ticketManager->getTicketTitle($convo["ticketId"]);
                            $msgContent = $message["msgContent"];
                            $msgCount++; ?>

                            <div class="box">
                                <div class="breaking">
                                    <p> Message from:
                                        <span><?= $senderName ?></span>
                                    </p>
                                </div>
                                <div class="breaking">
                                    <p> Senders contact:
                                        <span><?= $senderEmail ?></span>
                                    </p>
                                </div>
                                <div class="breaking">
                                    <p> Response for:
                                        <span><?= $ticketTitle ?></span>
                                    </p>
                                </div>
                                <div class="breaking">
                                    <p> Message:
                                        <span><?= $msgContent ?></span>
                                    </p>
                                </div>
                                <form method="POST" class="help-form">
                                    <div class="help-text">Did this help you?</div>
                                    <div>
                                        <input type="hidden" name="sender_id" value="<?php echo $message["senderAdminId"]; ?>">
                                        <input type="hidden" name="convo_id" value="<?php echo $convo["convoId"]; ?>">
                                        <input type="hidden" name="ticket_id" value="<?php echo $convo["ticketId"]; ?>">
                                        <input type="hidden" name="message_id" value="<?php echo $message["msgId"]; ?>">
                                        <button type="submit" name="helped" class="btn">Yes</button>
                                        <button class="delete-btn no-btn">No</button>
                                        <div class="user-reply notActive">
                                            <textarea name="usr_msg" id="usr_msg" class="msg_send_user_form"
                                                placeholder="Please describe your problem in more detail. Did our reply change the state of your issue?"></textarea>
                                            <button type="submit" name="usr_msg_send" class="btn reply-send-btn">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php }
                    }
                }
                if ($msgCount == 0) { ?>
                    <p class="empty">No new messages</p>
                <?php } ?>
            </div>
        </section>
    </section>
    <script src="js/script.js"></script>
    <?php require 'footer.php'; ?>
</body>
</html>
