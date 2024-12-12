<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: admin/admin.php");
    exit();
} elseif (isset($_SESSION['student_id'])) {
    header("Location: student/student_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $database = new db();
    $conn = $database->getConnection();

    // Check if the user is an admin
    $stmt = $conn->prepare("SELECT * FROM admin_tbl WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && $admin['password'] === $password) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];

        header("Location: admin/admin.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM student_tbl WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['username'] = $student['username'];

        header("Location: student/student.php");
        exit();
    }

    $error_message = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaveChat Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(150deg, #285260, #b4d7d8);
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            background-color: #E0D7CF;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .login-card h2 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            color: #285260;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #285260;
            color: white;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            transition: background 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-custom:hover {
            background-color: #548C92;
        }

        .modal-header {
            background-color: #285260;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="login-card">
        <h2>WaveChat Login</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger error-message"><?= $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-custom">Login</button>
            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none" style="color: #285260;"><i>Register</i></a></p>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.error-message').fadeOut();
        }, 5000);
    });
</script>
</body>
</html>

