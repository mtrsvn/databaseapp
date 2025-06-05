<?php
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if (isset($_POST['save'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['coursename'];

    $updateSuccess = $con->updateCourse($course_name, $course_id);

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
    $course_data = $con->getCourseByID($course_id);

} elseif (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $course_data = $con->getCourseByID($course_id);
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
        <h2 class="mb-4 text-center">Edit Course</h2>

        <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
           <div class="mb-3">
        <label for="course_id" class="form-label">Course Name</label>
        <input type="text" name="cid"  value="<?php echo $course_data['course_id']; ?>" class="form-control" 
         disabled required>
       </div>

            <div class="mb-3">
                <label for="coursename" class="form-label">Course</label>
                <input type="text" name="coursename" value="<?php echo $course_data['course_name']; ?>" id="coursename"
                    class="form-control" placeholder="Enter Course" required>
            </div>
  <input type="hidden" name="course_id" value="<?php echo $course_data['course_id'] ?>">
            <button type="submit" name="save" class="btn btn-primary w-100">Save</button>
        </form>
    </div>

    <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
    <script src="./package/dist/sweetalert2.js"></script>
    <?php echo $sweetAlertConfig; ?>
</body>
</html>
