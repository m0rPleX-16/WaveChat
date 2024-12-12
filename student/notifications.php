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

$private_query = "
    SELECT n.messages, n.date_sent, 'Private' AS type 
    FROM notifications n
    WHERE n.type = 'private' AND n.student_id = ?
    ORDER BY n.date_sent DESC
";
$private_stmt = $conn->prepare($private_query);
$private_stmt->bind_param("i", $student_id);
$private_stmt->execute();
$private_result = $private_stmt->get_result();
$private_notifications = $private_result->fetch_all(MYSQLI_ASSOC);

$public_query = "
    SELECT n.messages, n.date_sent, 'Public' AS type 
    FROM notifications n
    WHERE n.type = 'public'
    AND n.public_sms_id IN (
        SELECT public_sms_id FROM public_sms_students_tbl WHERE student_id = ?
    )
    ORDER BY n.date_sent DESC
";
$public_stmt = $conn->prepare($public_query);
$public_stmt->bind_param("i", $student_id);
$public_stmt->execute();
$public_result = $public_stmt->get_result();
$public_notifications = $public_result->fetch_all(MYSQLI_ASSOC);

// Combine and sort notifications by date
$all_notifications = array_merge($private_notifications, $public_notifications);
usort($all_notifications, function ($a, $b) {
    return strtotime($b['date_sent']) - strtotime($a['date_sent']);
});

// Count notifications for the sidebar badge
$public_count_query = "
    SELECT COUNT(*) as count 
    FROM public_sms_students_tbl 
    WHERE student_id = ? 
    AND public_sms_id IN (SELECT public_sms_id FROM public_sms_tbl)
";
$public_count_stmt = $conn->prepare($public_count_query);
$public_count_stmt->bind_param('i', $student_id);
$public_count_stmt->execute();
$public_count_result = $public_count_stmt->get_result();
$public_count = $public_count_result->fetch_assoc()['count'];

$private_count_query = "
    SELECT COUNT(*) as count 
    FROM notifications 
    WHERE type = 'private' AND student_id = ?
";
$private_count_stmt = $conn->prepare($private_count_query);
$private_count_stmt->bind_param('i', $student_id);
$private_count_stmt->execute();
$private_count_result = $private_count_stmt->get_result();
$private_count = $private_count_result->fetch_assoc()['count'];

$notification_count = $public_count + $private_count;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .layout {
            display: flex;
            flex-wrap: nowrap;
            min-height: 100vh;
            overflow: hidden;
        }

        #sidebar {
            flex: 0 0 250px;
            background: linear-gradient(150deg, #285260, #b4d7d8);
            color: #fff;
            transition: width 0.3s ease;
        }

        #sidebar.collapsed {
            width: 70px;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background: #FCF7F2;
            overflow: auto;
        }

        .messages-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background: #EDE4DB;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message-card {
            background-color: #D4C7BC;
            border: 3px solid #D4C7BC;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }

        .message-card:hover {
            background-color: #FCF2EA;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .message-type {
            font-weight: bold;
        }

        .date-sent {
            color: #666;
            font-size: 0.9em;
        }

        h2 {
            color: #2E3F42;
            padding: 10px;
            border-radius: 5px;
            font-weight: 900;
        }
    </style>
</head>
<body>
    <div class="layout">
        <?php include "include/sidebar.php"; ?>
        
        <div class="content">
            <div class="messages-container">
                <button class="btn btn-primary d-md-none" onclick="toggleSidebar()">Toggle Sidebar</button>
                <h2 class="mb-4 text-center">Notifications</h2>
                <?php if (empty($all_notifications)): ?>
                    <div class="alert alert-info text-center">No notifications found.</div>
                <?php else: ?>
                    <?php foreach ($all_notifications as $notification): ?>
                        <div class="message-card">
                            <div class="message-type">
                                <?= htmlspecialchars($notification['type']) ?> Notification
                            </div>
                            <p class="message-content">
                                <?= htmlspecialchars($notification['message']) ?>
                            </p>
                            <div class="date-sent">
                                Sent on: <?= date("F j, Y, g:i a", strtotime($notification['date_sent'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
