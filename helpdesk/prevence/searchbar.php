<?php
require('functions.php');

session_start();
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    header('location:index.php');
}

if ($_SESSION['department'][0] != 'Super-admin') {
    $fullQuery .= "AND (tps.departmentId = {$_SESSION['departmentId'][0]}";
    foreach ($_SESSION['departmentId'] as $departmentId) {
        if ($departmentId != $_SESSION['departmentId'][0]) {
            $fullQuery .= " OR tps.departmentId = $departmentId";
        }
    }
    $fullQuery .= ")";
}

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

$keywords = array();

if (isset($_POST['keyword'])) {
    $keywords = explode(' ', $_POST['keyword']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/searchbar.css">

</head>

<body>

    <?php include 'admin_header.php';
    if (!isset($_POST['users'])) {
        $_POST['users'] = null;
    }
    ?>
    <section class="dashboard">
        <section class="users">
            <h1 class="title">Search</h1>
            <form method="post" action="searchbar.php">
                <div class="flex">
                    <div class="filters">
                        <div class="inputBox" align="center">
                            <input class="search" type="text" name="keyword" />
                        </div>
                    </div>
                    <div class="inputBox">
                        <button type="submit" name="search" class="btn">Search</button>
                    </div><br>
                    <?php
                        if (isset($_POST['keyword'])) {
                            echo '
                                <div class="box-container">
                                    <div class="emptyWrap">
                                        <div class="emptyDiv">
                                            <p class="empty"><span style="color:black;">Searched Keyword: </span>' . $_POST['keyword'] . '</p>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    ?>
                    <div class="box-container">
                        <?php
                            if (isset($_POST['keyword'])) {
                                $noResults = true;

                                foreach ($keywords as $keyword) {
                                    if (php_search_all_database($keyword, $table_associative_array)) {
                                        $noResults = false;
                                    }
                                }

                                if ($noResults) {
                                    echo '
                                        <div class="emptyWrap">
                                            <div class="emptyDiv">
                                                <p class="empty"><span style="color:black"> No results found for </span>' . $_POST['keyword'] . ' </p>
                                            </div>
                                        </div>
                                    ';
                                }
                            }
                        ?>
                    </div>
                </div>
            </form>
        </section>
    </section>
    <script src="js/admin_script.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
function php_search_all_database($search_keyword, $table_associative_array)
{
    global $conn;
    $conn = mysqli_connect("172.31.1.103", "helpdesk", "Helpdesk.123", "helpdesk");
    $conn->set_charset("utf8");
    $count = 0;

    if (mysqli_connect_errno()) {		// Check if database connection is ok
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if (count($table_associative_array) > 0) {
        foreach ($table_associative_array as $table_name => $columnn_name) {
            foreach ($columnn_name as $column) {
                $db_search_result_fields = $column . " LIKE ('%" . $search_keyword . "%')";		// We have used wildcards as an example, You can replace as per your need
                $db_search_result = $conn->query("SELECT * FROM " . $table_name . " WHERE " . $db_search_result_fields);

                if ($db_search_result->num_rows > 0) {
                    while ($row = $db_search_result->fetch_array()) {
                        if ($table_name == 'admins') {
                            $columnName = substr($column, 5);
                        } else if ($table_name == 'users') {
                            $columnName = substr($column, 4);
                        } else if ($table_name == 'requests') {
                            $columnName = substr($column, 3);
                        } else if ($table_name == 'ticket_types') {
                            $columnName = $column;
                        } else if ($table_name == 'departments') {
                            $columnName = $column;
                        } else if ($table_name == 'tickets') {
                            $columnName = $column;
                        }
                        
                        echo '<div class="box">';
                            if (($table_name == 'admins') or ($table_name == 'users')) {
                                echo '<div class="breaking"><p> Page: <span><a href="users.php"> All users
                                </a></span></p></div>';
                            } else if ($table_name == 'requests') {
                                echo '<div class="breaking"><p> Page: <span><a href="admin_page.php"> Requests
                                </a></span></p></div>';
                            } else if ($table_name == 'departments') {
                                echo '<div class="breaking"><p> Page: <span><a href="departments.php"> Departments
                                </a></span></p></div>';
                            } else if ($table_name == 'tickets') {
                                echo '<div class="breaking"><p> Page: <span><a href="admin_tickets.php"> Tickets
                                </a></span></p></div>';
                            } else if ($table_name == 'ticket_types') {
                                echo '<div class="breaking"><p> Page: <span><a href="tck_types.php"> Ticket types
                                </a></span></p></div>';
                            }

                            echo '<div class="breaking"><p>Column name: <span>' . $columnName . "</span></p></div>";
                            echo '<div class="breaking"><p>Row: <span>' . $row[0] . "</span></p></div>";
                            echo '<div class="breaking"><p>Value: <span>' . $row[$column] . "</span></p></div>";
                        echo '</div>';
                    }
                } else {
                    if ($db_search_result->num_rows == 0) {
                        $count++;
                    }
                }
            }
        }
    }
    if ($count >= 24) {
        return 0;
    } else {
        return 1;
    }
}