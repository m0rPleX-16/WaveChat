<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
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

try {
    $database = new db();
    $conn = $database->getConnection();

    $queryMessageCount = "
        SELECT 
            (SELECT COUNT(*) FROM public_sms_students_tbl pss 
             INNER JOIN public_sms_tbl ps ON pss.public_sms_id = ps.public_sms_id 
             WHERE pss.student_id = ?) AS public_count,
            (SELECT COUNT(*) FROM private_sms_tbl p 
             WHERE p.student_id = ?) AS private_count
    ";

    $stmt = $conn->prepare($queryMessageCount);
    $stmt->bind_param("ii", $student_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $totalMessages = $result['public_count'] + $result['private_count'];
} catch (Exception $e) {
    $totalMessages = 0;
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
            <hr>
            <h3>About the College</h3>
            <p>The <b>College of Computing Education</b> maintains its reputation as one of the best computer schools in
                the region through its PACUCOA Level III accredited programs as well as being
                a certified Center of Development. The college is composed of highly qualified facultry members who are
                skilled and equipped with the updated skills in different fields of computer studies.
                The Computer Science and Information Technology program of the college is granted Center of Development
                (COD) status be CHED. It has forged collaborations with Apple, Google, Microsoft, and IBM.</p>
            <h4>Programs</h4>
            <li>
                Bachelor of Science in Information Technology
            </li>
            <li>
                Bachelor of Science in Computer Science
            </li>
            <li>
                Bachelor of Science in Information Systems
            </li>
            <li>
                Bachelor of Library and Information Science
            </li>
            <li>
                Bachelor of Science in Entertainment and Multimedia Computing - Digital Animation
            </li>
            <li>
                Bachelor of Science in Entertainment and Multimedia Computing - Game Development
            </li>
            <li>
                Bachelor of Multimedia Arts
            </li>
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