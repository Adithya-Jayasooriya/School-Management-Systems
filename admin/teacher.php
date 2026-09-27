<?php
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/teacher.php";
include "data/subject.php";
include "data/grade.php";
include "data/class.php";
include "data/section.php";

$teachers = getAllTeachers($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Teachers</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="icon" href="../logo.png">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include "inc/navbar.php"; ?>

<div class="container mt-5">
    <a href="teacher-add.php" class="btn btn-dark">Add New Teacher</a>

    <form action="teacher-search.php" method="get" class="mt-3 n-table">
        <div class="input-group mb-3">
            <input type="text" name="searchKey" class="form-control" placeholder="Search...">
            <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
        </div>
    </form>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger mt-3 n-table"><?= $_GET['error'] ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-info mt-3 n-table"><?= $_GET['success'] ?></div>
    <?php endif; ?>

    <?php if ($teachers && count($teachers) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered mt-3 n-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Subject</th>
                    <th>Class</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $i => $teacher): ?>
                <tr>
                    <th><?= $i + 1 ?></th>
                    <td><?= $teacher['teacher_id'] ?? 'N/A' ?></td>
                    <td>
                        <a href="teacher-view.php?teacher_id=<?= $teacher['teacher_id'] ?? '' ?>">
                            <?= $teacher['fname'] ?? 'N/A' ?>
                        </a>
                    </td>
                    <td><?= $teacher['lname'] ?? 'N/A' ?></td>
                    <td><?= $teacher['username'] ?? 'N/A' ?></td>
                    <td>
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
                    </td>
                    <td>
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
                    </td>
                    <td>
                        <a href="teacher-edit.php?teacher_id=<?= $teacher['teacher_id'] ?? '' ?>" class="btn btn-warning">Edit</a>
                        <a href="teacher-delete.php?teacher_id=<?= $teacher['teacher_id'] ?? '' ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">No teachers found!</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $("#navLinks li:nth-child(2) a").addClass('active');
});
</script>

</body>
</html>