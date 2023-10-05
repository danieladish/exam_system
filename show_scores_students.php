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

// Retrieve the user ID
$studentId = getUserId($conn, $username);

// Fetch the exams and scores for the student
$sql = "SELECT exams.id, exams.title, exam_scores.score
        FROM exams
        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
        WHERE exam_scores.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>My scores</title>
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/tables.css">
</head>
<body>
	<div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">My scores</h1>

        <div class="links-container">
            <a href="dashboard_students.php">Dashboard</a> <!-- Link to Dashboard page -->
			<a href="logout.php">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Exam ID</th>
                <th>Title</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['score']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
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
?>
