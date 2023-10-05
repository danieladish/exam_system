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
    <title>Create Exam</title>
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/create_exam.css">
</head>
<body>
	<div id="bg"></div>
    <div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">Create Exam</h1>

        <div class="links-container">
            <a href="dashboard.php">Dashboard</a> <!-- Link to Dashboard page -->
			<a href="view_exams.php">View Exams</a> <!-- Link to View Exams page -->
			<a href="show_scores.php" class="href">View Scores</a> <!-- Link to View Scores page -->
			<a href="seb_config.php">Generate SEB config file</a> <!-- Link to SEB generation page -->
			<a href="logout.php">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>

    <button id="createExamButton">Create Exam</button>
	
	<div id="examsList"></div>

    <template id="examFormTemplate">
        <div class="exam-form">
            <h3>Create Exam</h3>
            <label for="examTitle">Exam Title:</label>
            <input type="text" id="examTitle" name="examTitle">
            <label for="examDuration">Exam Duration:</label>
            <input type="number" id="examDuration" name="examDuration" min="1" step="1" value="1">
            <div id="questionsContainer"></div>
            <button id="addQuestionButton">Add Question</button>
            <button id="saveExamButton">Save Exam</button>
        </div>
    </template>

    <template id="questionFormTemplate">
        <div class="question-form">
            <h4>Question</h4>
            <label for="question">Question:</label>
            <input type="text" name="question">
            <label for="answer_1">Answer 1:</label>
            <input type="text" name="answer_1">
            <label for="answer_2">Answer 2:</label>
            <input type="text" name="answer_2">
            <label for="answer_3">Answer 3:</label>
            <input type="text" name="answer_3">
            <label for="answer_4">Answer 4:</label>
            <input type="text" name="answer_4">
            <label for="correctAnswer">Correct Answer:</label>
            <input type="number" name="correctAnswer" min="1" max="4" step="1" value="1">
        </div>
    </template>

    <script src="javascript/create_exam.js"></script>

</body>
</html>
