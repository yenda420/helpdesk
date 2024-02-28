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

<section class="dashboard">
    <section class="tickets">
        <h1 class="title">Tickets</h1>
    </section>

    <form method="post">
        <select name="users" required>
            <option selected value="">--- Choose a user ---</option>
            <?php foreach ($users as $user) { ?>
                <option value="<?= $user["userId"] ?>"><?= $user["userName"] ?> <?= $user["userSurname"] ?> <?= $user["email"] ?></option>
            <?php } ?>
        </select>

        <input type="submit" value="Show tickets">
    </form>

    <?php if (empty($_POST["users"])) { ?>

        <div class="box-container">
            <?php 
                foreach ($tickets as $ticket) { 
                    $user = returnUserForSelectedTicket($conn, $ticket["ticketId"]);
            ?>
                    <p>ID: <span> <?= $ticket["ticketId"] ?></span></p>
                    <p>Title: <span> <?= $ticket["title"] ?></span></p>
                    <p>Users name: <span> <?= $user["userName"] ?></span></p>
                    <p>Users surname: <span> <?= $user["userSurname"] ?></span></p>
                    <p>Users email: <span> <?= $user["email"] ?></span></p>
                    <p>Ticket type: <span> <?= $ticket["ticketType"] ?></span></p>
                    <p>Description: <span> <?= $ticket["ticketDesc"] ?></span></p>
            <?php } ?>
        </div>

        <?php } else {
            if (isset($_POST["users"]) && $_POST["users"] != 0) { 
                $user = returnUser($conn, $_POST["users"]);

                if (numberOfTickets($conn, $_POST) != 0) { 
        ?>
                    <h1 class="title">Tickets for <?= $user["userName"] ?> <?= $user["userSurname"] ?></h1>
                    
                    <?php foreach ($tickets as $ticket) { ?>
                    <p>ID: <span> <?= $ticket["ticketId"] ?></span></p>
                    <p>Title: <span> <?= $ticket["title"] ?></span></p>
                    <p>Ticket type: <span> <?= $ticket["ticketType"] ?></span></p>
                    <p>Description: <span> <?= $ticket["ticketDesc"] ?></span></p>

                    <?php } 
                } else { ?>
                    <h1 class="title"><?= $user["userName"] ?> <?= $user["userSurname"] ?> currently has no tickets</h1>
                <?php }
            }
    } ?>
</section>

