<?php
    session_start();

    require("config.php");
    require("functions.php");

    $users = returnAllUsers($conn);

    if (isset($_POST["users"])) {
        $tickets = empty($_POST["users"]) ? returtnAllTickets($conn) : returnTicketsForSelectedUser($conn, $_POST);
    } else {
        $tickets = returtnAllTickets($conn);
    }

    require("admin_header.php");
?>

<form method="post">
    <select name="users" required>
        <option value="">--- Choose a user ---</option>
        <?php foreach ($tickets as $ticket) { ?>
            <option value="<?= $tickets["ticketId"] ?>"><?= $tickets["title"] ?></option>
        <?php } ?>
    </select>

    <input type="submit" value="Show tickets">
</form>