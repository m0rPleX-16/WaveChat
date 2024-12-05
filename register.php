
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(150deg, #285260, #b4d7d8);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family:  'Poppins', sans-serif;
        }
        .register-card {
            max-width: 600px;
            width: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #E0D7CF;
        }
        .register-card .card-body {
            padding: 2rem;
        }
        .register-card .form-control {
            border-radius: 10px;
            background-color: #FCF7F2;
        }
        .register-card .btn-primary {
            border-radius: 10px;
            background: #285260;
            border: none;
            transition: background 0.3s ease;
        }
        .register-card .btn-primary:hover {
            background: #548C92;
        }
        .text-muted {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="card register-card">
        <div class="card-body">
            <h3 class="text-center mb-4" style="color: #285260;"><b>Register</b></h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter your student ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter your last name" required>
                        </div>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter your first name" required>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter your middle name">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <input type="text" class="form-control" id="program" name="program" placeholder="Enter your program (e.g., BSIT)" required>
                        </div>
                        <div class="mb-3">
                            <label for="school" class="form-label">School</label>
                            <input type="text" class="form-control" id="school" name="school" placeholder="Enter your school name" required>
                        </div>
                        <div class="mb-3">
                            <label for="year_level" class="form-label">Year Level</label>
                            <input type="text" class="form-control" id="year_level" name="year_level" placeholder="Enter your year level" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
                <p class="text-muted">Already have an account? <a href="index.php" class="text-decoration-none" style="color: #285260;"><i>Login</i></a></p>
            </div>
        </div>
    </div>
</body>
</html>

<!--

<?php
require_once 'includes/DBconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $student_id = $_POST['student_id'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $phone_number = $_POST['phone_number'];
    $program = $_POST['program'];
    $school = $_POST['school'];
    $year_level = $_POST['year_level'];

    $stmt = $conn->prepare('INSERT INTO users (username, password, student_id, lastname, firstname, middlename, phone_number, program, school, year_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssssssss', $username, $password, $student_id, $lastname, $firstname, $middlename, $phone_number, $program, $school, $year_level);

    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        $error = 'Registration failed. Try again.';
    }
}
?>

-->