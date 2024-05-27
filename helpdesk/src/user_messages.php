<?php

include 'config.php';
include 'functions.php';

session_start();

if (isset($_POST['helped'])) {
    $query = '
        UPDATE tickets
        SET status = "Resolved"
        WHERE ticketId = ?
    ';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_POST['ticket_id']);
    $stmt->execute();

    if ($stmt->affected_rows <= 0)
        $_SESSION['message'] = "Error updating database";

    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['usr_msg_send'])) {
    $query = '
        INSERT INTO messages (conversationId, msgContent, senderUserId)
        VALUES (?, ?, ?)
    ';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $_POST['convo_id'], $_POST['usr_msg'], $_SESSION['user_id']);
    $insert_msg_query = $stmt->execute();
    $stmt->close();

    if ($insert_msg_query) {
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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <section class="dashboard">
        <section class="users">
            <h1 class="title">Your Messages</h1>
            <div class="box-container">
                <?php
                foreach (returnConvos($conn, 88) as $convo) {
                    foreach (returnMessagesFromAdmin($conn, $convo["convoId"]) as $message) {
                        $senderName = returnAdmin($conn, $message["senderAdminId"])['adminName'] . " " .
                            returnAdmin($conn, $message["senderAdminId"])['adminSurname'];
                        $senderEmail = returnAdmin($conn, $message["senderAdminId"])['adminEmail'];
                        $ticketTitle = returnTicket($conn, $convo["ticketId"])['title'];
                        $msgContent = $message["msgContent"]; ?>

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
                                <div class="help-text">Did this help you?</div>
                                <div>
                                    <input type="hidden" name="convo_id" value="<?php echo $convo["convoId"]; ?>">
                                    <input type="hidden" name="ticket_id" value="<?php echo $convo["ticketId"]; ?>">
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
                } ?>
            </div>
        </section>
    </section>
    <script src="js/script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>