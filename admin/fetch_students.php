<?php
require_once '../db.php';

$programId = $_GET['program_id'];

$database = new db();
$conn = $database->getConnection();

$stmt = $conn->prepare("SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM student_tbl WHERE program_id = ?");
$stmt->execute([$programId]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($students);
?>
