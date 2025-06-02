<?php
session_start();
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if (isset($_POST['add_student'])) {
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $email = $_POST['email'] ?? '';
  $admin_id = $_SESSION['admin_id'];

  $admin_FN = isset($_SESSION['admin_FN']) ? $_SESSION['admin_FN'] : 'Admin';


  $db = $con->opencon();
  $userID = false;
  try {
    $stmt = $db->prepare("INSERT INTO students (student_FN, student_LN, student_email, admin_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$first_name, $last_name, $email, $admin_id])) {
      $userID = $db->lastInsertId();
    }
  } catch (Exception $e) {
    $userID = false;
  }

  if ($userID) {
    $sweetAlertConfig = "
          <script src='./package/dist/sweetalert2.all.min.js'></script>
          <script>
            Swal.fire({
              icon: 'success',
              title: 'Student Added',
              text: 'Welcome, " . addslashes(htmlspecialchars($admin_FN)) . "',
              confirmButtonText: 'Continue'
            }).then(() => {
              window.location.href = 'index.php';
            });
          </script>";
  } else {
    $sweetAlertConfig = "
          <script src='./package/dist/sweetalert2.all.min.js'></script>
          <script>
            Swal.fire({
              icon: 'error',
              title: 'Add Student Failed',
              text: 'Could not add student.'
            });
          </script>";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Add Student</title>
</head>

<body>
  <?php
  if (!empty($sweetAlertConfig)) {
    echo $sweetAlertConfig;
  }
  ?>
</body>

</html>