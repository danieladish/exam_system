<?php
session_start();

include "config.php";

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

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the necessary data is provided
    $postData = json_decode(file_get_contents('php://input'), true); // Retrieve JSON data

    if (isset($postData['selectedExamId']) && isset($postData['answers'])) {
        $selectedExamId = $postData['selectedExamId'];
        $answers = $postData['answers'];

        // Calculate the score
        $score = calculateScore($selectedExamId, $answers);

        // Insert the score into the exam_scores table
        $studentId = getUserId($conn, $username);
        insertExamScore($conn, $selectedExamId, $studentId, $score);

        // Prepare the response
        $response = array(
            'success' => true,
            'score' => $score,
            'totalScore' => count($answers)
        );

        echo json_encode($response);
    } else {
        // Invalid request, display error
        $response = array(
            'success' => false,
            'message' => 'Invalid request. Missing data.'
        );

        echo json_encode($response);
    }
}

// Function to calculate the score based on the selected answers
function calculateScore($selectedExamId, $answers)
{
    // Retrieve the correct answers from the database
    global $conn;
    $stmt = $conn->prepare("SELECT id, correct_answer FROM questions WHERE exam_id = ? ORDER BY id");
    $stmt->bind_param("i", $selectedExamId);
    $stmt->execute();
    $result = $stmt->get_result();

    $correctAnswers = array();
    while ($row = $result->fetch_assoc()) {
        $correctAnswers[$row['id']] = $row['correct_answer'];
    }

    // Calculate the score
    $score = 0;
    $questionIds = array_keys($answers); // Get the question IDs in the order they appear in the answers array
    foreach ($questionIds as $questionId) {
        $selectedAnswerId = $answers[$questionId];
        $correctAnswer = $correctAnswers[$questionId];
        if ($selectedAnswerId == $correctAnswer) {
            $score++;
        }
    }

    return $score;
}

// Function to retrieve the user ID based on the username
function getUserId($conn, $username)
{
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['id'];
}

// Function to insert the exam score into the exam_scores table
function insertExamScore($conn, $selectedExamId, $studentId, $score)
{
    $stmt = $conn->prepare("INSERT INTO exam_scores (exam_id, student_id, score) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $selectedExamId, $studentId, $score);
    $stmt->execute();
    $stmt->close();
}
?>
