<?php
require_once '../db.php';

$messageModal = ''; // For displaying the success/error modal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $programId = $_POST['program_id'];
    $adminId = 1; // Example admin ID, change as necessary

    try {
        $database = new db();
        $conn = $database->getConnection();

        $stmt = $conn->prepare("INSERT INTO public_sms_tbl (message, date_sent, admin_id) VALUES (?, NOW(), ?)");
        $stmt->bind_param("si", $message, $adminId);
        $stmt->execute();
        $publicSmsId = $conn->insert_id;

        $stmt = $conn->prepare("SELECT student_id FROM student_tbl WHERE program_id = ?");
        $stmt->bind_param("i", $programId);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt = $conn->prepare("INSERT INTO public_sms_students_tbl (student_id, public_sms_id) VALUES (?, ?)");
        while ($row = $result->fetch_assoc()) {
            $stmt->bind_param("ii", $row['student_id'], $publicSmsId);
            $stmt->execute();
        }

        $messageModal = "
            <div class='modal fade' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='successModalLabel'>Success</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            Public SMS sent successfully to all students in the selected course.
                        </div>
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
                        <div class='modal-body'>
                            Error: " . htmlspecialchars($e->getMessage()) . "
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                        </div>
                    </div>
                </div>
            </div>";
    }
}

$programs = [];
try {
    $database = new db();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT program_id, program_name FROM programs_tbl");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error fetching programs: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Public SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Send Public SMS</h1>
    <form action="send_public_sms.php" method="POST">
        <div class="mb-3">
            <label for="program_id" class="form-label">Select Course</label>
            <select name="program_id" id="program_id" class="form-select" required>
                <option value="" disabled selected>Select a course</option>
                <?php foreach ($programs as $program): ?>
                    <option value="<?= htmlspecialchars($program['program_id']) ?>">
                        <?= htmlspecialchars($program['program_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send SMS</button>
    </form>
</div>

<!-- Modals -->
<?= $messageModal ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Show modal if it exists
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
