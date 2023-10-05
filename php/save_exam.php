<?php
session_start();

include "config.php";

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login page or display error
    header('Location: login.php');
    exit;
}

// Retrieve the logged-in teacher's ID from the session
$teacherId = $_SESSION['id'];

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the POST data
$postData = json_decode(file_get_contents("php://input"), true);

// Extract the exam details
$examTitle = $postData['examTitle'];
$examDuration = $postData['examDuration'];
$questions = $postData['questions'];

// Insert the exam details into the database
$sql = "INSERT INTO exams (title, duration, teacher_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $examTitle, $examDuration, $teacherId);

if ($stmt->execute()) {
    // Get the auto-generated exam ID
    $examId = $stmt->insert_id;

    // Insert the questions into the database
    $sql = "INSERT INTO questions (exam_id, question, answer_1, answer_2, answer_3, answer_4, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssi", $examId, $questionText, $answer_1, $answer_2, $answer_3, $answer_4, $correctAnswer);

    foreach ($questions as $question) {
        $questionText = $question['question'];
        $answer_1 = $question['answer_1'];
        $answer_2 = $question['answer_2'];
        $answer_3 = $question['answer_3'];
        $answer_4 = $question['answer_4'];
        $correctAnswer = $question['correctAnswer'];

        $stmt->execute();
    }

    $response = array(
        "success" => true,
        "message" => "Exam added successfully."
    );
    echo json_encode($response);
} else {
    // Error adding the exam
    $response = array(
        "success" => false,
        "message" => "Error adding the exam: " . $stmt->error
    );
    echo json_encode($response);
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
