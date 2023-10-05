<?php
session_start();

include "php/config.php";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login page or display error
    header('Location: login.php');
    exit;
}

// User is logged in, retrieve the username from the session
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];


// Retrieve all scores with exam details
$sql = "SELECT exams.id AS exam_id, exams.title, exam_scores.student_id, users.username, exam_scores.score
        FROM exam_scores
        INNER JOIN exams ON exam_scores.exam_id = exams.id
        INNER JOIN users ON exam_scores.student_id = users.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>All Scores</title>
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/tables.css">
</head>
<body>
	<div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">All Scores</h1>

        <div class="links-container">
			<a href="dashboard.php">Dashboard</a> <!-- Link to Dashboard page -->
            <a href="create_exam.php" class="href">Create Exam</a> <!-- Link to Create Exam page -->
            <a href="view_exams.php" class="href">View Exams</a> <!-- Link to View Exams page -->
            <a href="seb_config.php" class="href">Generate SEB config file</a> <!-- Link to SEB generation page -->
            <a href="logout.php" class="href">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Exam ID</th>
                <th>Title</th>
                <th>Student ID</th>
                <th>Username</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['exam_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['score']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
