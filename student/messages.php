<?php
require '../db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

try {
    $database = new db();
    $conn = $database->getConnection();

    $queryPublic = "
        SELECT 
            ps.message AS message, 
            ps.date_sent AS date_sent, 
            'public' AS type
        FROM 
            public_sms_students_tbl pss
        INNER JOIN 
            public_sms_tbl ps 
        ON 
            pss.public_sms_id = ps.public_sms_id
        WHERE 
            pss.student_id = ?
    ";

    $queryPrivate = "
        SELECT 
            p.message AS message, 
            p.date_sent AS date_sent, 
            'private' AS type
        FROM 
            private_sms_tbl p
        WHERE 
            p.student_id = ?
    ";

    $stmtPublic = $conn->prepare($queryPublic);
    $stmtPublic->bind_param("i", $student_id);
    $stmtPublic->execute();
    $resultPublic = $stmtPublic->get_result();

    $stmtPrivate = $conn->prepare($queryPrivate);
    $stmtPrivate->bind_param("i", $student_id);
    $stmtPrivate->execute();
    $resultPrivate = $stmtPrivate->get_result();

    $messages = [];

    while ($row = $resultPublic->fetch_assoc()) {
        $messages[] = $row;
    }
    while ($row = $resultPrivate->fetch_assoc()) {
        $messages[] = $row;
    }

    usort($messages, function ($a, $b) {
        return strtotime($b['date_sent']) - strtotime($a['date_sent']);
    });
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .layout {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            flex-shrink: 0;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background: #f8f9fa;
        }

        .messages-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }

        .message-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .message-type {
            font-weight: bold;
        }

        .date-sent {
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="layout">
        <?php include "include/sidebar.php"; ?>

        <div class="content">
            <div class="messages-container">
                <button class="btn btn-primary d-md-none" onclick="toggleSidebar()">Toggle Sidebar</button>
                <h2 class="mb-4 text-center">Messages</h2>
                <?php if (empty($messages)): ?>
                    <div class="alert alert-info text-center">No messages found.</div>
                <?php else: ?>
                    <div class="row">
                        <!-- Public Messages -->
                        <div class="col-md-6">
                            <h4 class="text-center">Public Messages</h4>
                            <?php foreach ($messages as $message): ?>
                                <?php if ($message['type'] == 'public'): ?>
                                    <div class="message-card">
                                        <div class="message-type">
                                            <?= htmlspecialchars(ucfirst($message['type'])) ?> Message
                                        </div>
                                        <p class="message-content">
                                            <?= htmlspecialchars($message['message']) ?>
                                        </p>
                                        <div class="date-sent">
                                            Sent on: <?= date("F j, Y, g:i a", strtotime($message['date_sent'])) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Private Messages -->
                        <div class="col-md-6">
                            <h4 class="text-center">Private Messages</h4>
                            <?php foreach ($messages as $message): ?>
                                <?php if ($message['type'] == 'private'): ?>
                                    <div class="message-card">
                                        <div class="message-type">
                                            <?= htmlspecialchars(ucfirst($message['type'])) ?> Message
                                        </div>
                                        <p class="message-content">
                                            <?= htmlspecialchars($message['message']) ?>
                                        </p>
                                        <div class="date-sent">
                                            Sent on: <?= date("F j, Y, g:i a", strtotime($message['date_sent'])) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
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