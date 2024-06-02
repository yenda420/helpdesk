<?php
class UserRequest
{
    private $conn;
    private $utility;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->utility = new Utility($db);
    }

    public function registerUser($data)
    {
        $name = $data['name'];
        $surname = $data['surname'];
        $email = $data['email'];
        $pass = hash('sha256', $data['password']);

        if ($data['password'] != $data['cpassword'])
            return 'Passwords don\'t match!';
        if (strlen($data['password']) < 8)
            return 'Password needs at least 8 characters.';
        if (!preg_match('/[A-Z]/', $data['password']))
            return 'Password needs at least 1 upper case character.';
        if (!preg_match('/\d/', $data['password']))
            return 'Password needs at least 1 number.';
        if (!preg_match("/[^a-zA-Z0-9]/", $data['password']))
            return 'Password needs at least 1 special character.';
        if ($this->utility->emailInDatabase($email))
            return 'Account with this email already exists.';

        $stmt = $this->conn->prepare("
            INSERT INTO `requests` 
            SET reqName=?, reqSurname=?, reqEmail=?, reqPasswd=?
        ");
        $stmt->bind_param("ssss", $name, $surname, $email, $pass);

        if (!$stmt->execute()) {
            $stmt->close();
            return 'Query failed.';
        }

        $stmt->close();
        return 'Request for an account was successful.';
    }   
}
?>