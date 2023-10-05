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
<html lang="en" >
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard_page.css">
</head>
<body>
    <div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">Dashboard</h1>

        <div class="links-container">
            <a href="create_exam.php" class="href">Create Exam</a> <!-- Link to Create Exam page -->
            <a href="view_exams.php" class="href">View Exams</a> <!-- Link to View Exams page -->
            <a href="show_scores.php" class="href">View Scores</a> <!-- Link to View Scores page -->
            <a href="seb_config.php" class="href">Generate SEB config file</a> <!-- Link to SEB generation page -->
            <a href="logout.php" class="href">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>
</body>
</html>
