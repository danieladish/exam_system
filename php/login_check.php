<?php
session_start();

include "config.php";

// Retrieve the submitted username and password
$submittedUsername = $_POST['username'] ?? '';
$submittedPassword = $_POST['password'] ?? '';

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement to retrieve the user with the submitted username and password
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $submittedUsername, $submittedPassword);
$stmt->execute();
$result = $stmt->get_result();

// Check if a matching user was found in the database
if ($result->num_rows === 1) {
    // Authentication successful, store user information in the session
    $query = "SELECT id, user_type, username FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $submittedUsername);
    $stmt->execute();
    $stmt->bind_result($userId, $userType, $userName);
    $stmt->fetch();
    $stmt->close();

    // Store the user ID and user type in the session
    $_SESSION['id'] = $userId;
    $_SESSION['user_type'] = $userType;
    $_SESSION['username'] = $userName;
    $_SESSION['loggedin'] = true;

    // Redirect to the dashboard or any other page based on user type
    if ($userType === 'teacher') {
        header('Location: ../dashboard.php');
    } else if ($userType === 'student') {
        header('Location: ../dashboard_students.php');
    }
    exit;
} else {
    // Authentication failed, redirect back to the login page with an error message
    //add text to display wrong login
	//header('Location: ../login.php');
	header('Location: login.php?error=InvalidCredentials');
    exit;
}

// Close the database connection
$stmt->close();
$conn->close();
?>

