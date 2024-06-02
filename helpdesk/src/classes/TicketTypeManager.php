<?php

class TicketTypeManager
{
    private $conn;
    private $Utility;
    private $UserManager;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->Utility = new Utility($conn);
        $this->UserManager = new UserManager($conn);
    }



    public function createTicketType($name, $departmentId)
    {
        $stmt = $this->conn->prepare("INSERT INTO ticket_types (ticketTypeName, departmentId) VALUES (?, ?)");
        $stmt->bind_param('si', $name, $departmentId);
        return $stmt->execute();
    }
    public function deleteTicketType($typeId)
    {
        // First, delete all messages related to conversations linked to tickets of the given ticket type
        $stmt = $this->conn->prepare("
            DELETE messages 
            FROM messages 
            JOIN conversation ON messages.conversationId = conversation.convoId
            JOIN tickets ON conversation.ticketId = tickets.ticketId
            WHERE tickets.ticketTypeId = ?
        ");
        $stmt->bind_param("i", $typeId);
        $deleteMessages = $stmt->execute();

        if ($deleteMessages) {
            // Then, delete all conversations linked to tickets of the given ticket type
            $stmt = $this->conn->prepare("
                DELETE conversation 
                FROM conversation
                JOIN tickets ON conversation.ticketId = tickets.ticketId
                WHERE tickets.ticketTypeId = ?
            ");
            $stmt->bind_param("i", $typeId);
            $deleteConversations = $stmt->execute();

            if ($deleteConversations) {
                // Next, delete all tickets of the given ticket type
                $stmt = $this->conn->prepare("DELETE FROM `tickets` WHERE ticketTypeId = ?");
                $stmt->bind_param("i", $typeId);
                $deleteTickets = $stmt->execute();

                if ($deleteTickets) {
                    // Finally, delete the ticket type itself
                    $stmt = $this->conn->prepare("DELETE FROM `ticket_types` WHERE ticketTypeId = ?");
                    $stmt->bind_param("i", $typeId);
                    return $stmt->execute();
                }
            }
        }
        return false;
    }


    public function changeTicketType($type_id, $type_name, $department_responsible)
    {
        if (!$this->Utility->departmentExists($department_responsible)) {
            return "Department doesn't exist";
        }
        $departmentId = $this->UserManager->returnDepartmentId($department_responsible)['departmentId'];
        $stmt = $this->conn->prepare("UPDATE ticket_types SET ticketTypeName = ?, departmentId = ? WHERE ticketTypeId = ?");
        $stmt->bind_param("sii", $type_name, $departmentId, $type_id);
        //if rows were affected
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $stmt->close();
            return "Ticket type updated successfully";
        } else {
            $stmt->close();
            return "Ticket type remains unchanged";
        }
    }
    public function getTicketTypes()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ticket_types");
        $stmt->execute();
        return $stmt->get_result();
    }
    function returnTicketTypeName($ticketTypeId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ticket_types WHERE ticketTypeId = ?");
        $stmt->bind_param("i", $ticketTypeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticketType = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $ticketType[0];
    }
}