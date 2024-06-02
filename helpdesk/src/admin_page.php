<?php

require 'classes/Database.php';
require 'classes/RequestManager.php';
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';

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
$request = new RequestManager($db);

if (isset($_POST['delete_request'])) {
    $request_id = $_POST['request_id'];
    $result = $request->deleteRequest($request_id);
    $sessionManager->setMessage($result ? "Request deleted successfully" : "Error deleting request");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['accept_request'])) {
    $request_id = $_POST['request_id'];
    $result = $request->acceptRequest($request_id);
    $sessionManager->setMessage($result ? "User added successfully" : "Error adding user");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

    <?php include 'admin_header.php'; ?>
    <section class="dashboard">
        <section class="users">
            <h1 class="title">Requests</h1>
            <div class="box-container">
                <?php
                $requests = $request->getAllRequests();
                if (empty($requests)) {
                    echo '<p class="empty">No requests</p>';
                } else {
                    foreach ($requests as $req) {
                ?>
                        <div class="box">
                            <div class="breaking">
                                <p>ID: <span><?php echo $req['requestId']; ?></span></p>
                            </div>
                            <div class="breaking">
                                <p>Name: <span><?php echo $req['reqName']; ?></span></p>
                            </div>
                            <div class="breaking">
                                <p>Surname: <span><?php echo $req['reqSurname']; ?></span></p>
                            </div>
                            <div class="breaking">
                                <p>Email: <span><?php echo $req['reqEmail']; ?></span></p>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="request_id" value="<?php echo $req['requestId']; ?>">
                                <button type="submit" name="delete_request" class="delete-btn" onclick="return confirm('Are you sure you want to delete this request?')">Delete</button>
                                <button type="submit" name="accept_request" class="btn" onclick="return confirm('Are you sure you want to accept this request?')">Accept</button>
                            </form>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>
    </section>

    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>
