<?php

include 'classes/TicketManager.php';
include 'classes/Database.php';
require 'classes/SessionManager.php';
require 'classes/ConvoMessageManager.php';
include 'classes/MessageManager.php';
include 'classes/UserManager.php';
include 'classes/Utility.php';

$sessionManager = new SessionManager();
$sessionManager->startSession();

$messageManager = new MessageManager();

$database = new Database();
$db = $database->getConnection();
$ticketManager = new TicketManager($db);
$userManager = new UserManager($db);
$convoMessageManager = new ConvoMessageManager($db);


$admin_id = $sessionManager->getAdminId();
if (!$admin_id) {
    header('location:index.php');
    exit;
}

$msgCount = 0;

if (isset($_POST['usr_msg_send'])) {
    $messageInserted = $convoMessageManager->insertMessageAdmin($_POST['convo_id'], $_POST['usr_msg'], $_SESSION['admin_id']);
    $messageUpdated = $convoMessageManager->updateAdminReplied($_POST['message_id']);

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
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>
    <?php include 'admin_header.php'; ?>
    <section class="dashboard">
        <section class="users">
            <h1 class="title">Your Messages</h1>
            <div class="box-container">
                <?php
                foreach ($userManager->getAdminConversations($_SESSION['admin_id']) as $convo) {
                    foreach ($convoMessageManager->getMessagesFromUser($convo["convoId"]) as $message) {
                        if (!$message["adminReplied"]) {
                            $user = $userManager->getUserDetails($message["senderUserId"]);
                            $senderName = $user['userName'] . " " . $user['userSurname'];
                            $senderEmail = $user['userEmail'];
                            $ticketTitle = $ticketManager->getTicketTitle($convo["ticketId"]);
                            $msgContent = $message["msgContent"];
                            $msgCount++; ?>

                            <div class="box">
                                <div class="breaking">
                                    <p> Message from:
                                        <span>
                                            <?= $senderName ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="breaking">
                                    <p> Senders contact:
                                        <span>
                                            <?= $senderEmail ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="breaking">
                                    <p> Response for:
                                        <span>
                                            <?= $ticketTitle ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="breaking">
                                    <p> Message:
                                        <span>
                                            <?= $msgContent ?>
                                        </span>
                                    </p>
                                </div>
                                <form method="POST" class="help-form">
                                    <div>
                                        <input type="hidden" name="convo_id" value="<?php echo $convo["convoId"]; ?>">
                                        <input type="hidden" name="ticket_id" value="<?php echo $convo["ticketId"]; ?>">
                                        <input type="hidden" name="message_id" value="<?php echo $message["msgId"]; ?>">
                                        <textarea name="usr_msg" id="usr_msg" class="msg_send_user_form"
                                            placeholder="Please write your answer in more detail."></textarea>
                                        <button type="submit" name="usr_msg_send" class="btn reply-send-btn">Send</button>
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
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>