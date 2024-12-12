<?php
require_once '../db.php';

$programId = $_GET['student_id'];

$database = new db();
$conn = $database->getConnection();

$stmt = $conn->prepare("SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM student_tbl WHERE student_id = ?");
$stmt->execute([$programId]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($students);
?>
<?php
session_start();

require '../db.php';

$database = new db();
$conn = $database->getConnection();
if ($conn === null) {
    echo json_encode(['error' => 'Failed to establish database connection.']);
    exit();
}

$query = "SELECT student_id, last_name, first_name, middle_name FROM student_tbl";

$result = $conn->query($query);

$programs = [];

if ($result === false) {
    echo json_encode(['error' => 'Failed to fetch programs.']);
    exit();
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $programs[] = [
            'student_id' => $row['student_id'],
            'full_name' => $row['first_name'].''. $row['middle_name'].''. $row['last_name']
        ];
    }
}

header('Content-Type: application/json');

echo json_encode($programs);
?>
