<?php
require_once('../classes/database.php');

$con = new database();
if (isset($_POST['course'])) {
    $course = $_POST['course'];

    if ($con->isEmailExists($course)) {
        echo json_encode(['exists' => true]);
        
    }else{
        echo json_encode(['error' => 'invalid request']);
    }
}