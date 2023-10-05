document.addEventListener("DOMContentLoaded", function() {
    // Attach event listener to the createExamButton
    const createExamButton = document.getElementById("createExamButton");
    createExamButton.addEventListener("click", createExam);

     function createExam() {
    // Clone the exam form template
    const template = document.getElementById("examFormTemplate");
    const clone = template.content.cloneNode(true);

    // Append the cloned form to the examsList div
    const examsList = document.getElementById("examsList");
    examsList.appendChild(clone);

    addQuestionButton.addEventListener("click", function() {
    addQuestion(clone);
  });

	saveExamButton.addEventListener("click", function() {
    saveExam(clone);
  });
  }

    function addQuestion() {
        // Clone the question form template
        const questionTemplate = document.getElementById("questionFormTemplate");
        const clone = questionTemplate.content.cloneNode(true);

        // Append the cloned question form to the questionsContainer
        const questionsContainer = document.getElementById("questionsContainer");
        questionsContainer.appendChild(clone);
    }

    function saveExam() {
        // Retrieve the exam details from the form
        const examTitle = document.getElementById("examTitle").value;
        const examDuration = document.getElementById("examDuration").value;

        // Retrieve the questions from the form
        const questions = [];
        const questionForms = document.getElementsByClassName("question-form");
        for (const questionForm of questionForms) {
            const question = questionForm.querySelector("input[name='question']").value;
            const answer_1 = questionForm.querySelector("input[name='answer_1']").value;
            const answer_2 = questionForm.querySelector("input[name='answer_2']").value;
            const answer_3 = questionForm.querySelector("input[name='answer_3']").value;
            const answer_4 = questionForm.querySelector("input[name='answer_4']").value;
            const correctAnswer = questionForm.querySelector("input[name='correctAnswer']").value;

            questions.push({
                question,
                answer_1,
                answer_2,
                answer_3,
                answer_4,
                correctAnswer,
            });
        }

        // Prepare the data to be sent to the server
        const data = {
            examTitle,
            examDuration,
            questions,
        };

        // Send an AJAX request to the PHP script
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "php/save_exam.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request successful
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Exam added successfully

                        // Clear the input fields
                        document.getElementById("examTitle").value = "";
                        document.getElementById("examDuration").value = "";
                        document.getElementById("questionsContainer").innerHTML = "";

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
});
