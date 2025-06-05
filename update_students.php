<?php
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if (isset($_POST['save'])) {
    $student_id = $_POST['student_id'];
    $student_FN = $_POST['first_name'];
    $student_LN = $_POST['last_name'];
    $student_email = $_POST['email'];

    $updateSuccess = $con->updateStudent($student_FN, $student_LN, $student_email, $student_id);

    if ($updateSuccess) {
        $sweetAlertConfig = "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Updated',
                text: 'Student info updated successfully!'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    } else {
        $sweetAlertConfig = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'Something went wrong.'
            });
        </script>";
    }
    $student_data = $con->getStudentByID($student_id);

} elseif (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $student_data = $con->getStudentByID($student_id);
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./package/dist/sweetalert2.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4 text-center">Edit Student</h2>

        <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
            <input type="hidden" name="student_id" value="<?php echo $student_data['student_id']; ?>">

            <div class="mb-3">
                <label class="form-label">Student ID</label>
                <input type="text" value="<?php echo $student_data['student_id']; ?>" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" value="<?php echo $student_data['student_FN']; ?>" id="first_name"
                    class="form-control" placeholder="Enter your new First Name" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" value="<?php echo $student_data['student_LN']; ?>" id="last_name"
                    class="form-control" placeholder="Enter your new Last Name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" value="<?php echo $student_data['student_email']; ?>"
                    class="form-control" placeholder="Enter your new E-Mail" required>
            </div>

            <button type="submit" name="save" class="btn btn-primary w-100">Save</button>
        </form>
    </div>

    <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="./package/dist/sweetalert2.js"></script>
    <?php echo $sweetAlertConfig; ?>
</body>
</html>
