<?php
    function returtnAllTickets($conn) {
        $sql = "SELECT * FROM tickets";

        $sqlResult = mysqli_query($conn, $sql);
        $tickets = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

        return $tickets;
    }

    function returnAllUsers($conn) {
        $sql = "SELECT * FROM users";

        $sqlResult = mysqli_query($conn, $sql);
        $users = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

        return $users;
    }

    function returnTicketsForSelectedUser($conn, $data) {
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

    function returnUserForSelectedTicket($conn, $ticketId) {
        $sql = "
            SELECT u.userName, u.userSurname, u.email
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND t.ticketId = $ticketId;
        ";
    
        $sqlResult = mysqli_query($conn, $sql);
        $user = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
    
        return $user[0];
    }
    
    function returnUser($conn, $userId) {
        $sql = "SELECT * FROM users WHERE userId = $userId";

        $sqlResult = mysqli_query($conn, $sql);
        $user = mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);

        return $user[0];
    }

    function numberOfTicketsForUser($conn, $data) {
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

    function numberOfTicketsForUserId($conn, $userId) {
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

    function numberOfTickets($conn) {
        $sql = "SELECT * FROM tickets;";

        $sqlResult = mysqli_query($conn, $sql);
        $numberOfRecords = mysqli_num_rows($sqlResult);

        return $numberOfRecords;
    }