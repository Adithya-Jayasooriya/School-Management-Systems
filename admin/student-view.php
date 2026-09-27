<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'Admin') {
    header("Location: student.php");
    exit;
}

include "../DB_connection.php";
include "data/student.php";
include "data/subject.php";
include "data/grade.php";
include "data/section.php";

if (!isset($_GET['student_id'])) {
    header("Location: student.php");
    exit;
}

$student_id = $_GET['student_id'];
$student = getStudentById($student_id, $conn);

if (!$student) {
    header("Location: teacher.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
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
        <img src="../img/student-<?= $student['gender'] ?? 'default' ?>.png" class="card-img-top" alt="Student Image">
        <div class="card-body text-center">
            <h5 class="card-title">@<?= $student['username'] ?? 'N/A' ?></h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">First name: <?= $student['fname'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Last name: <?= $student['lname'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Username: <?= $student['username'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Address: <?= $student['address'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Date of birth: <?= $student['date_of_birth'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Email address: <?= $student['email_address'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Gender: <?= $student['gender'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Date joined: <?= $student['date_of_joined'] ?? 'Not provided' ?></li>

            <li class="list-group-item">Grade: 
                <?php 
                    $grade = $student['grade'] ?? 0;
                    $g = getGradeById($grade, $conn);
                    echo ($g['grade_code'] ?? 'N/A') . '-' . ($g['grade'] ?? 'N/A');
                ?>
            </li>
            <li class="list-group-item">Section: 
                <?php 
                    $section = $student['section'] ?? 0;
                    $s = getSectioById($section, $conn);
                    echo $s['section'] ?? 'N/A';
                ?>
            </li>

            <li class="list-group-item">Parent first name: <?= $student['parent_fname'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Parent last name: <?= $student['parent_lname'] ?? 'Not provided' ?></li>
            <li class="list-group-item">Parent phone number: <?= $student['parent_phone_number'] ?? 'Not provided' ?></li>
        </ul>
        <div class="card-body text-center">
            <a href="student.php" class="btn btn-primary">Go Back</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
         $("#navLinks li:nth-child(3) a").addClass('active');
    });
</script>

</body>
</html>