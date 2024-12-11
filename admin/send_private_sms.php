<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $studentId = $_POST['student_id'];
    $adminId = 1;

    try {
        $database = new db();
        $conn = $database->getConnection();


        $stmt = $conn->prepare("INSERT INTO private_sms_tbl (message, date_sent, admin_id, student_id) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$message, $adminId, $studentId]);

        echo "Private SMS sent successfully to the student.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
