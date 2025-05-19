<?php

class Database {

    private $host = 'localhost';
    private $dbname = 'dbs_app';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Open and return the PDO connection
    public function opencon() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }

    // Sign up a new admin user
    public function signupUser($username, $email, $password, $firstname, $lastname) {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmt = $con->prepare("
                INSERT INTO Admin (admin_FN, admin_LN, admin_username, userEmail, admin_password) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$firstname, $lastname, $username, $email, $password]);

            $userId = $con->lastInsertId();
            $con->commit();

            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            error_log("Signup error: " . $e->getMessage());
            return false;
        }
    }

    // Check if username already exists
    public function isUsernameExists($username) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM Admin WHERE admin_username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
