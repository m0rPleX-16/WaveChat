<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $programId = $_POST['program_id']; 
    $adminId = 1;

    try {
        $database = new db();
        $conn = $database->getConnection();

        $stmt = $conn->prepare("INSERT INTO public_sms_tbl (message, date_sent, admin_id) VALUES (?, NOW(), ?)");
        $stmt->execute([$message, $adminId]);
        $publicSmsId = $conn->lastInsertId();

        $stmt = $conn->prepare("SELECT student_id FROM student_tbl WHERE program_id = ?");
        $stmt->execute([$programId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("INSERT INTO public_sms_students_tbl (student_id, public_sms_id) VALUES (?, ?)");
        foreach ($students as $student) {
            $stmt->execute([$student['student_id'], $publicSmsId]);
        }

        echo "Public SMS sent successfully to all students in the program.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
