<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

require '../db.php';

$student_id = $_SESSION['student_id'];

$database = new db();
$conn = $database->getConnection();

$stmt = $conn->prepare("SELECT first_name, last_name FROM student_tbl WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $student_name = $student['first_name'] . ' ' . $student['last_name'];
} else {
    $student_name = "Student";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #E0D7CF;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            background-color: #E0D7CF;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(150deg, #285260, #b4d7d8);
            color: #fff;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link i {
            font-size: 20px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar .toggle-btn {
            cursor: pointer;
            margin: 10px;
            color: #fff;
            font-size: 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-header h1 {
            font-size: 24px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100%;
                z-index: 1000;
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding-top: 60px;
            }
        }
    </style>
</head>

<body>
    <?php include "include/sidebar.php"; ?>
    <div class="content">
        <div class="content-header">
            <h1>Welcome, <?php echo htmlspecialchars($student_name); ?></h1>
            <button class="btn btn-primary d-md-none" onclick="toggleSidebar()">Toggle Sidebar</button>
        </div>
        <hr>
        <div>
            <p>This is your dashboard where you can manage your courses, view notifications, and access messages.</p>

            <div class="row">
                <!-- Example Cards -->
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Courses</h5>
                            <p class="card-text">View and manage your enrolled courses.</p>
                            <a href="#" class="btn btn-primary">View Courses</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Notifications</h5>
                            <p class="card-text">Stay updated with the latest notifications.</p>
                            <a href="#" class="btn btn-primary">View Notifications</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Messages</h5>
                            <p class="card-text">Check your messages and communicate.</p>
                            <a href="#" class="btn btn-primary">View Messages</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('open');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>