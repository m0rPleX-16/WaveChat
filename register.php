<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href='css/bootstrap.css' rel="stylesheet">
    <link href='css/style.css' rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="card register-card">
        <div class="card-body">
            <h3 class="text-center mb-4" style="color: #285260;"><b>Student Registration</b></h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id"
                                placeholder="Enter your student ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                placeholder="Enter your last name" required>
                        </div>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                placeholder="Enter your first name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middlename" name="middlename"
                                placeholder="Enter your middle name" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                placeholder="Enter your phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-select w-100" id="programDropdown" name="program" required>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="school" class="form-label">School</label>
                            <input type="text" class="form-control" id="school" name="school"
                                placeholder="Enter your school name" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_level" class="form-label">Year Level</label>
                            <input type="text" class="form-control" id="year_level" name="year_level"
                                placeholder="Enter your year level" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
                <p class="text-muted">Already have an account? <a href="index.php" class="text-decoration-none"
                        style="color: #285260; font-weight:bold;"><i>Login</i></a></p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src='js/script.js'></script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: 'admin/fetch_programs.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (Array.isArray(data) && data.length > 0) {
                        const programDropdown = $('#programDropdown');
                        programDropdown.empty();
                        programDropdown.append('<option value="">Select Program</option>');
                        data.forEach(function (program) {
                            programDropdown.append('<option value="' + program.program_id + '">' + program.program_name + '</option>');
                        });
                    } else {
                        console.error('No programs found or invalid data.');
                    }
                },
                error: function (error) {
                    console.log('Error fetching programs:', error);
                    alert('An error occurred while fetching programs.');
                }
            });
        });
    </script>
</body>

</html>

<?php
require_once 'db.php';

$database = new db();
$conn = $database->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $student_id = htmlspecialchars(trim($_POST['student_id']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $middlename = htmlspecialchars(trim($_POST['middlename']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $program = htmlspecialchars(trim($_POST['program']));
    $school = htmlspecialchars(trim($_POST['school']));
    $year_level = htmlspecialchars(trim($_POST['year_level']));

    $checkQuery = $conn->prepare('SELECT student_id FROM student_tbl WHERE username = ? OR student_id = ?');
    $checkQuery->bind_param('ss', $username, $student_id);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        $error = 'Username or Student ID already exists.';
    } else {
        $stmt = $conn->prepare('INSERT INTO student_tbl (username, password, student_id, last_name, first_name, middle_name, phone_number, program_id, school, year_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('ssssssssss', $username, $password, $student_id, $lastname, $firstname, $middlename, $phone_number, $program, $school, $year_level);

            if ($stmt->execute()) {
                header('Location: register.php?success=1');
                exit();
            } else {
                $error = 'Registration failed. Try again.';
            }
            $stmt->close();
        } else {
            $error = 'Database error: Could not prepare statement.';
        }
    }
    $checkQuery->close();
}
?>