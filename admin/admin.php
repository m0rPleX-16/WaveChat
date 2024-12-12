<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

require '../db.php';

$database = new db();
$conn = $database->getConnection();

$recentPrivateQuery = "SELECT message, date_sent FROM private_sms_tbl ORDER BY date_sent DESC LIMIT 5";
$recentPrivateResult = $conn->query($recentPrivateQuery);
$recentPrivate = $recentPrivateResult->fetch_all(MYSQLI_ASSOC);

$recentPublicQuery = "SELECT message, date_sent FROM public_sms_tbl ORDER BY date_sent DESC LIMIT 5";
$recentPublicResult = $conn->query($recentPublicQuery);
$recentPublic = $recentPublicResult->fetch_all(MYSQLI_ASSOC);

$recentStudentsQuery = "SELECT first_name, middle_name, last_name FROM student_tbl ORDER BY student_id DESC LIMIT 5";
$recentStudentsResult = $conn->query($recentStudentsQuery);
$recentStudents = $recentStudentsResult->fetch_all(MYSQLI_ASSOC);

$studentsCountQuery = "SELECT COUNT(*) FROM student_tbl";
$studentsCountResult = $conn->query($studentsCountQuery);
$studentsCount = $studentsCountResult->fetch_row()[0];

$programsCountQuery = "SELECT COUNT(*) FROM programs_tbl";
$programsCountResult = $conn->query($programsCountQuery);
$programsCount = $programsCountResult->fetch_row()[0];

$messagesCountQuery = "SELECT COUNT(*) FROM private_sms_tbl";
$messagesCountResult = $conn->query($messagesCountQuery);
$messagesCount = $messagesCountResult->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaveChat - Admin Dashboard</title>
    <link rel="stylesheet" href='../css/bootstrap.css'>
    <link rel="stylesheet" href='css/style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="main-content">
        <h2 class="mb-4" style="color: white">Welcome, Administrator</h2>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">Recent Private SMS</div>
                    <div class="card-body" id="recentPrivateMessages">
                        <ul>
                            <?php foreach ($recentPrivate as $sms): ?>
                                <li>
                                    <strong>Message:</strong> <?= htmlspecialchars($sms['message']) ?>
                                    <br>
                                    <small><strong>Date Sent:</strong> <?= $sms['date_sent'] ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Recent Public SMS</div>
                    <div class="card-body" id="recentPublicMessages">
                        <ul>
                            <?php foreach ($recentPublic as $sms): ?>
                                <li>
                                    <strong>Message:</strong> <?= htmlspecialchars($sms['message']) ?>
                                    <br>
                                    <small><strong>Date Sent:</strong> <?= $sms['date_sent'] ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Recently Registered Students</div>
                    <div class="card-body" id="recentPublicMessages">
                        <ul>
                            <?php foreach ($recentStudents as $student): ?>
                                <li>
                                    <strong>Full Name:</strong> <?= htmlspecialchars($student['first_name']) ?>
                                    <?= htmlspecialchars($student['middle_name']) ?>
                                    <?= htmlspecialchars($student['last_name']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">System Overview</div>
                    <div class="card-body">
                        <ul>
                            <li><strong>Total Students:</strong> <?= $studentsCount ?></li>
                            <li><strong>Total Programs:</strong> <?= $programsCount ?></li>
                            <li><strong>Total SMS Sent:</strong> <?= $messagesCount ?></li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Add New Program</div>
                    <div class="card-body">
                        <form id="addProgramForm">
                            <div class="mb-3">
                                <label for="programName" class="form-label" >Program Name</label>
                                <input type="text" class="form-control" id="programName" name="programName"
                                    placeholder="Enter Program Name (e.g., BSIT)" required>
                            </div>
                            <button type="submit" class="btn btn-custom">Add Program</button>
                        </form>
                        <div id="programMessage" class="mt-2"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Quick Actions</div>
                    <div class="card-body">
                        <a href="send_private_sms.php" class="btn btn-custom">Send Private SMS</a>
                        <a href="send_public_sms.php" class="btn btn-custom mt-3">Send Public SMS</a>
                       
                    </div>
                </div>
                <a href="#" id="logoutButton" class="btn btn-custom mt-3">Logout</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="../logout.php" class="btn btn-danger" id="confirmLogout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('logoutButton').addEventListener('click', function () {
            var logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        });

        document.getElementById('addProgramForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var programName = document.getElementById('programName').value;
            $.ajax({
                url: 'add_program.php',
                type: 'POST',
                data: { programName: programName },
                dataType: 'json',
                success: function (response) {
                    var messageDiv = document.getElementById('programMessage');
                    if (response.status === 'success') {
                        messageDiv.innerHTML = '<div class="alert alert-success">' + response.message + '</div>';
                        document.getElementById('programName').value = '';
                    } else {
                        messageDiv.innerHTML = '<div class="alert alert-danger">' + response.message + '</div>';
                    }
                },
                error: function () {
                    var messageDiv = document.getElementById('programMessage');
                    messageDiv.innerHTML = '<div class="alert alert-danger">An error occurred while adding the program. Please try again.</div>';
                }
            });
        });
    </script>
</body>

</html>