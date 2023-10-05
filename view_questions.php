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
    <title>View Questions</title>
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/tables.css">
</head>
<body>
	<div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">View Questions</h1>

        <div class="links-container">
            <a href="dashboard.php">Dashboard</a> <!-- Link to Dashboard page -->
			<a href="create_exam.php">Create Exam</a> <!-- Link to Create Exam page -->
			<a href="view_exams.php">View Exams</a> <!-- Link to View Exams page -->
			<a href="seb_config.php">Generate SEB config file</a> <!-- Link to SEB generation page -->
			<a href="logout.php">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>
	
    <div id="questionsList">
        <table>
            <thead>
                <tr>
                    <th>Question ID</th>
                    <th>Question</th>
                    <th>Answer 1</th>
                    <th>Answer 2</th>
                    <th>Answer 3</th>
                    <th>Answer 4</th>
                    <th>Correct Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "php/config.php";

                // Create a connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get the exam ID from the URL parameter
                $examId = $_GET['examId'];

                // Retrieve questions of the specified exam from the database
                $sql = "SELECT * FROM questions WHERE exam_id = $examId";
                $result = $conn->query($sql);

                // Check if any questions exist for the exam
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $questionId = $row['id'];
                        $question = $row['question'];
                        $answer1 = $row['answer_1'];
                        $answer2 = $row['answer_2'];
                        $answer3 = $row['answer_3'];
                        $answer4 = $row['answer_4'];
                        $correctAnswer = $row['correct_answer'];

                        // Display the question details in a table row
                        echo '<tr>';
                        echo '<td>' . $questionId . '</td>';
                        echo '<td>' . $question . '</td>';
                        echo '<td>' . $answer1 . '</td>';
                        echo '<td>' . $answer2 . '</td>';
                        echo '<td>' . $answer3 . '</td>';
                        echo '<td>' . $answer4 . '</td>';
                        echo '<td>' . $correctAnswer . '</td>';
                        echo '</tr>';
                    }
                } else {
                    // No questions found for the exam
                    echo '<tr><td colspan="7">No questions found for this exam.</td></tr>';
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
