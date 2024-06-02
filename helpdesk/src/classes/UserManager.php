<?php
class UserManager
{
    private $conn;
    private $utility;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->utility = new Utility($db);
    }

    public function createAdmin($data)
    {
        $name = $data['createAdminName'];
        $surname = $data['createAdminSurname'];
        $email = $data['createAdminEmail'];
        $pass = hash('sha256', $data['createAdminPasswd']);

        if ($data['createAdminPasswd'] != $data['createAdminPasswdConf'])
            return 'Passwords don\'t match!';
        if (strlen($data['createAdminPasswd']) < 8)
            return 'Password needs at least 8 characters.';
        if (!preg_match('/[A-Z]/', $data['createAdminPasswd']))
            return 'Password needs at least 1 upper case character.';
        if (!preg_match('/\d/', $data['createAdminPasswd']))
            return 'Password needs at least 1 number.';
        if (!preg_match("/[^a-zA-Z0-9]/", $data['createAdminPasswd']))
            return 'Password needs at least 1 special character.';
        if ($this->utility->emailInDatabase($email))
            return 'Account with this email already exists.';

        foreach ($data['department'] as $department) {
            $departmentIds[] = $this->returnDepartmentId($department)['departmentId'];
            $departmentNames[] = $this->returnDepartmentName($this->returnDepartmentId($department)['departmentId']);
        }

        if (count($departmentIds) > 1) {
            if (in_array('Super-admin', $departmentNames))
                return 'You can\'t select multiple departments if you chose the "Super-admin" department.';
            if (in_array('Unassigned', $departmentNames))
                return 'You can\'t select multiple departments if you chose the "Unassigned" department.';
        }

        $stmt = $this->conn->prepare("
            INSERT INTO `admins` (adminName, adminSurname, adminEmail, adminPasswd) 
            VALUES (?, ?, ?, ?);
        ");
        $stmt->bind_param("ssss", $name, $surname, $email, $pass);

        if (!$stmt->execute()) {
            $stmt->close();
            return 'Query failed';
        }

        $createdAdminId = $this->returnAdminId($email)['adminId'];

        $stmt = $this->conn->prepare("
            INSERT INTO department_lists (departmentId, adminId) 
            VALUES (?, ?);
        ");

        foreach ($departmentIds as $departmentId) {
            $stmt->bind_param("ii", $departmentId, $createdAdminId);
            if (!$stmt->execute()) {
                $stmt->close();
                return 'Query failed';
            }
        }
        $stmt->close();
        return 'Admin was successfully created.';
    }
    function returnDepartments()
    {
        $stmt = $this->conn->prepare("SELECT * FROM departments");
        $stmt->execute();
        $result = $stmt->get_result();
        $departments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $departments;
    }
    public function returnDepartmentId($department)
    {
        $stmt = $this->conn->prepare("SELECT departmentId FROM `departments` WHERE departmentName = ?");
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $result = $stmt->get_result();
        $departmentId = $result->fetch_assoc();
        $stmt->close();
        return $departmentId;
    }

    public function returnDepartmentName($departmentId)
    {
        $stmt = $this->conn->prepare("SELECT departmentName FROM `departments` WHERE departmentId = ?");
        $stmt->bind_param("i", $departmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $departmentName = $result->fetch_assoc();
        $stmt->close();
        return $departmentName['departmentName'];
    }

    public function returnAdminId($email)
    {
        $stmt = $this->conn->prepare("SELECT adminId FROM `admins` WHERE adminEmail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $adminId = $result->fetch_assoc();
        $stmt->close();
        return $adminId;
    }
    public function getAdminDetails($adminId)
    {
        $query = 'SELECT adminName, adminSurname, adminEmail FROM admins WHERE adminId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        return $admin;
    }
    public function getUserDetails($userId)
    {
        $query = 'SELECT userName, userSurname, userEmail FROM users WHERE userId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        return $admin;
    }

    public function getUserConversations($userId)
    {
        $query = 'SELECT * FROM conversation WHERE userId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $conversations = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $conversations;
    }
    public function getAdminConversations($adminId)
    {
        $query = 'SELECT * FROM conversation WHERE adminId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $conversations = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $conversations;
    }
    public function getAllUsers()
    {
        $query = 'SELECT * FROM users';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $users;

    }

    public function getUserById($user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function getAdminById($admin_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE adminId = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function deleteUser($userId)
    {
        $queries = [
            "DELETE FROM `conversation` WHERE userId = ?",
            "DELETE FROM `tickets` WHERE userId = ?",
            "DELETE FROM `messages` WHERE senderUserId = ?",
            "DELETE FROM `users` WHERE userId = ?"
        ];
        foreach ($queries as $query) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            if ($stmt->affected_rows <= 0) {
                return false;
            }
        }
        return true;
    }

    public function deleteAdmin($adminId)
    {
        $queries = [
            "DELETE FROM `conversation` WHERE adminId = ?",
            "DELETE FROM `department_lists` WHERE adminId = ?",
            "DELETE FROM `messages` WHERE senderAdminId = ?",
            "UPDATE `tickets` SET `resolver` = NULL WHERE resolver = ?",
            "DELETE FROM `admins` WHERE adminId = ?"
        ];
        foreach ($queries as $query) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $adminId);
            $stmt->execute();
            if ($stmt->affected_rows <= 0) {
                return false;
            }
        }
        return true;
    }
    public function changeAdminDepartment($admin_id, $department_names)
    {
        if (!preg_match('/^(?:[a-zA-Z0-9\s]+\n?)+$/', $department_names)) {
            return "Each department should be on a new line.";
        }
        $department_names = explode("\n", $department_names);
        $department_names = array_filter($department_names);
        $department_names = array_map('trim', $department_names);
        $department_names = array_filter($department_names);
        //if there is no department of this name in the database
        foreach ($department_names as $department_name) {
            if (!$this->utility->departmentExists($department_name)) {
                return "Department $department_name doesn't exist.";
            }
        }
        //if admin is unassigned
        if (in_array('Unassigned', $department_names)) {
            if ($this->utility->isAdminUnassigned($admin_id)) {
                return "Admin is already unassigned.";
            }
        }
        //if super-admin is selected
        if (in_array('Super-admin', $department_names)) {
            if (count($department_names) > 1) {
                return "Super-admin can't have any other departments.";
            }
        }
        $department_ids = [];
        foreach ($department_names as $department_name) {
            $department_ids[] = $this->returnDepartmentId($department_name);
        }
        $stmt = $this->conn->prepare("DELETE FROM department_lists WHERE adminId = ?");
        $stmt->bind_param("i", $admin_id);
        if ($stmt->execute()) {
            $stmt->close();
            $stmt = $this->conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, ?)");
            foreach ($department_ids as $department_id) {
                $stmt->bind_param("ii", $admin_id, $department_id['departmentId']);
                $stmt->execute();
            }
            $stmt->close();
            return "Departments changed successfully";
        }
        $stmt->close();
    }
}
?>