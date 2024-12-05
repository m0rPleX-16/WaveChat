<?php
require_once 'includes/DBconnection.php';

$sms_logs = $conn->query("
    SELECT l.*, c.name AS conversation_name 
    FROM sms_logs l 
    JOIN conversations c ON l.conversation_id = c.id 
    ORDER BY l.sent_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">SMS Logs</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Conversation</th>
                <th>Message</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sms_logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['conversation_name']) ?></td>
                    <td><?= htmlspecialchars($log['message']) ?></td>
                    <td><?= htmlspecialchars($log['sent_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
