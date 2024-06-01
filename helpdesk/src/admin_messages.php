<?php

include 'config.php';
include 'functions.php';

session_start();

$msgCount = 0;

if (isset($_POST['usr_msg_send'])) {
    $query = '
        INSERT INTO messages (conversationId, msgContent, senderAdminId)
        VALUES (?, ?, ?)
    ';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $_POST['convo_id'], $_POST['usr_msg'], $_SESSION['admin_id']);
    $insert_msg_query = $stmt->execute();
    $stmt->close();

    $query = '
        UPDATE messages
        SET adminReplied = 1
        WHERE msgId = ?
    ';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_POST['message_id']);
    $update_msg_query = $stmt->execute();
    $stmt->close();

    if ($insert_msg_query && $update_msg_query) {
        $_SESSION['message'] = "Message sent successfully";
    } else {
        $_SESSION['message'] = "Failed to send message";
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
                foreach (returnConvosAdmin($conn, $_SESSION['admin_id']) as $convo) {
                    foreach (returnMessagesFromUser($conn, $convo["convoId"]) as $message) {
                        if (!$message["adminReplied"]) {
                            $senderName = returnUser($conn, $message["senderUserId"])['userName'] . " " .
                            returnUser($conn, $message["senderUserId"])['userSurname'];
                            $senderEmail = returnUser($conn, $message["senderUserId"])['userEmail'];
                            $ticketTitle = returnTicket($conn, $convo["ticketId"])['title'];
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
    <script src="js/script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>