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
            FROM admins inner join department_lists using (adminId)
            WHERE departmentId = $departmentId;
        ";

    $sqlResult = mysqli_query($conn, $sql);
    $admins = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

    return $admins;
}

function returnAllUsers($conn)
{
    $sql = "SELECT * FROM admins, users";

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