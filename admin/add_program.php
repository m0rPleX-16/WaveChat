<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../db.php';

    $programName = trim($_POST['programName']);
    if (empty($programName)) {
        echo json_encode(['status' => 'error', 'message' => 'Program name is required']);
        exit();
    }

    $database = new db();
    $conn = $database->getConnection();

    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit();
    }
    error_log("Program Name: " . $programName);

    $query = "INSERT INTO programs_tbl (program_name) VALUES (?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $programName);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Program added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to execute query']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare query']);
    }

    $conn->close();
}
?>
