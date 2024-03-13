<?php
function returnDepartments($conn)
{
    $sql = "SELECT * FROM departments";

    $sqlResult = mysqli_query($conn, $sql);
    $departments = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $departments;
}

function returnDepartmentName($conn, $departmentId)
{
    $sql = "SELECT * FROM departments WHERE departmentId = $departmentId";

    $sqlResult = mysqli_query($conn, $sql);
    $department = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $department[0]['departmentName'];
}

function returnDepartmentId($conn, $departmentName)
{
    $sql = "SELECT * FROM departments WHERE departmentName = '$departmentName'";

    $sqlResult = mysqli_query($conn, $sql);
    $department = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $department[0];
}
function departmentExists($conn, $departmentName)
{
    $sql = "SELECT * FROM departments WHERE departmentName = '$departmentName'";

    $sqlResult = mysqli_query($conn, $sql);
    $department = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    if ($department) {
        return 1;
    } else {
        return 0;
    }
}
function adminExists($conn, $adminEmail)
{
    $sql = "SELECT * FROM admins WHERE adminEmail = '$adminEmail'";

    $sqlResult = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    if ($admin) {
        return 1;
    } else {
        return 0;
    }
}
function ticketTypeExists($conn, $ticketTypeName)
{
    $sql = "SELECT * FROM ticket_types WHERE ticketTypeName = '$ticketTypeName'";

    $sqlResult = mysqli_query($conn, $sql);
    $type = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    if ($type) {
        return 1;
    } else {
        return 0;
    }
}

function returnAdminId($conn, $adminEmail)
{
    $sql = "SELECT * FROM admins WHERE adminEmail = '$adminEmail'";

    $sqlResult = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $admin[0];
}

function returnAllFrontendUsers($conn)
{
    $sql = "SELECT * FROM users";

    $sqlResult = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $users;
}

function returnAllBackendUsers($conn)
{
    $sql = "SELECT * FROM admins";

    $sqlResult = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
    return $users;
}

function returnAllBackendsForDepartmentId($conn, $departmentId)
{
    $sql = "
            SELECT adminEmail
            FROM admins INNER JOIN department_lists USING (adminId)
            WHERE departmentId = $departmentId;
    ";

    $sqlResult = mysqli_query($conn, $sql);
    $admins = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $admins;
}

function returnAllUsers($conn)
{
    $sql = "SELECT * FROM admins UNION DISTINCT SELECT * FROM users";

    $sqlResult = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);


    return $users;
}

function returnTicketsForSelectedUser($conn, $data)
{
    $sql = "
            SELECT *
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND u.userId = '{$data["users"]}';
        ";

    $sqlResult = mysqli_query($conn, $sql);
    $tickets = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $tickets;
}

function returnUserForSelectedTicket($conn, $ticketId)
{
    $sql = "
            SELECT u.userName, u.userSurname, u.userEmail
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND t.ticketId = $ticketId;
        ";

    $sqlResult = mysqli_query($conn, $sql);
    $user = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $user[0];
}

function returnTicketTypesForDepartmentName($conn, $departmentName)
{
    if ($departmentName == 'Super-admin') {
        $sql = "SELECT ticketTypeName, ticketTypeId FROM ticket_types";
    } else {
        $sql = "
                SELECT ticketTypeName, ticketTypeId
                FROM ticket_types INNER JOIN departments USING (departmentId)
                WHERE departmentName = '{$departmentName}';
            ";
    }

    $sqlResult = mysqli_query($conn, $sql);
    $ticketTypes = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $ticketTypes;
}

function returnUser($conn, $userId)
{
    $sql = "SELECT * FROM users WHERE userId = $userId";

    $sqlResult = mysqli_query($conn, $sql);
    $user = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $user[0];
}

function returnAdmin($conn, $adminId)
{
    $sql = "SELECT * FROM admins WHERE adminId = $adminId";

    $sqlResult = mysqli_query($conn, $sql);
    $user = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $user[0];
}

function numberOfTicketsForUser($conn, $data)
{
    $sql = "
            SELECT *
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND u.userId = '{$data["users"]}';
        ";

    $sqlResult = mysqli_query($conn, $sql);
    $numberOfRecords = mysqli_num_rows($sqlResult);

    return $numberOfRecords;
}

function numberOfTicketsForUserId($conn, $userId)
{
    $sql = "
            SELECT *
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND u.userId = $userId;
        ";

    $sqlResult = mysqli_query($conn, $sql);
    $numberOfRecords = mysqli_num_rows($sqlResult);

    return $numberOfRecords;
}

function returnTicketTypeName($conn, $ticketTypeId)
{
    $sql = "SELECT * FROM ticket_types WHERE ticketTypeId = $ticketTypeId";

    $sqlResult = mysqli_query($conn, $sql);
    $ticketType = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $ticketType[0];
}

function returnTicketTypes($conn)
{
    $ticket_types = mysqli_query($conn, "SELECT * FROM `ticket_types`") or die('query failed');
    return $ticket_types;
}

function numberOfTickets($conn)
{
    $sql = "SELECT * FROM tickets;";

    $sqlResult = mysqli_query($conn, $sql);
    $numberOfRecords = mysqli_num_rows($sqlResult);

    return $numberOfRecords;
}

function emailInDatabase($dbConnect, $email)
{

    $sqlUsers = "
            SELECT *
            FROM users
            WHERE userEmail = '{$email}';
        ";

    $sqlRequests = "
            SELECT *
            FROM requests
            WHERE reqEmail = '{$email}';
        ";

    $sqlRequests = "
            SELECT *
            FROM admins
            WHERE adminEmail = '{$email}';
        ";

    $sqlResultUsers = mysqli_query($dbConnect, $sqlUsers);
    $numberOfRecordsUsers = mysqli_num_rows($sqlResultUsers);

    $sqlResultRequests = mysqli_query($dbConnect, $sqlRequests);
    $numberOfRecordsRequests = mysqli_num_rows($sqlResultRequests);

    $sqlResultAdmins = mysqli_query($dbConnect, $sqlRequests);
    $numberOfRecordsAdmins = mysqli_num_rows($sqlResultRequests);

    if (
        $numberOfRecordsRequests == 0 &&
        $numberOfRecordsUsers == 0 &&
        $numberOfRecordsRequests == 0
    ) {
        return 0;
    } else {
        return 1;
    }
}

function registerUser($conn, $data)
{
    $name = mysqli_real_escape_string($conn, $data['name']);
    $surname = mysqli_real_escape_string($conn, $data['surname']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $pass = mysqli_real_escape_string($conn, hash('sha256', $data['password']));

    if ($data['password'] != $data['cpassword'])
        return 'Passwords don\'t match!';
    if (strlen($data['password']) < 8)
        return 'Password needs at least 8 characters.';
    if (!preg_match('/[A-Z]/', $data['password']))
        return 'Password needs at least 1 upper case character.';
    if (!preg_match('/\d/', $data['password']))
        return 'Password needs at least 1 number.';
    if (!preg_match("/[^a-zA-Z0-9]/", $data['password']))
        return 'Password needs at least 1 special character.';
    if (emailInDatabase($conn, $email))
        return 'Account with this email already exists.';

    $sqlInsert = "
        INSERT INTO `requests` 
        SET reqName='$name', reqSurname='$surname', reqEmail='$email', reqPasswd='$pass';
    ";

    if (!mysqli_query($conn, $sqlInsert))
        return 'Query failed.';

    return 'Request for an account was successful.';
}

function createAdmin($conn, $data)
{
    $name = mysqli_real_escape_string($conn, $data['createAdminName']);
    $surname = mysqli_real_escape_string($conn, $data['createAdminSurname']);
    $email = mysqli_real_escape_string($conn, $data['createAdminEmail']);
    $pass = mysqli_real_escape_string($conn, hash('sha256', $data['createAdminPasswd']));

    if ($data['createAdminPasswd'] != $data['createAdminPasswdConf'])
        return 'Passwords don\'t match!';
    if (strlen($data['createAdminPasswd']) < 8)
        return 'Password needs at least 8 characters.';
    if (!preg_match('/[A-Z]/', $data['createAdminPasswd']))
        return 'Password needs at least 1 upper case character.';
    if (!preg_match('/\d/', $data['createAdminPasswd']))
        return 'Password needs at least 1 number.';
    if (!preg_match("/[^a-zA-Z0-9]/", $data['createAdminPasswd']))
        return 'Password needs at least 1 special character.';
    if (emailInDatabase($conn, $email))
        return 'Account with this email already exists.';

    foreach ($data['department'] as $department) {
        $departmentIds[] = returnDepartmentId($conn, mysqli_real_escape_string($conn, $department))['departmentId'];
        $departmentNames[] = returnDepartmentName($conn, returnDepartmentId($conn, mysqli_real_escape_string($conn, $department))['departmentId']);
    }

    if (count($departmentIds) > 1) {
        if (in_array('Super-admin', $departmentNames))
            return 'You can\'t select multiple departments if you chose the "Super-admin" department.';
        if (in_array('Unassigned', $departmentNames))
            return 'You can\'t select multiple departments if you chose the "Unassigned" department.';
    }

    $insertIntoAdmins = "
        INSERT INTO `admins` (adminName, adminSurname, adminEmail, adminPasswd) 
        VALUES ('$name', '$surname', '$email','$pass');
    ";

    if (!mysqli_query($conn, $insertIntoAdmins))
        return 'Query failed';

    $createdAdminId = returnAdminId($conn, $email)['adminId'];

    foreach ($departmentIds as $departmentId) {

        $insertIntoDepartment_lists = "
            INSERT INTO department_lists (departmentId, adminId) 
            VALUES ($departmentId, $createdAdminId);
        ";

        if (!mysqli_query($conn, $insertIntoDepartment_lists))
            return 'Query failed';
    }

    return 'Admin was successfuly created.';
}

function deleteDepartment($conn, $departmentId)
{
    $delete_lists = "DELETE FROM department_lists WHERE departmentId = $departmentId";
    if (mysqli_query($conn, $delete_lists)) {
        $update_tickets = "UPDATE ticket_types SET departmentId = 0 WHERE departmentId = $departmentId";
        if (mysqli_query($conn, $update_tickets)) {
            $delete_department = "DELETE FROM departments WHERE departmentId = $departmentId";
            if (mysqli_query($conn, $delete_department)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
function isAdminUnassigned($conn, $adminId)
{
    $sql = "SELECT * FROM department_lists WHERE adminId = $adminId AND departmentId = 0;";
    $sqlResult = mysqli_query($conn, $sql);
    $numberOfRecords = mysqli_num_rows($sqlResult);
    if ($numberOfRecords == 0) {
        return 0;
    } else {
        return 1;
    }
}
function isAdminInDepartment($conn, $adminId)
{
    $sql = "SELECT * FROM department_lists WHERE adminId = $adminId";
    $sqlResult = mysqli_query($conn, $sql);
    $numberOfRecords = mysqli_num_rows($sqlResult);
    if ($numberOfRecords == 0) {
        return 0;
    } else {
        return 1;
    }

}
function changeAdminDepartment($conn, $admin_id, $department_names)
{
    if (!preg_match('/^[a-zA-Z0-9\s]+(,\s[a-zA-Z0-9\s]+)*$/', $department_names) && !preg_match('/^[a-zA-Z0-9\s]+(,[a-zA-Z0-9\s]+)*$/', $department_names) && !in_array('Super-admin', explode(',', $department_names)))
        return "Wrong format, please use: dep1, dep2, dep3.";
    $department_names = explode(',', $department_names);
    $department_names = array_map('trim', $department_names);
    //if there is no department of this name in the database
    foreach ($department_names as $department_name) {
        if (!departmentExists($conn, $department_name)) {
            return "Department $department_name doesn't exist.";
        }
    }
    //if admin is unassigned
    if (in_array('Unassigned', $department_names)) {
        if (isAdminUnassigned($conn, $admin_id)) {
            return "Admin is already unassigned.";
        }
    }
    //if super-admin is selected
    if (in_array('Super-admin', $department_names)) {
        if (count($department_names) > 1) {
            return "Super-admin can't have any other departments.";
        }
    }
    $department_ids = [];
    foreach ($department_names as $department_name) {
        $department_ids[] = returnDepartmentId($conn, $department_name);
    }
    $delete_department = "DELETE FROM department_lists WHERE adminId = $admin_id";
    if (mysqli_query($conn, $delete_department)) {
        foreach ($department_ids as $department_id) {
            mysqli_query($conn, "INSERT INTO department_lists (adminId, departmentId) VALUES ($admin_id,{$department_id['departmentId']})");
        }
        return "Departments changed successfully";
    }
}
function changeTicketTypeDepartment($conn, $type_id, $type_name, $department_responsible)
{
    if (!departmentExists($conn, $department_responsible)) {
        return "Department doesn't exist";
    }
    $departmentId = returnDepartmentId($conn, $department_responsible)['departmentId'];
    $update_type = mysqli_query($conn, "UPDATE `ticket_types` SET ticketTypeName = '$type_name', departmentId = $departmentId WHERE ticketTypeId = $type_id");
    if ($update_type) {
        return "Ticket type updated successfully";
    } else {
        return "Error updating ticket type";
    }
}
function changeDepartments($conn, $dep_id, $ticket_types, $admins, $dep_name)
{
    if (!preg_match('/^(?:[a-zA-Z0-9\s]+\n?)+$/', $ticket_types)) {
        return "Each ticket type should be on a new line";
    }
    $ticket_types = explode("\n", $ticket_types);
    $ticket_types = array_filter($ticket_types);
    $ticket_types = array_map('trim', $ticket_types);
    $ticket_types = array_filter($ticket_types);
    foreach ($ticket_types as $ticket_type) {
        if (!ticketTypeExists($conn, $ticket_type)) {
            return "Ticket type $ticket_type doesn't exist.";
        }
    }
    if (!preg_match('/^(?:[a-zA-Z0-9\s@.]+\n?)+$/', $admins)) {
        return "Each admin email should be on a new line";
    }
    $admins = explode("\n", $admins);
    $admins = array_filter($admins);
    $admins = array_map('trim', $admins);
    $admins = array_filter($admins);
    foreach ($admins as $admin) {
        if (!adminExists($conn, $admin)) {
            return "Admin $admin doesn't exist.";
        }
    }
    $admin_ids = [];
    $delete_department = "DELETE FROM department_lists WHERE departmentId = $dep_id";
    if (mysqli_query($conn, $delete_department) && !empty($admins)) {
        foreach ($admins as $admin) {
            $admin_ids[] = returnAdminId($conn, $admin);
        }
        foreach ($admin_ids as $admin_id) {


            if ($dep_id != 0 && $dep_id != 3) {
                if (isAdminUnassigned($conn, $admin_id['adminId']))
                    mysqli_query($conn, "UPDATE department_lists SET departmentId = $dep_id WHERE adminId = {$admin_id['adminId']}");
                else
                    mysqli_query($conn, "INSERT INTO department_lists (adminId, departmentId) VALUES ({$admin_id['adminId']}, $dep_id)");
            } else {
                mysqli_query($conn, "DELETE FROM department_lists WHERE adminId = {$admin_id['adminId']}");
                mysqli_query($conn, "INSERT INTO department_lists (adminId, departmentId) VALUES ({$admin_id['adminId']}, $dep_id)");
            }
        }
    }
    $unassigned_admins = returnAllBackendUsers($conn);
    foreach ($unassigned_admins as $unassigned_admin) {
        if (!isAdminInDepartment($conn, $unassigned_admin['adminId'])) {
            mysqli_query($conn, "INSERT INTO department_lists (adminId, departmentId) VALUES ({$unassigned_admin['adminId']}, 0)");
        }
    }
    $ticket_types_all = returnTicketTypesForDepartmentName($conn, $dep_name);
    foreach ($ticket_types_all as $ticket_type) {
        if (!in_array($ticket_type['ticketTypeName'], $ticket_types)) {
            $update_tickets = "UPDATE ticket_types SET departmentId = 0 WHERE ticketTypeName = '{$ticket_type['ticketTypeName']}'";
            mysqli_query($conn, $update_tickets);
        }
    }
    if ($dep_id != 3) {
        foreach ($ticket_types as $ticket_type) {
            $update_tickets = "UPDATE ticket_types SET departmentId = $dep_id WHERE ticketTypeName = '$ticket_type'";
            mysqli_query($conn, $update_tickets);
        }
    }
    if (mysqli_affected_rows($conn) > 0) {
        return "Department has been changed successfully";
    }
    return "The department has not been changed";
}

function camelCaseToWords($str) {
    $str = strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $str));
    return ucfirst($str);
}

function php_search_all_database($conn, $search_keyword, $table_associative_array)
{
    $count = 0;

    if (mysqli_connect_errno()) {		// Check if database connection is ok
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if (count($table_associative_array) > 0) {
        foreach ($table_associative_array as $table_name => $columnn_name) {
            foreach ($columnn_name as $column) {
                $db_search_result_fields = $column . " LIKE ('%" . $search_keyword . "%')";
                $db_search_result = $conn->query("SELECT * FROM " . $table_name . " WHERE " . $db_search_result_fields);

                if ($db_search_result->num_rows > 0) {
                    while ($row = $db_search_result->fetch_array()) {

                        $columnName = camelCaseToWords($column);

                        echo '<div class="box">';
                            if (($table_name == 'admins') or ($table_name == 'users')) {
                                echo '<div class="breaking"><p> Page: <span><a href="users.php"> Users
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
                            echo '<div class="breaking"><p>Row ID: <span>' . $row[0] . "</span></p></div>";
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