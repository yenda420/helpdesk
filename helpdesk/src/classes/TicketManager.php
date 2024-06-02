<?php

class TicketManager
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTicketsByUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE userId = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function numberOfTicketsForUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as ticketCount FROM tickets WHERE userId = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['ticketCount'];
    }

    public function getTicketTypeName($ticketTypeId)
    {
        $stmt = $this->conn->prepare("SELECT ticketTypeName FROM ticket_types WHERE ticketTypeId = ?");
        $stmt->bind_param('i', $ticketTypeId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function markAsResolved($adminId, $ticketId)
    {
        $query = 'UPDATE tickets SET status = "Resolved", resolver = ? WHERE ticketId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $adminId, $ticketId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
    public function getTicketTitle($ticketId)
    {
        $query = 'SELECT title FROM tickets WHERE ticketId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ticketId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticket = $result->fetch_assoc();
        $stmt->close();

        return $ticket['title'];
    }
    public function sendTicket($title, $type, $description, $date, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO `tickets` (`title`, `status`, `ticketDesc`, `ticketDate`, `userId`, `ticketTypeId`) VALUES (?, 'Waiting', ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $description, $date, $userId, $type);
        return $stmt->execute();
    }
    public function numberOfTickets()
    {
        $stmt = $this->conn->prepare("SELECT * FROM tickets");
        $stmt->execute();
        $result = $stmt->get_result();
        $numberOfRecords = $result->num_rows;
        $stmt->close();
        return $numberOfRecords;
    }
    function returnUserForSelectedTicket($ticketId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users u, tickets t WHERE u.userId = t.userId AND t.ticketId = ?");
        $stmt->bind_param("i", $ticketId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user[0];
    }
}