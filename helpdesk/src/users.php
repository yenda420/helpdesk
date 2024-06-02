<?php

require 'classes/SessionManager.php';
require 'classes/MessageManager.php';
require 'classes/UserManager.php';
require 'classes/DepartmentManager.php';
require 'classes/Database.php';
require 'classes/Utility.php';

$sessionManager = new SessionManager();
$sessionManager->startSession();
$database = new Database();
$conn = $database->getConnection();

$admin_id = $sessionManager->getAdminId();
if (!$admin_id) {
    header('location:index.php');
    exit;
}

$userManager = new UserManager($conn);
$departmentManager = new DepartmentManager($conn);
$messageManager = new MessageManager();

$message = [];

if (isset($_POST['delete_user'])) {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        if ($userManager->deleteUser($user_id)) {
            $sessionManager->setMessage("User deleted successfully");
        } else {
            $sessionManager->setMessage("Error deleting user");
        }
    } elseif (isset($_POST['admin_id'])) {
        $admin_id = $_POST['admin_id'];
        if ($userManager->deleteAdmin($admin_id)) {
            $sessionManager->setMessage("Admin deleted successfully");
        } else {
            $sessionManager->setMessage("Error deleting admin");
        }
    }
}

if (isset($_POST['change_dept'])) {
    if (isset($_POST['admin_id']) && isset($_POST['department'])) {
        $admin_id = $_POST['admin_id'];
        $department_names = $_POST['department'];
        $sessionManager->setMessage($userManager->changeAdminDepartment($admin_id, $department_names));
    }
}

if (isset($_POST['filter'])) {
    if ($_POST['users'] == 'frontend') {
        $frontendUsers = $userManager->getAllUsers();
        $backendUsers = [];
    } elseif ($_POST['users'] == 'backend') {
        $frontendUsers = [];
        $backendUsers = $departmentManager->returnAllBackendUsers();
    } else {
        $frontendUsers = $userManager->getAllUsers();
        $backendUsers = $departmentManager->returnAllBackendUsers();
    }
} else {
    $frontendUsers = $userManager->getAllUsers();
    $backendUsers = $departmentManager->returnAllBackendUsers();
}

if (!isset($_POST['userSearch'])) {
    $_POST['userSearch'] = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/searchbar.css">
</head>

<body>
    <?php include 'admin_header.php';
    if(!isset($_POST['users'])) $_POST['users'] = 'all';
    ?>

    <section class="dashboard">
        <section class="users">
            <h1 class="title">Users</h1>
            <form method="post">
                <div class="flex">
                    <div class="filters">
                        <div class="inputBox">
                            <select name="users" class="" required>
                                <option value="all">--- Choose a user type ---</option>
                                <option <?php if ($_POST['users'] == 'frontend') echo 'selected' ?> value="frontend">Frontend</option>
                                <option <?php if ($_POST['users'] == 'backend') echo 'selected' ?> value="backend">Backend</option>
                            </select>
                        </div>
                        <div class="inputBox" align="center">
                            <select name="userSearch" id="userSearch">
                                <option style="font-size: 1.8rem;" value="">Select a user or type to search</option>
                                <?php foreach ($frontendUsers as $user) { ?>
                                    <option style="font-size: 1.8rem;" <?php if ($_POST['userSearch'] == $user['userId']) echo "selected"; ?> value="<?= $user['userId'] ?>">
                                        <?= $user['userEmail'] ?>
                                    </option>
                                <?php } ?>
                                <?php foreach ($backendUsers as $user) { ?>
                                    <option style="font-size: 1.8rem;" <?php if ($_POST['userSearch'] == $user['adminId']) echo "selected"; ?> value="<?= $user['adminId'] ?>">
                                        <?= $user['adminEmail'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/2.8.0/slimselect.min.js" integrity="sha512-mG8eLOuzKowvifd2czChe3LabGrcIU8naD1b9FUVe4+gzvtyzSy+5AafrHR57rHB+msrHlWsFaEYtumxkC90rg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                        <script>
                            new SlimSelect({
                                select: "#userSearch"
                            });
                        </script>
                    </div>
                    <div class="inputBox">
                        <button type="submit" name="filter" class="btn">Show users</button>
                    </div><br>

                    <div class="box-container">
                        <?php
                        if (empty($_POST["userSearch"])) {
                            if (isset($frontendUsers)) {
                                foreach ($frontendUsers as $user) {
                                    echo '<form method="post">';
                                    echo '<div class="box">
                                        <div class="breaking"><p> ID: <span>' . $user['userId'] . '</span> </p></div>
                                        <div class="breaking"><p> Name: <span>' . $user['userName'] . '</span> </p></div>
                                        <div class="breaking"><p> Surname: <span>' . $user['userSurname'] . '</span> </p></div>
                                        <div class="breaking"><p> Email: <span>' . $user['userEmail'] . '</span> </p></div>';
                                    if ($sessionManager->getDepartments()[0] == 'Super-admin') {
                                        echo '<input type="hidden" name="user_id" value="' . $user['userId'] . '"><br>
                                        <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingUser()">Delete</button>
                                    </div>';
                                    } else {
                                        echo '</div>';
                                    }
                                    echo '</form>';
                                }
                            }

                            if (isset($backendUsers)) {
                                foreach ($backendUsers as $user) {
                                    echo '<form method="post">';
                                    echo '<div class="box">
                                        <div class="breaking"><p> ID: <span>' . $user['adminId'] . '</span> </p></div>
                                        <div class="breaking"><p> Name: <span>' . $user['adminName'] . '</span> </p></div>
                                        <div class="breaking"><p> Surname: <span>' . $user['adminSurname'] . '</span> </p></div>
                                        <div class="breaking"><p> Email: <span>' . $user['adminEmail'] . '</span> </p></div>';
                                    echo '<div class="breaking" style="overflow:hidden;"><p> Department : <span align="center" style="justify-content: center;">';
                                    $stmt = $conn->prepare("SELECT * FROM `department_lists` where adminId = ?");
                                    $stmt->bind_param("i", $user['adminId']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $fetch_departments = $result->fetch_all(MYSQLI_ASSOC);

                                    $departmentNames = [];
                                    if ($fetch_departments) {
                                        foreach ($fetch_departments as $department) {
                                            $departmentNames[] = $userManager->returnDepartmentName($department['departmentId']);
                                        }
                                    }
                                    echo "<textarea name='department' rows='" . count($departmentNames) . "' cols='21'>";
                                    echo implode("\n", $departmentNames);
                                    echo '</textarea>';
                                    echo '</span></p></div>';
                                    if ($sessionManager->getDepartments()[0] == 'Super-admin') {
                                        echo '<input type="hidden" name="admin_id" value="' . $user['adminId'] . '"> <br>
                                        <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingAdmin()">Delete</button>
                                        <button type="submit" name="change_dept" class="btn" onclick="return confirmChangingDepartments()">Change</button>
                                    </div>';
                                    } else {
                                        echo '</div>';
                                    }
                                    echo '</form>';
                                }
                            }
                        } else {
                            if (isset($_POST['userSearch'])) {
                                $user_id = $_POST['userSearch'];
                                $oneUser = $userManager->getUserById($user_id);
                                if (!$oneUser) {
                                    $oneUser = $userManager->getAdminById($user_id);
                                }

                                if ($oneUser) {
                                    if (($_POST['users'] == 'frontend' && isset($oneUser['adminId'])) ||
                                        ($_POST['users'] == 'backend' && isset($oneUser['userId']))) {
                                        echo '<p class="empty">Not a user of the selected type</p>';
                                    } else {
                                        echo '<form method="post">';
                                        echo '<div class="box">
                                        <div class="breaking"><p> ID: <span>' . ($oneUser['userId'] ?? $oneUser['adminId']) . '</span> </p></div>
                                        <div class="breaking"><p> Name: <span>' . ($oneUser['userName'] ?? $oneUser['adminName']) . '</span> </p></div>
                                        <div class="breaking"><p> Surname: <span>' . ($oneUser['userSurname'] ?? $oneUser['adminSurname']) . '</span> </p></div>
                                        <div class="breaking"><p> Email: <span>' . ($oneUser['userEmail'] ?? $oneUser['adminEmail']) . '</span> </p></div>';
                                        if (isset($oneUser['adminId'])) {
                                            echo '<div class="breaking"><p> Department: <span>';

                                            $stmt = $conn->prepare("SELECT * FROM `department_lists` where adminId = ?");
                                            $stmt->bind_param("i", $oneUser['adminId']);
                                            $stmt->execute();
                                            $select_departments = $stmt->get_result();

                                            $departmentNames = [];
                                            if (mysqli_num_rows($select_departments) > 0) {
                                                while ($fetch_departments = mysqli_fetch_assoc($select_departments)) {
                                                    $departmentNames[] = $userManager->returnDepartmentName($fetch_departments['departmentId']);
                                                }
                                            }
                                            echo '<input style="width:fit-content; max-width: 20rem" type="text" name="department" value="' . implode(', ', $departmentNames) . '">';
                                            echo '</span> </p></div>';
                                        }
                                        if ($sessionManager->getDepartments()[0] == 'Super-admin') {
                                            echo '<input type="hidden" name="' . (isset($oneUser['userId']) ? 'user_id' : 'admin_id') . '" value="' . ($oneUser['userId'] ?? $oneUser['adminId']) . '"> <br>
                                            <button type="submit" name="delete_user" class="delete-btn" onclick="return confirmDeletingUser()">Delete</button>';
                                            if (isset($oneUser['adminId'])) {
                                                echo '<button type="submit" name="change_dept" class="btn" onclick="return confirmChangingDepartments()">Change</button>';
                                            }
                                        }
                                        echo '</div>';
                                        echo '</form>';
                                    }
                                } else {
                                    echo '<p class="empty">No user found</p>';
                                }
                            }
                        }

                        if (isset($backendUsers) && isset($frontendUsers)) {
                            if (count($frontendUsers) == 0 && count($backendUsers) == 0) {
                                echo '<p class="empty">No users</p>';
                            }
                            if (count($frontendUsers) == 0 && $_POST['users'] == 'frontend') {
                                echo '<p class="empty">No frontend users</p>';
                            }
                            if (count($backendUsers) == 0 && $_POST['users'] == 'backend') {
                                echo '<p class="empty">No backend users</p>';
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
