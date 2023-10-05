<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login page or display error
    header('Location: login.php');
    exit;
}

// User is logged in, retrieve the username from the session
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Exams</title>
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/tables.css">
</head>
<body>
	<div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">View Exams</h1>

        <div class="links-container">
			<a href="dashboard.php">Dashboard</a> <!-- Link to Dashboard page -->
            <a href="create_exam.php" class="href">Create Exam</a> <!-- Link to Create Exam page -->
            <a href="show_scores.php" class="href">View Scores</a> <!-- Link to View Scores page -->
            <a href="seb_config.php" class="href">Generate SEB config file</a> <!-- Link to SEB generation page -->
            <a href="logout.php" class="href">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>

    <div id="examsList">
        <table>
            <thead>
                <tr>
                    <th>Exam ID</th>
                    <th>Exam Name</th>
                    <th>Exam Duration</th>
                    <th>Teacher Username</th>
                    <th>View Questions</th> <!-- New column for viewing questions -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Establish a database connection
                include "php/config.php";

                // Create a connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve exams from the database
                $sql = "SELECT exams.id, exams.title, exams.duration, users.username 
                        FROM exams
                        INNER JOIN users ON exams.teacher_id = users.id";
                $result = $conn->query($sql);

                // Check if any exams exist
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $examId = $row['id'];
                        $examTitle = $row['title'];
                        $examDuration = $row['duration'];
                        $teacherUsername = $row['username'];

                        // Display the exam details in a table row
                        echo '<tr>';
                        echo '<td>' . $examId . '</td>';
                        echo '<td>' . $examTitle . '</td>';
                        echo '<td>' . $examDuration . '</td>';
                        echo '<td>' . $teacherUsername . '</td>';
                        echo '<td><a href="view_questions.php?examId=' . $examId . '">View Questions</a></td>'; // Link to view questions page
                        echo '</tr>';
                    }
                } else {
                    // No exams found
                    echo '<tr><td colspan="5">No exams found.</td></tr>';
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
