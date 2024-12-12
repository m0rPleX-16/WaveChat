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
            background: linear-gradient(150deg, #285260, #b4d7d8);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .card {
            background-color: #E0D7CF;
            color: #285260;
            border-radius: 12px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .btn-primary {
            background-color: #285260;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #548C92;
        }

        .btn-close {
            color: #666;
            opacity: 0.8;
        }

        .btn-close:hover {
            color: #333;
            opacity: 1;
        }

        .back-button {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .back-button:hover {
            color: #333;
        }

        .modal-content {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Send Student SMS</h2>
            <a href="admin.php" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
                </svg>
                Back
            </a>
        </div>
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
                <textarea name="message" id="message" class="form-control" rows="4"
                    placeholder="Write your message here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send SMS</button>
        </form>
    </div>

    <!-- Modals -->
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