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
    // User is not logged in, return an error response
    $response = [
        'success' => false,
        'message' => 'User is not logged in'
    ];

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Output the JSON response
    echo json_encode($response);
    exit;
}

// Retrieve the selected exam ID from the request
$selectedExamId = $_POST['selectedExamId'];

// Retrieve the exam details from the database based on the selected exam ID
$sql = "SELECT questions.id, questions.question, questions.answer_1, questions.answer_2, questions.answer_3, questions.answer_4, exams.title, exams.duration FROM questions JOIN exams ON questions.exam_id = exams.id WHERE exams.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $selectedExamId);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = [
        'id' => $row['id'],
        'question' => $row['question'],
        'answerOptions' => [
            ['id' => 1, 'text' => $row['answer_1']],
            ['id' => 2, 'text' => $row['answer_2']],
            ['id' => 3, 'text' => $row['answer_3']],
            ['id' => 4, 'text' => $row['answer_4']]
        ]
    ];

    // Extract the title and duration from the first row (assuming they are the same for all questions in the exam)
    $title = $row['title'];
    $duration = $row['duration'];
}

// Prepare the JSON response
$response = [
    'success' => true,
    'examData' => [
        'title' => $title,
        'duration' => $duration,
        'questions' => $questions
    ]
];

// Set the content type to JSON
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
?>
