<?php
require_once('../classes/database.php');
$con = new database();
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    if ($con->isUsernameExists($username)) {
        echo json_encode(['exists' => true]);
    }else{
        echo json_encode(['error' => 'invalid request']);
    }
}