<?php

class AuthManager
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db->getConnection();
    }

    public function authenticateUser($email, $password)
    {
        $hashedPassword = hash('sha256', $this->conn->real_escape_string($password));
        $email = $this->conn->real_escape_string($email);

        // Check in users table
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userPasswd = ? AND userEmail = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            return ['role' => 'user', 'data' => $user];
        }

        // Check in admins table
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE adminPasswd = ? AND adminEmail = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin) {
            return ['role' => 'admin', 'data' => $admin];
        }

        return null;
    }

    public function getAdminDepartments($adminId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM department_lists WHERE adminId = ?");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function getDepartmentName($departmentId)
    {
        $stmt = $this->conn->prepare("SELECT departmentName FROM departments WHERE departmentId = ?");
        $stmt->bind_param("i", $departmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['departmentName'];
    }
}