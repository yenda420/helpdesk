<?php

class RequestManager
{
    private $conn;
    private $table_name = "requests";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function deleteRequest($request_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE requestId = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function acceptRequest($request_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE requestId = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $fetch_request = $result->fetch_assoc();
        $stmt->close();

        if ($fetch_request) {
            $req_name = $fetch_request['reqName'];
            $req_surname = $fetch_request['reqSurname'];
            $req_password = $fetch_request['reqPasswd'];
            $req_email = $fetch_request['reqEmail'];

            $stmt = $this->conn->prepare("INSERT INTO `users` (userName, userSurname, userEmail, userPasswd) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $req_name, $req_surname, $req_email, $req_password);
            $insert_user = $stmt->execute();
            $stmt->close();

            if ($insert_user) {
                return $this->deleteRequest($request_id);
            }
        }

        return false;
    }

    public function getAllRequests()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = [];

        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }

        $stmt->close();
        return $requests;
    }
}