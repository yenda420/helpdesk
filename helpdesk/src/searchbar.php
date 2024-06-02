<?php
require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/SearchManager.php';
require 'classes/Database.php';

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
$searchManager = new SearchManager($db);

if (!isset($_POST['search'])) {
    $_POST['search'] = null;
}

$table_associative_array = array(
    'admins' => array(
        'adminId',
        'adminName',
        'adminSurname',
        'adminEmail'
    ),
    'departments' => array(
        'departmentId',
        'departmentName'
    ),
    'requests' => array(
        'requestId',
        'reqName',
        'reqSurname',
        'reqEmail'
    ),
    'ticket_types' => array(
        'ticketTypeId',
        'ticketTypeName',
        'departmentId'
    ),

    'tickets' => array(
        'ticketId',
        'title',
        'status',
        'ticketDesc',
        'ticketDate',
        'userId',
        'ticketTypeId'
    ),
    'users' => array(
        'userId',
        'userName',
        'userSurname',
        'userEmail'
    )
);

if (!empty($_POST['keyword'])) {
    $keywords = array();
    $keywords = explode(' ', $_POST['keyword']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search database</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/searchbar.css">

</head>

<body>

    <?php include 'admin_header.php'; ?>
    <section class="dashboard">
        <section class="users">
            <h1 class="title">Search database</h1>
            <form method="post" action="searchbar.php">
                <div class="flex">
                    <div class="filters">
                        <div class="inputBox" align="center">
                            <input class="search" type="text" name="keyword" placeholder="Enter keywords" />
                        </div>
                    </div>
                    <div class="inputBox">
                        <button type="submit" name="search" class="btn">Search</button>
                    </div><br>
                    <?php
                        if (!empty($_POST['keyword'])) {
                            $resultsFound = false;

                            foreach ($keywords as $keyword) {
                                if ($searchManager->resultsFound($keyword, $table_associative_array)) {
                                    $resultsFound = true;
                                    break;
                                }
                            }

                            if ($resultsFound) {
                                echo '
                                    <div class="box-container">
                                        <div class="emptyWrap">
                                            <div class="emptyDiv">
                                                <p class="empty"><span style="color:black;">Results for </span>' . $_POST['keyword'] . ':</p>
                                            </div>
                                        </div>
                                    </div>
                                ';

                                echo '<div class="box-container">';
                                    foreach ($keywords as $keyword) {
                                        $searchManager->php_search_all_database($keyword, $table_associative_array);
                                    }
                                echo '</div>';

                            } else {
                                echo '
                                    <div class="box-container">
                                        <div class="emptyWrap">
                                            <div class="emptyDiv">
                                                <p class="empty"><span style="color:black"> No results found for </span>' . $_POST['keyword'] . ' </p>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                        } else {
                            echo '
                                <div class="box-container">
                                    <div class="emptyWrap">
                                        <div class="emptyDiv">
                                            <p class="empty"><span style="color:black">Type to search the database</span></p>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    ?>
                </div>
            </form>
        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>