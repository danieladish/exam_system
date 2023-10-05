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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/dashboard_students_page.css">
    <script>
        // JavaScript code for submitting the exam
function submitExam() {
    // Retrieve the selected answers from the form
    var answers = {};
    var answerRadios = document.getElementsByClassName("answer-radio");
    for (var i = 0; i < answerRadios.length; i++) {
        if (answerRadios[i].checked) {
            var questionId = answerRadios[i].getAttribute("data-question-id");
            answers[questionId] = answerRadios[i].value;
        }
    }

    // Prepare the data to be sent to the server
    var selectedExamId = document.getElementById("selectedExamId").value; // Retrieve the selected exam ID
    var data = {
        selectedExamId: selectedExamId,
        answers: answers
    };

    //console.log(data);

    // Send an AJAX request to the PHP script
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/submit_exam.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Modify this line
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Request successful
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Exam submitted successfully

                    // Display the score
                    var scoreDisplay = document.getElementById("scoreDisplay");
					scoreDisplay.style.display = "block";
                    scoreDisplay.textContent = "Your score: " + response.score + "/" + response.totalScore;
					// Display the Refresh
					document.getElementById('refreshButton').style.display = 'block';
                } else {
                    // Display error message
                    console.error(response.message);
                }
            } else {
                // Request failed
                console.error("Request failed with status:", xhr.status);
            }
        }
    };

    xhr.send(JSON.stringify(data));
}


        // JavaScript code for fetching the exam details
        function fetchExam() {
    var selectedExamId = document.getElementById("selectedExamId").value;

    // Prepare the data to be sent to the server
    var data = new FormData();
    data.append('selectedExamId', selectedExamId);

    // Send an AJAX request to the PHP script
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/fetch_exam.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Request successful
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Exam details retrieved successfully
                    var examData = response.examData;
                    displayExam(examData);

                    // Start the timer
                    startTimer(examData.duration);
                } else {
                    // Display error message
                    console.error(response.message);
                }
            } else {
                // Request failed
                console.error("Request failed with status:", xhr.status);
            }
        }
    };

    xhr.send(data);
}



        // JavaScript code for starting the timer
function startTimer(duration) {
    var timer = duration * 60; // Convert minutes to seconds
    var minutes, seconds;

    var timerInterval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Display the remaining time
        document.getElementById("timer").textContent = minutes + ":" + seconds;
		document.getElementById("timer").style.color = "#912929";

        if (--timer < 0) {
            // Timer has ended
            clearInterval(timerInterval);

            // Auto-submit the exam
            submitExam();
        }
    }, 1000);
}

        // JavaScript code for displaying the fetched exam details
function displayExam(examData) {
    var examContainer = document.getElementById("examContainer");

    // Display the exam title and duration
    var examTitle = document.createElement("h3");
    examTitle.textContent = examData.title;
    examContainer.appendChild(examTitle);

    var examDuration = document.createElement("p");
    examDuration.textContent = "Duration: " + examData.duration + " minutes";
    examContainer.appendChild(examDuration);


    // Display the timer
	var timerDisplay = document.createElement("p");
	timerDisplay.setAttribute("id", "timerDisplay");
	timerDisplay.innerHTML = "Time left: <span id='timer'></span>";
	examContainer.appendChild(timerDisplay);


    // Start the timer
    startTimer(examData.duration);

    // Display the questions and answer options
    var questions = examData.questions;
    questions.forEach(function (question) {
        var questionText = document.createElement("p");
        questionText.textContent = question.question;
        examContainer.appendChild(questionText);

        // Display the answer options
        for (var i = 0; i < question.answerOptions.length; i++) {
            var answerOption = question.answerOptions[i];
            var answerLabel = document.createElement("label");
            answerLabel.innerHTML = "<input type='radio' name='answer-" + question.id + "' class='answer-radio' value='" + answerOption.id + "' data-question-id='" + question.id + "'>" + answerOption.text;
            examContainer.appendChild(answerLabel);
            examContainer.appendChild(document.createElement("br"));
        }
    });

    // Display the submit button
    var submitButton = document.createElement("button");
    submitButton.textContent = "Submit Exam";
    submitButton.onclick = submitExam;
    examContainer.appendChild(submitButton);
}

    </script>
</head>
<body>
	<div class="container">
        <p class="logged-in-as"><?php echo "Logged in as: $username, type profile: $user_type"; ?></p>
        <h1 class="dashboard-title">Student Dashboard</h1>

        <div class="links-container">
            <a href="show_scores_students.php" class="href">View my scores</a> <!-- Link to individual scores page -->
			<a href="logout.php" class="href">Logout</a> <!-- Link to Logout page -->
        </div>
    </div>
    <h2>Select Exam</h2>
	<div class="exam_container">
    <div id="examSelection">
        <select id="selectedExamId">
            <?php
            // Retrieve the exams from the database
            $stmt = $conn->prepare("SELECT * FROM exams");
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any exams exist
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $examId = $row['id'];
                    $examTitle = $row['title'];

                    // Display exam options
                    echo "<option value='$examId'>$examTitle</option>";
                }
            } else {
                // No exams found
                echo '<option disabled>No exams found</option>';
            }
            ?>
        </select>
        <button type="button" onclick="fetchExam()">Start Exam</button>
    </div>

    <div id="examContainer"></div>

    <div id="scoreDisplay" style="display:none"></div>
	<button id="refreshButton" onclick="location.reload()" style="display: none">Select new exam</button>
	</div>
</body>
</html>
