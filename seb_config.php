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
    <title>Generate SEB configuration file</title>
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/seb_config.css">
</head>
<body>
	<div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">Generate SEB configuration file</h1>

        <div class="links-container">
			<a href="dashboard.php">Dashboard</a> <!-- Link to Dashboard page -->
            <a href="create_exam.php" class="href">Create Exam</a> <!-- Link to Create Exam page -->
            <a href="view_exams.php" class="href">View Exams</a> <!-- Link to View Exams page -->
            <a href="show_scores.php" class="href">View Scores</a> <!-- Link to View Scores page -->
            <a href="logout.php" class="href">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>
    
	<form method="post" action="php/generate_config.php">
    <h3>Configuration Options:</h3>
    <p>
        <input type="checkbox" id="enableQuitButton" name="enableQuitButton" value="false">
        <label for="enableQuitButton">Disable Quit Button</label>
    </p>
    <p>
        <input type="checkbox" id="enableBackButton" name="enableBackButton" value="false">
        <label for="enableBackButton">Disable Back Button</label>
    </p>
    <p>
        <input type="checkbox" id="enableReloadButton" name="enableReloadButton" value="false">
        <label for="enableReloadButton">Disable Reload Button</label>
    </p>
    <p>
        <input type="checkbox" id="copyPasteBehavior" name="copyPasteBehavior" value="false">
        <label for="copyPasteBehavior">Disable Copy Paste Behavior</label>
    </p>
    <button id="seb_btn" type="submit">Generate Configuration File</button>
</form>
</body>
</html>
