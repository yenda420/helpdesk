<?php

include 'config.php';
include 'functions.php';

session_start();
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
                        foreach(returnMessages($conn, $convo["convoId"]) as $message) {
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
                            </div>
                <?php   }
                    }
                ?>
            </div>
        </section>
    </section>
    <script src="js/script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>