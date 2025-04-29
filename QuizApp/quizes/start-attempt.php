<?php
session_start();

$userId = $_SESSION['userId'];

$db_servername = $_SESSION['db_servername'];
$db_username = $_SESSION['db_username'];
$db_password = $_SESSION['db_password'];
$db_name = $_SESSION['db_name'];
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['quizId'])) {
    die("Quiz ID not provided.");
}

$quizId = $_GET['quizId'];

// Fetch quiz info
$quiz_sql = "SELECT * FROM `quiz` WHERE `quizId` = '$quizId'";
$quiz_result = $conn->query($quiz_sql);
if ($quiz_result->num_rows == 0) {
    die("Quiz not found.");
}
$quiz_row = $quiz_result->fetch_assoc();
$topic = $quiz_row['topic'];
$time_limit = $quiz_row['quizTime']; // Not used yet, but could set timer later

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $total_questions = 0;
    $correct_answers = 0;

    // Fetch questions and correct answers
    $question_sql = "SELECT * FROM `question` WHERE `quizId` = '$quizId'";
    $question_result = $conn->query($question_sql);

    while ($question_row = $question_result->fetch_assoc()) {
        $questionId = $question_row['questionId'];
        $correctAnswerId = $question_row['correctAnswerId'];

        $user_answer = isset($_POST["question_$questionId"]) ? $_POST["question_$questionId"] : null;

        if ($user_answer !== null) {
            if ($user_answer == $correctAnswerId) {
                $correct_answers++;
            }
        }
        $total_questions++;
    }

    $marks = $correct_answers; // You can change how you calculate marks if needed

    // Insert into attempt table
    $insert_attempt_sql = "INSERT INTO `attempt` (`quizId`, `userID`, `marks`) VALUES ('$quizId', '$userId', '$marks')";
    if ($conn->query($insert_attempt_sql) === TRUE) {
        echo "<script>alert('Quiz Submitted Successfully! You scored $marks marks.'); window.location.href='attempt.php';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Otherwise, display the quiz

echo "<!DOCTYPE html><html>
<head>
    <title>Start Attempt</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <link rel='stylesheet' type='text/css' href='../Styles/main.css'>
    <style>
        .question-block {
            margin: 20px auto;
            padding: 15px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
            text-align: left;
        }
        .question-text {
            font-size: 18px;
            font-weight: bold;
        }
        .answer-option {
            margin: 5px 0;
        }
    </style>
</head>
<body><center>";

echo "<table width='100%'><tr>
        <td align='left'><a href='../Homepage.php'><button class='default-button'>Home</button></a></td>
    </tr></table>";

echo "<div class='main-div default-shadow'>";
echo "<h1>Attempt Quiz: " . htmlspecialchars($topic) . "</h1><hr>";

echo "<form method='POST'>";

// Fetch questions
$question_sql = "SELECT * FROM `question` WHERE `quizId` = '$quizId'";
$question_result = $conn->query($question_sql);

if ($question_result->num_rows > 0) {
    while ($question_row = $question_result->fetch_assoc()) {
        $questionId = $question_row['questionId'];
        $desc = $question_row['desc'];

        echo "<div class='question-block'>";
        echo "<div class='question-text'>" . htmlspecialchars($desc) . "</div>";

        // Fetch answers for this question
        $answer_sql = "SELECT * FROM `answer` WHERE `quizId` = '$quizId' AND `questionId` = '$questionId'";
        $answer_result = $conn->query($answer_sql);

        if ($answer_result->num_rows > 0) {
            while ($answer_row = $answer_result->fetch_assoc()) {
                $answerId = $answer_row['answerId'];
                $answerDesc = $answer_row['desc'];

                echo "<div class='answer-option'>";
                echo "<input type='radio' name='question_$questionId' value='$answerId' required> " . htmlspecialchars($answerDesc);
                echo "</div>";
            }
        } else {
            echo "<p>No answers found for this question.</p>";
        }

        echo "</div>";
    }

    echo "<br><input type='submit' value='Submit Quiz' class='default-button'>";
} else {
    echo "<p>No questions found in this quiz.</p>";
}

echo "</form>";
echo "</div><div class='bottom-div'></div>";

echo "</center>
    
</body>
</html>";

$conn->close();
?>
