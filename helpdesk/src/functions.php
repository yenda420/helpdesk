<?php
function returnDepartments($conn)
{
    $stmt = $conn->prepare("SELECT * FROM departments");
    $stmt->execute();
    $result = $stmt->get_result();
    $departments = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $departments;
}

function returnDepartmentName($conn, $departmentId)
{
    $stmt = $conn->prepare("SELECT * FROM departments WHERE departmentId = ?");
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $department = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $department[0]['departmentName'];
}

function returnDepartmentId($conn, $departmentName)
{
    $stmt = $conn->prepare("SELECT * FROM departments WHERE departmentName = ?");
    $stmt->bind_param("s", $departmentName);
    $stmt->execute();
    $result = $stmt->get_result();
    $department = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $department[0];
}
function departmentExists($conn, $departmentName)
{
    $stmt = $conn->prepare("SELECT * FROM departments WHERE departmentName = ?");
    $stmt->bind_param("s", $departmentName);
    $stmt->execute();
    $result = $stmt->get_result();
    $department = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    if ($department) {
        return 1;
    } else {
        return 0;
    }
}
function adminExists($conn, $adminEmail)
{
    $stmt = $conn->prepare("SELECT * FROM admins WHERE adminEmail = ?");
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    if ($admin) {
        return 1;
    } else {
        return 0;
    }
}
function ticketTypeExists($conn, $ticketTypeName)
{
    $stmt = $conn->prepare("SELECT * FROM ticket_types WHERE ticketTypeName = ?");
    $stmt->bind_param("s", $ticketTypeName);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticketType = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    if ($ticketType) {
        return 1;
    } else {
        return 0;
    }
}

function returnAdminId($conn, $adminEmail)
{
    $stmt = $conn->prepare("SELECT * FROM admins WHERE adminEmail = ?");
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $admin[0];
}

function returnAllFrontendUsers($conn)
{
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $users;
}

function returnAllBackendUsers($conn)
{
    $stmt = $conn->prepare("SELECT * FROM admins");
    $stmt->execute();
    $result = $stmt->get_result();
    $admins = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $admins;
}

function returnAllBackendsForDepartmentId($conn, $departmentId)
{
    $stmt = $conn->prepare("SELECT * FROM admins INNER JOIN department_lists USING (adminId) WHERE departmentId = ?");
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $admins = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $admins;
}

function returnAllUsers($conn)
{
    $stmt = $conn->prepare("SELECT * FROM admins UNION DISTINCT SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $users;
}

function returnTicketsForSelectedUser($conn, $data)
{
    $stmt = $conn->prepare("SELECT * FROM users u, tickets t WHERE u.userId = t.userId AND u.userId = ?");
    $stmt->bind_param("i", $data['users']);
    $stmt->execute();
    $result = $stmt->get_result();
    $tickets = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $tickets;
}

function returnUserForSelectedTicket($conn, $ticketId)
{
    $stmt = $conn->prepare("SELECT * FROM users u, tickets t WHERE u.userId = t.userId AND t.ticketId = ?");
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $user[0];
}

function returnTicketTypesForDepartmentName($conn, $departmentName)
{
    if ($departmentName == 'Super-admin') {
        $stmt = $conn->prepare("SELECT ticketTypeName, ticketTypeId FROM ticket_types");
    } else {
        $stmt = $conn->prepare("
                SELECT ticketTypeName, ticketTypeId
                FROM ticket_types INNER JOIN departments USING (departmentId)
                WHERE departmentName = ?;
            ");
        $stmt->bind_param("s", $departmentName);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $ticketTypes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $ticketTypes;
}

function returnUser($conn, $userId)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $user[0];
}

function returnAdmin($conn, $adminId)
{
    $stmt = $conn->prepare("SELECT * FROM admins WHERE adminId = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $admin[0];
}

function returnTicket($conn, $ticketId)
{
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE ticketId = ?");
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $ticket[0];
}

function numberOfTicketsForUser($conn, $data)
{
    $stmt = $conn->prepare("SELECT * FROM users u, tickets t WHERE u.userId = t.userId AND u.userId = ?");
    $stmt->bind_param("i", $data['users']);
    $stmt->execute();
    $result = $stmt->get_result();
    $numberOfRecords = $result->num_rows;
    $stmt->close();
    return $numberOfRecords;
}

function numberOfTicketsForUserId($conn, $userId)
{
    $stmt = $conn->prepare("
            SELECT *
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND u.userId = ?;
        ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $numberOfRecords = $result->num_rows;
    $stmt->close();
    return $numberOfRecords;
}

function returnTicketTypeName($conn, $ticketTypeId)
{
    $stmt = $conn->prepare("SELECT * FROM ticket_types WHERE ticketTypeId = ?");
    $stmt->bind_param("i", $ticketTypeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticketType = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $ticketType[0];
}

function returnTicketTypes($conn)
{
    $stmt = $conn->prepare("SELECT * FROM ticket_types");
    $stmt->execute();
    $result = $stmt->get_result();
    $ticketTypes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $ticketTypes;
}

function numberOfTickets($conn)
{
    $stmt = $conn->prepare("SELECT * FROM tickets");
    $stmt->execute();
    $result = $stmt->get_result();
    $numberOfRecords = $result->num_rows;
    $stmt->close();
    return $numberOfRecords;
}

function emailInDatabase($conn, $email)
{

    $sqlUsers = $conn->prepare("
            SELECT *
            FROM users
            WHERE userEmail = ?;
        ");

    $sqlRequests = $conn->prepare("
            SELECT *
            FROM requests
            WHERE reqEmail = ?;
        ");

    $sqlAdmins = $conn->prepare("
            SELECT *
            FROM admins
            WHERE adminEmail = ?;
        ");

    $sqlUsers->bind_param("s", $email);
    $sqlUsers->execute();
    $resultUsers = $sqlUsers->get_result();
    $sqlRequests->bind_param("s", $email);
    $sqlRequests->execute();
    $resultRequests = $sqlRequests->get_result();
    $sqlAdmins->bind_param("s", $email);
    $sqlAdmins->execute();
    $resultAdmins = $sqlAdmins->get_result();
    $numberOfRecordsUsers = $resultUsers->num_rows;
    $numberOfRecordsRequests = $resultRequests->num_rows;
    $numberOfRecordsAdmins = $resultAdmins->num_rows;
    $sqlUsers->close();
    $sqlRequests->close();
    $sqlAdmins->close();
    if ($numberOfRecordsUsers > 0 || $numberOfRecordsRequests > 0 || $numberOfRecordsAdmins > 0) {
        return 1;
    } else {
        return 0;
    }

}

function registerUser($conn, $data)
{
    $name = $data['name'];
    $surname = $data['surname'];
    $email = $data['email'];
    $pass = hash('sha256', $data['password']);

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

    $stmt = $conn->prepare("
        INSERT INTO `requests` 
        SET reqName=?, reqSurname=?, reqEmail=?, reqPasswd=?
    ");
    $stmt->bind_param("ssss", $name, $surname, $email, $pass);

    if (!$stmt->execute()) {
        $stmt->close();
        return 'Query failed.';
    }

    $stmt->close();
    return 'Request for an account was successful.';
}

function createAdmin($conn, $data)
{
    $name = $data['createAdminName'];
    $surname = $data['createAdminSurname'];
    $email = $data['createAdminEmail'];
    $pass = hash('sha256', $data['createAdminPasswd']);

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
        $departmentIds[] = returnDepartmentId($conn, $department)['departmentId'];
        $departmentNames[] = returnDepartmentName($conn, returnDepartmentId($conn, $department)['departmentId']);
    }

    if (count($departmentIds) > 1) {
        if (in_array('Super-admin', $departmentNames))
            return 'You can\'t select multiple departments if you chose the "Super-admin" department.';
        if (in_array('Unassigned', $departmentNames))
            return 'You can\'t select multiple departments if you chose the "Unassigned" department.';
    }

    $stmt = $conn->prepare("
        INSERT INTO `admins` (adminName, adminSurname, adminEmail, adminPasswd) 
        VALUES (?, ?, ?, ?);
    ");
    $stmt->bind_param("ssss", $name, $surname, $email, $pass);

    if (!$stmt->execute()) {
        $stmt->close();
        return 'Query failed';
    }

    $createdAdminId = returnAdminId($conn, $email)['adminId'];

    $stmt = $conn->prepare("
        INSERT INTO department_lists (departmentId, adminId) 
        VALUES (?, ?);
    ");

    foreach ($departmentIds as $departmentId) {
        $stmt->bind_param("ii", $departmentId, $createdAdminId);
        if (!$stmt->execute()) {
            $stmt->close();
            return 'Query failed';
        }
    }
    $stmt->close();
    return 'Admin was successfully created.';
}

function deleteDepartment($conn, $departmentId)
{
    $stmt1 = $conn->prepare("DELETE FROM department_lists WHERE departmentId = ?");
    $stmt1->bind_param("i", $departmentId);
    if ($stmt1->execute()) {
        $stmt1->close();
        $stmt2 = $conn->prepare("UPDATE ticket_types SET departmentId = 0 WHERE departmentId = ?");
        $stmt2->bind_param("i", $departmentId);
        if ($stmt2->execute()) {
            $stmt2->close();
            $stmt3 = $conn->prepare("DELETE FROM departments WHERE departmentId = ?");
            $stmt3->bind_param("i", $departmentId);
            if ($stmt3->execute()) {
                $stmt3->close();
                return 1;
            } else {
                $stmt3->close();
                return 0;
            }
        } else {
            $stmt2->close();
            return 0;
        }
    } else {
        $stmt1->close();
        return 0;
    }
}
function isAdminUnassigned($conn, $adminId)
{
    $stmt = $conn->prepare("SELECT * FROM department_lists WHERE adminId = ? AND departmentId = 0;");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $numberOfRecords = $result->num_rows;
    $stmt->close();
    if ($numberOfRecords == 0) {
        return 0;
    } else {
        return 1;
    }
}
function isAdminInDepartment($conn, $adminId)
{
    $stmt = $conn->prepare("SELECT * FROM department_lists WHERE adminId = ?;");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $numberOfRecords = $result->num_rows;
    $stmt->close();
    if ($numberOfRecords == 0) {
        return 0;
    } else {
        return 1;
    }

}
function changeAdminDepartment($conn, $admin_id, $department_names)
{
    if (!preg_match('/^(?:[a-zA-Z0-9\s]+\n?)+$/', $department_names)) {
        return "Each department should be on a new line.";
    }
    $department_names = explode("\n", $department_names);
    $department_names = array_filter($department_names);
    $department_names = array_map('trim', $department_names);
    $department_names = array_filter($department_names);
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
    $stmt = $conn->prepare("DELETE FROM department_lists WHERE adminId = ?");
    $stmt->bind_param("i", $admin_id);
    if ($stmt->execute()) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, ?)");
        foreach ($department_ids as $department_id) {
            $stmt->bind_param("ii", $admin_id, $department_id['departmentId']);
            $stmt->execute();
        }
        $stmt->close();
        return "Departments changed successfully";
    }
    $stmt->close();
}
function changeTicketTypeDepartment($conn, $type_id, $type_name, $department_responsible)
{
    if (!departmentExists($conn, $department_responsible)) {
        return "Department doesn't exist";
    }
    $departmentId = returnDepartmentId($conn, $department_responsible)['departmentId'];
    $stmt = $conn->prepare("UPDATE ticket_types SET ticketTypeName = ?, departmentId = ? WHERE ticketTypeId = ?");
    $stmt->bind_param("sii", $type_name, $departmentId, $type_id);
    if ($stmt->execute()) {
        $stmt->close();
        return "Department changed successfully";
    } else {
        $stmt->close();
        return "Department has not been changed";
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
    $stmt = $conn->prepare("DELETE FROM department_lists WHERE departmentId = ?");
    $stmt->bind_param("i", $dep_id);
    if ($stmt->execute() && !empty($admins)) {
        $stmt->close();
        foreach ($admins as $admin) {
            $admin_ids[] = returnAdminId($conn, $admin);
        }
        $stmt1 = $conn->prepare("UPDATE department_lists SET departmentId = ? WHERE adminId = ?");
        $stmt2 = $conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, ?)");
        $stmt3 = $conn->prepare("DELETE FROM department_lists WHERE adminId = ?");
        $stmt4 = $conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, ?)");
        foreach ($admin_ids as $admin_id) {
            if ($dep_id != 0 && $dep_id != 3) {
                if (isAdminUnassigned($conn, $admin_id['adminId'])) {
                    $stmt1->bind_param("ii", $dep_id, $admin_id['adminId']);
                    $stmt1->execute();
                } else {
                    $stmt2->bind_param("ii", $admin_id['adminId'], $dep_id);
                    $stmt2->execute();
                }
            } else {
                $stmt3->bind_param("i", $admin_id['adminId']);
                $stmt3->execute();
                $stmt4->bind_param("ii", $admin_id['adminId'], $dep_id);
                $stmt4->execute();
            }
        }
    }
    //$stmt->close();
    $unassigned_admins = returnAllBackendUsers($conn);
    $stmt5 = $conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, 0)");
    foreach ($unassigned_admins as $unassigned_admin) {
        if (!isAdminInDepartment($conn, $unassigned_admin['adminId'])) {
            $stmt5->bind_param("i", $unassigned_admin['adminId']);
            $stmt5->execute();
        }
    }
    $ticket_types_all = returnTicketTypesForDepartmentName($conn, $dep_name);
    $stmt6 = $conn->prepare("UPDATE ticket_types SET departmentId = 0 WHERE ticketTypeName = ?");
    foreach ($ticket_types_all as $ticket_type) {
        if (!in_array($ticket_type['ticketTypeName'], $ticket_types)) {
            $stmt6->bind_param("s", $ticket_type['ticketTypeName']);
            $stmt6->execute();
        }
    }
    if ($dep_id != 3) {
        $stmt7 = $conn->prepare("UPDATE ticket_types SET departmentId = ? WHERE ticketTypeName = ?");
        foreach ($ticket_types as $ticket_type) {
            $stmt7->bind_param("is", $dep_id, $ticket_type);
            $stmt7->execute();
        }
    }
    if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0 || $stmt3->affected_rows > 0 || $stmt4->affected_rows > 0 || $stmt5->affected_rows > 0 || $stmt6->affected_rows > 0 || $stmt7->affected_rows > 0) {
        return "Departments changed successfully";
    } else {
        return "No changes were made";
    }
}
function camelCaseToWords($str)
{
    $str = strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', " $1", $str));
    return ucfirst($str);
}

function php_search_all_database($conn, $search_keyword, $table_associative_array)
{
    $count = 0;

    if (mysqli_connect_errno()) {        // Check if database connection is ok
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if (count($table_associative_array) > 0) {
        foreach ($table_associative_array as $table_name => $columnn_name) {
            foreach ($columnn_name as $column) {
                $stmt = $conn->prepare("SELECT * FROM " . $table_name . " WHERE " . $column . " LIKE ?");
                $param = '%' . $search_keyword . '%';
                $stmt->bind_param("s", $param);
                $stmt->execute();
                $db_search_result = $stmt->get_result();

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
                $stmt->close();
            }
        }
    }

    if ($count >= 24) {
        return 0;
    } else {
        return 1;
    }
}

function resultsFound($conn, $search_keyword, $table_associative_array)
{
    $count = 0;

    if (mysqli_connect_errno()) {        // Check if database connection is ok
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if (count($table_associative_array) > 0) {
        foreach ($table_associative_array as $table_name => $columnn_name) {
            foreach ($columnn_name as $column) {
                $stmt = $conn->prepare("SELECT * FROM " . $table_name . " WHERE " . $column . " LIKE ?");
                $param = '%' . $search_keyword . '%';
                $stmt->bind_param("s", $param);
                $stmt->execute();
                $db_search_result = $stmt->get_result();

                if ($db_search_result->num_rows > 0) {
                    $stmt->close();
                    return true;
                } else {
                    if ($db_search_result->num_rows == 0) {
                        $count++;
                    }
                }
                $stmt->close();
            }
        }
    }

    if ($count >= 24) {
        return false;
    } else {
        return true;
    }
}

function returnConvos($conn, $userId) {
    $query = "
        SELECT * 
        FROM conversation 
        WHERE convoId IS NOT NULL 
            AND userId = ?;
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $convos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $convos;
}

function returnMessagesFromAdmin($conn, $convoId) {
    $query = "
        SELECT * 
        FROM messages 
        WHERE senderAdminId IS NOT NULL
            AND conversationId = ?;
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $convoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $messages;
}