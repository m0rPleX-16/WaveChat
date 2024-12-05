<?php
require_once '../includes/DBconnection.php';
require_once '../send_sms.php';

// Fetch conversations
$conversations = $conn->query("SELECT * FROM conversations")->fetch_all(MYSQLI_ASSOC);

// Handle SMS sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $conversation_id = $_POST['conversation_id'];

    // Get recipients
    $recipients = $conn->query("SELECT u.phone_number, u.student_id 
        FROM conversation_members cm 
        JOIN users u ON cm.user_id = u.id 
        WHERE cm.conversation_id = $conversation_id")->fetch_all(MYSQLI_ASSOC);

    foreach ($recipients as $recipient) {
        if (sendSMS($recipient['phone_number'], $message)) {
            $conn->query("INSERT INTO sms_logs (student_id, message, conversation_id) 
                VALUES ('{$recipient['student_id']}', '$message', $conversation_id)");
        }
    }
    $success = 'Message sent successfully!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 300px;
            background: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
        }
        .chat-head {
            padding: 15px;
            border-bottom: 1px solid #495057;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .chat-head:hover {
            background: #495057;
        }
        .chat-head img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="p-3">Conversations</h4>
        <?php foreach ($conversations as $conversation): ?>
            <div class="chat-head" data-conversation-id="<?= $conversation['id'] ?>">
                <img src="<?= $conversation['type'] === 'group' ? 'group_icon.png' : 'user_icon.png' ?>" alt="Avatar">
                <span><?= htmlspecialchars($conversation['name']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Send SMS</h3>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="conversation_id" id="conversation_id" value="">
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" class="form-control" rows="4" placeholder="Enter your message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>

    <script>
        const chatHeads = document.querySelectorAll('.chat-head');
        chatHeads.forEach(chatHead => {
            chatHead.addEventListener('click', () => {
                const conversationId = chatHead.getAttribute('data-conversation-id');
                document.getElementById('conversation_id').value = conversationId;
            });
        });
    </script>
</body>
</html>
