<?php

class SessionManager {
    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function destroySession() {
        session_start();
        session_unset();
        session_destroy();
    }

    public function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function getSession($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    public function getSessionArray($key) {
        return $_SESSION[$key] ?? [];
    }
    public function addToSessionArray($key, $value) {
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        $_SESSION[$key][] = $value;
    }
    public function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    } public function getUserSurname() {
        return $_SESSION['user_surname'] ?? null;
    }
    public function getUserEmail() {
        return $_SESSION['user_email'] ?? null;
    }
    public function getAdminId() {
        return $_SESSION['admin_id'] ?? null;
    }

    public function getAdminName() {
        return $_SESSION['admin_name'] ?? null;
    }

    public function getAdminEmail() {
        return $_SESSION['admin_email'] ?? null;
    }

    public function getDepartments() {
        return $_SESSION['department'] ?? [];
    }

    public function getMessage() {
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
            return $message;
        }
        return null;
    }

    public function setMessage($message) {
        $_SESSION['message'] = $message;
    }

    public function isSuperAdmin() {
        $departments = $this->getDepartments();
        return !empty($departments) && $departments[0] == 'Super-admin';
    }
}
?>
