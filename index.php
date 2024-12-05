

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.css">
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
        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #E0D7CF;
        }
        .login-card .card-body {
            padding: 2rem;
        }
        .login-card .form-control {
            border-radius: 10px;
            background-color: #FCF7F2;
            
        }
        .login-card .btn-primary {
            border-radius: 10px;
            background: #285260;
            border: none;
            transition: background 0.3s ease;
        }
        .login-card .btn-primary:hover {
            background: #548C92;
        }
        .text-muted {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body">
            <h3 class="text-center mb-4" style="color: #285260;"><b>WaveChat</b></h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none" style="color: #285260;"><i>Register</i></a></p>
            </div>
        </div>
    </div>
</body>
</html>

<!--

<?php
session_start();
require_once 'includes/DBconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: admin/admin.php');
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

-->