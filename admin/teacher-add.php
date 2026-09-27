<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

include "../DB_connection.php";
include "data/subject.php";
include "data/class.php";
include "data/grade.php";
include "data/section.php";

// Fetch all subjects and classes for the form
$subjects = getAllSubjects($conn);
$classes  = getAllClasses($conn);

// Preserve old input values if redirected back
$old = [
    'fname' => $_GET['fname'] ?? '',
    'lname' => $_GET['lname'] ?? '',
    'username' => $_GET['uname'] ?? '',
    'address' => $_GET['address'] ?? '',
    'employee_number' => $_GET['en'] ?? '',
    'phone_number' => $_GET['pn'] ?? '',
    'qualification' => $_GET['qf'] ?? '',
    'email_address' => $_GET['email'] ?? ''
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Add Teacher</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="icon" href="../logo.png">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include "inc/navbar.php"; ?>

<div class="container mt-5">
    <a href="teacher.php" class="btn btn-dark">Go Back</a>

    <form method="post" action="req/teacher-add.php" class="shadow p-4 mt-4 form-w">
        <h3>Add New Teacher</h3>
        <hr>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <!-- Basic info -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($old['fname']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($old['lname']) ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($old['username']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="pass" id="passInput" required>
                <button class="btn btn-secondary" id="gBtn">Random</button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($old['address']) ?>">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee Number</label>
                <input type="text" class="form-control" name="employee_number" value="<?= htmlspecialchars($old['employee_number']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" value="<?= htmlspecialchars($old['phone_number']) ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Qualification</label>
            <input type="text" class="form-control" name="qualification" value="<?= htmlspecialchars($old['qualification']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email_address" value="<?= htmlspecialchars($old['email_address']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label><br>
            <input type="radio" name="gender" value="Male" checked> Male
            &nbsp;&nbsp;
            <input type="radio" name="gender" value="Female"> Female
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="date_of_birth">
        </div>

        <!-- Subjects -->
        <div class="mb-3">
            <label class="form-label">Subjects</label>
            <div class="row row-cols-5">
                <?php foreach ($subjects as $subject): ?>
                    <div class="col">
                        <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>">
                        <?= htmlspecialchars($subject['subject']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Classes -->
        <div class="mb-3">
            <label class="form-label">Classes</label>
            <div class="row row-cols-5">
                <?php foreach ($classes as $class): 
                    $grade = getGradeById($class['grade'], $conn);
                    $section = getSectioById($class['section'], $conn);
                ?>
                    <div class="col">
                        <input type="checkbox" name="classes[]" value="<?= $class['class_id'] ?>">
                        <?= $grade['grade_code'] . '-' . $grade['grade'] . $section['section'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Teacher</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $("#navLinks li:nth-child(2) a").addClass('active');
    });

    function makePass(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('passInput').value = result;
    }

    document.getElementById('gBtn').addEventListener('click', function(e) {
        e.preventDefault();
        makePass(6);
    });
</script>

</body>
</html>