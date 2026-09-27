<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'Admin') {
    header("Location: teacher.php");
    exit;
}

include "../DB_connection.php";
include "data/teacher.php";
include "data/subject.php";
include "data/grade.php";
include "data/section.php";
include "data/class.php";

if (!isset($_GET['teacher_id'])) {
    header("Location: teacher.php");
    exit;
}

$teacher_id = $_GET['teacher_id'];
$teacher = getTeacherById($teacher_id, $conn);

if (!$teacher) {
    header("Location: teacher.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Details</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="icon" href="../logo.png">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include "inc/navbar.php"; ?>

<div class="container mt-5 d-flex justify-content-center">
    <div class="card" style="width: 22rem;">
        <img src="../img/teacher-<?= $teacher['gender'] ?? 'default' ?>.png" class="card-img-top" alt="Teacher Image">
        <div class="card-body text-center">
            <h5 class="card-title">@<?= $teacher['username'] ?? 'N/A' ?></h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">First name: <?= $teacher['fname'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Last name: <?= $teacher['lname'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Username: <?= $teacher['username'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Employee number: <?= $teacher['employee_number'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Address: <?= $teacher['address'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Date of birth: <?= $teacher['date_of_birth'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Phone number: <?= $teacher['phone_number'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Qualification: <?= $teacher['qualification'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Email address: <?= $teacher['email_address'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Gender: <?= $teacher['gender'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Date joined: <?= $teacher['date_of_joined'] ?? 'Not provided' ?></li>

            <li class="list-group-item">Subject: 
                <?php
                $subjects_str = '';
                if (!empty($teacher['subjects'])) {
                    $subjects = str_split(trim($teacher['subjects']));
                    foreach ($subjects as $subject) {
                        $s_temp = getSubjectById($subject, $conn);
                        if ($s_temp) $subjects_str .= $s_temp['subject_code'] . ', ';
                    }
                    $subjects_str = rtrim($subjects_str, ', ');
                }
                echo $subjects_str ?: 'N/A';
                ?>
            </li>

            <li class="list-group-item">Class: 
                <?php
                $classes_str = '';
                if (!empty($teacher['class'])) {
                    $classes = str_split(trim($teacher['class']));
                    foreach ($classes as $class_id) {
                        $class = getClassById($class_id, $conn);
                        if ($class) {
                            $g = getGradeById($class['grade'] ?? 0, $conn);
                            $s = getSectioById($class['section'] ?? 0, $conn);
                            if ($g && $s) {
                                $classes_str .= ($g['grade_code'] ?? 'N/A') . '-' . ($g['grade'] ?? 'N/A') . ($s['section'] ?? '') . ', ';
                            }
                        }
                    }
                    $classes_str = rtrim($classes_str, ', ');
                }
                echo $classes_str ?: 'N/A';
                ?>
            </li>
        </ul>
        <div class="card-body text-center">
            <a href="teacher.php" class="btn btn-primary">Go Back</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $("#navLinks li:nth-child(2) a").addClass('active');
});
</script>

</body>
</html>