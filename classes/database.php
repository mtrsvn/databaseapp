<?php

class database {

    function opencon() {
        return new PDO(
            'mysql:host=localhost;dbname=dbs_app',
            'root',
            ''
        );
    }

    function signupUser($username, $password, $firstname, $lastname) {
        $con = $this->opencon();

        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO Admin (admin_FN, admin_LN, admin_username, admin_password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $username, $password]);

            $userId = $con->lastInsertId();
            $con->commit();

            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
}
