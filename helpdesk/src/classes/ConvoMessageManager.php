<?php

class ConvoMessageManager
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function updateUserReplied($messageId)
    {
        $query = 'UPDATE messages SET userReplied = 1 WHERE msgId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $messageId);
        $stmt->execute();


        return $stmt->affected_rows > 0;
    }
    public function updateAdminReplied($messageId)
    {
        $query = 'UPDATE messages SET adminReplied = 1 WHERE msgId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $messageId);
        $stmt->execute();


        return $stmt->affected_rows > 0;
    }

    public function insertMessage($conversationId, $messageContent, $userId)
    {
        $query = 'INSERT INTO messages (conversationId, msgContent, senderUserId) VALUES (?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isi", $conversationId, $messageContent, $userId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
    public function insertMessageAdmin($conversationId, $messageContent, $adminId)
    {
        $query = 'INSERT INTO messages (conversationId, msgContent, senderAdminId) VALUES (?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isi", $conversationId, $messageContent, $adminId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function getMessagesFromAdmin($conversationId)
    {
        $query = 'SELECT * FROM messages WHERE conversationId = ? AND senderAdminId IS NOT NULL';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $conversationId);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }
    public function getMessagesFromUser($conversationId)
    {
        $query = 'SELECT * FROM messages WHERE conversationId = ? AND senderUserId IS NOT NULL';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $conversationId);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $messages;
    }
}