<?php
    function returtnAllTickets($conn) {
        $sql = "SELECT * FROM tickets";

        $sqlResult = mysqli_query($conn, $sql);
        return mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
    }

    function returnAllUsers($conn) {
        $sql = "SELECT * FROM users";

        $sqlResult = mysqli_query($conn, $sql);
        return mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
    }

    function returnTicketsForSelectedUser($conn, $data) {
        $sql = "
            SELECT *
            FROM users u, tickets t
            WHERE u.userId = t.userId
                AND u.userId = '{$data["userId"]}';
        ";

        $sqlResult = mysqli_query($conn, $sql);
        return mysqli_fetch_all($sqlResult, MYSQLI_ASSOC);
    }