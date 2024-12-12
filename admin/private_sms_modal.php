<?php
require_once '../db.php';

$messageModal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $studentId = $_POST['student_id'];
    $adminId = 1;

    $attachment = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileName = basename($_FILES['attachment']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath)) {
            $attachment = $filePath;
        }
    }

    try {
        $database = new db();
        $conn = $database->getConnection();
        $stmt = $conn->prepare("INSERT INTO private_sms_tbl (message, date_sent, admin_id, student_id) VALUES (?, NOW(), ?, ?)");
        $stmt->bind_param("sii", $message, $adminId, $studentId);
        $stmt->execute();

        $messageModal = "
            <div class='modal fade' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='successModalLabel'>Success</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>Private SMS sent successfully to the selected student.</div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                        </div>
                    </div>
                </div>
            </div>";
    } catch (Exception $e) {
        $messageModal = "
            <div class='modal fade' id='errorModal' tabindex='-1' aria-labelledby='errorModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='errorModalLabel'>Error</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>Error: " . htmlspecialchars($e->getMessage()) . "</div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                        </div>
                    </div>
                </div>
            </div>";
    }
}

try {
    $database = new db();
    $conn = $database->getConnection();

    $query = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM student_tbl";
    $result = $conn->query($query);

    $students = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error fetching students: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            color: #fff;
        }
        .btn-primary {
            background-color: #2575fc;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1b5bbf;
        }
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            background: #2575fc;
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1 class="mb-4">Send SMS</h1>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendSMSModal">
            Open Send SMS Form
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sendSMSModal" tabindex="-1" aria-labelledby="sendSMSModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendSMSModalLabel">Send SMS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="send_private_sms.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Select Student</label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="" disabled selected>Select a student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= htmlspecialchars($student['student_id']) ?>">
                                        <?= htmlspecialchars($student['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment (Optional)</label>
                            <input type="file" name="attachment" id="attachment" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send SMS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for Success/Error Messages -->
    <?= $messageModal ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successModal = document.getElementById('successModal');
            const errorModal = document.getElementById('errorModal');
            if (successModal) {
                new bootstrap.Modal(successModal).show();
            } else if (errorModal) {
                new bootstrap.Modal(errorModal).show();
            }
        });
    </script>
</body>
</html>
