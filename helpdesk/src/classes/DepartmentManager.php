<?php

class DepartmentManager
{
    private $conn;
    public $Utility;
    private $UserManager;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->Utility = new Utility($db);
        $this->UserManager = new UserManager($db);
    }

    public function setTicketTypeManager($ticketTypeManager)
    {
        $this->ticketTypeManager = $ticketTypeManager;
    }
    public function createDepartment($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO departments (departmentName) VALUES (?)");
        $stmt->bind_param('s', $name);
        return $stmt->execute();
    }
    public function getDepartmentId($name)
    {
        $stmt = $this->conn->prepare("SELECT departmentId FROM departments WHERE departmentName = ?");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function deleteDepartment($dep_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM departments WHERE departmentId = ?");
        $stmt->bind_param("i", $dep_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    public function changeDepartments($dep_id, $ticket_types, $admins, $dep_name)
    {
        if (!preg_match('/^(?:[a-zA-Z0-9\s]+\n?)+$/', $ticket_types)) {
            return "Each ticket type should be on a new line";
        }
        $ticket_types = explode("\n", $ticket_types);
        $ticket_types = array_filter($ticket_types);
        $ticket_types = array_map('trim', $ticket_types);
        $ticket_types = array_filter($ticket_types);
        foreach ($ticket_types as $ticket_type) {
            if (!$this->Utility->ticketTypeExists($ticket_type)) {
                return "Ticket type $ticket_type doesn't exist.";
            }
        }
        if (!preg_match('/^(?:[a-zA-Z0-9\s@.]+\n?)+$/', $admins)) {
            return "Each admin email should be on a new line";
        }
        $admins = explode("\n", $admins);
        $admins = array_filter($admins);
        $admins = array_map('trim', $admins);
        $admins = array_filter($admins);
        foreach ($admins as $admin) {
            if (!$this->adminExists($admin)) {
                return "Admin $admin doesn't exist.";
            }
        }
        $admin_ids = [];
        $stmt = $this->conn->prepare("DELETE FROM department_lists WHERE departmentId = ?");
        $stmt->bind_param("i", $dep_id);
        if ($stmt->execute() && !empty($admins)) {
            $stmt->close();
            foreach ($admins as $admin) {
                $admin_ids[] = $this->UserManager->returnAdminId($admin);
            }
            $stmt1 = $this->conn->prepare("UPDATE department_lists SET departmentId = ? WHERE adminId = ?");
            $stmt2 = $this->conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, ?)");
            $stmt3 = $this->conn->prepare("DELETE FROM department_lists WHERE adminId = ?");
            $stmt4 = $this->conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, ?)");
            foreach ($admin_ids as $admin_id) {
                if ($dep_id != 0 && $dep_id != 3) {
                    if ($this->Utility->isAdminUnassigned($admin_id['adminId'])) {
                        $stmt1->bind_param("ii", $dep_id, $admin_id['adminId']);
                        $stmt1->execute();
                    } else {
                        $stmt2->bind_param("ii", $admin_id['adminId'], $dep_id);
                        $stmt2->execute();
                    }
                } else {
                    $stmt3->bind_param("i", $admin_id['adminId']);
                    $stmt3->execute();
                    $stmt4->bind_param("ii", $admin_id['adminId'], $dep_id);
                    $stmt4->execute();
                }
            }
        }
        //$stmt->close();
        $unassigned_admins = $this->returnAllBackendUsers();
        $stmt5 = $this->conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, 0)");
        foreach ($unassigned_admins as $unassigned_admin) {
            if (!$this->isAdminInDepartment($unassigned_admin['adminId'])) {
                $stmt5->bind_param("i", $unassigned_admin['adminId']);
                $stmt5->execute();
            }
        }
        $ticket_types_all = $this->returnTicketTypesForDepartmentName($dep_name);
        $stmt6 = $this->conn->prepare("UPDATE ticket_types SET departmentId = 0 WHERE ticketTypeName = ?");
        foreach ($ticket_types_all as $ticket_type) {
            if (!in_array($ticket_type['ticketTypeName'], $ticket_types)) {
                $stmt6->bind_param("s", $ticket_type['ticketTypeName']);
                $stmt6->execute();
            }
        }
        if ($dep_id != 3) {
            $stmt7 = $this->conn->prepare("UPDATE ticket_types SET departmentId = ? WHERE ticketTypeName = ?");
            foreach ($ticket_types as $ticket_type) {
                $stmt7->bind_param("is", $dep_id, $ticket_type);
                $stmt7->execute();
            }
        }
        if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0 || $stmt3->affected_rows > 0 || $stmt4->affected_rows > 0 || $stmt5->affected_rows > 0 || $stmt6->affected_rows > 0 || $stmt7->affected_rows > 0) {
            return "Departments changed successfully";
        } else {
            return "No changes were made";
        }
    }

    public function returnAllBackendUsers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM admins");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }

    public function isAdminInDepartment($adminId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM department_lists WHERE adminId = ?");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;
        $stmt->close();
        return $numRows > 0;
    }
    public function adminExists($adminEmail)
    {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE adminEmail = ?");
        $stmt->bind_param("s", $adminEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        if ($admin) {
            return 1;
        } else {
            return 0;
        }
    }
    public function assignAdminToUnassigned($adminId)
    {
        $stmt = $this->conn->prepare("INSERT INTO department_lists (adminId, departmentId) VALUES (?, 0)");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $stmt->close();
    }

    public function returnTicketTypesForDepartmentName($departmentName)
    {
        if ($departmentName == 'Super-admin') {
            $stmt = $this->conn->prepare("SELECT ticketTypeName, ticketTypeId FROM ticket_types");
        } else {
            $stmt = $this->conn->prepare("
                SELECT ticketTypeName, ticketTypeId
                FROM ticket_types INNER JOIN departments USING (departmentId)
                WHERE departmentName = ?;
            ");
            $stmt->bind_param("s", $departmentName);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $ticketTypes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $ticketTypes;
    }
    public function returnAllBackendsForDepartmentId($departmentId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM admins INNER JOIN department_lists USING (adminId) WHERE departmentId = ?");
        $stmt->bind_param("i", $departmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admins = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $admins;
    }
}
?>