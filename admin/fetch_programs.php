<?php
session_start();

require '../db.php';

$database = new db();
$conn = $database->getConnection();
if ($conn === null) {
    echo json_encode(['error' => 'Failed to establish database connection.']);
    exit();
}

$query = "SELECT program_id, program_name FROM programs_tbl";

$result = $conn->query($query);

$programs = [];

if ($result === false) {
    echo json_encode(['error' => 'Failed to fetch programs.']);
    exit();
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $programs[] = [
            'program_id' => $row['program_id'],
            'program_name' => $row['program_name']
        ];
    }
}

header('Content-Type: application/json');

echo json_encode($programs);
?>
