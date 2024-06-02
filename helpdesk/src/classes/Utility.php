<?php

class Utility
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function emailInDatabase($email)
    {
        $sqlUsers = $this->conn->prepare("
            SELECT *
            FROM users
            WHERE userEmail = ?;
        ");

        $sqlRequests = $this->conn->prepare("
            SELECT *
            FROM requests
            WHERE reqEmail = ?;
        ");

        $sqlAdmins = $this->conn->prepare("
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
    public function departmentExists($name)
    {
        $stmt = $this->conn->prepare("SELECT departmentName FROM departments WHERE departmentName = ?");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function ticketTypeExists($name)
    {
        $stmt = $this->conn->prepare("SELECT ticketTypeName FROM ticket_types WHERE ticketTypeName = ?");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function isAdminUnassigned($adminId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM department_lists WHERE adminId = ? AND departmentId = 0;");
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

}