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

// Get quizId from POST or GET
if (isset($_POST['quizId'])) {
    $quizId = $_POST['quizId'];
} elseif (isset($_GET['quizId'])) {
    $quizId = $_GET['quizId'];
} else {
    die("Quiz ID not provided.");
}

// Get quiz information
$quiz_sql = "SELECT * FROM `quiz` WHERE `quizId` = '$quizId'";
$quiz_result = $conn->query($quiz_sql);

if ($quiz_result->num_rows == 0) {
    die("Quiz not found.");
}

$quiz_row = $quiz_result->fetch_assoc();
$topic = $quiz_row['topic'];
$time = $quiz_row['quizTime'];

echo "<!DOCTYPE html><html>
<head>
    <title>Quiz Details</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <link rel='stylesheet' type='text/css' href='../Styles/main.css'>
    <style>
        .question-block {
            margin: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }
        .answer {
            margin-left: 20px;
            padding: 5px;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body><center>";

echo "<table width='100%'><tr>
        <td align='left'><a href='../Homepage.php'><button class='default-button'>Home</button></a></td>
    </tr></table>";

echo "<div class='main-div default-shadow'>";
echo "<h1>Quiz: " . htmlspecialchars($topic) . "</h1>";
echo "<h3>Time Allowed: " . htmlspecialchars($time) . " minutes</h3><hr>";

// Get questions
$question_sql = "SELECT * FROM `question` WHERE `quizId` = '$quizId' ORDER BY `questionId` ASC";
$question_result = $conn->query($question_sql);

while ($question_row = $question_result->fetch_assoc()) {
    $questionId = $question_row['questionId'];
    $question_desc = $question_row['desc'];
    $correctAnswerId = $question_row['correctAnswerId'];

    echo "<div class='question-block'>";
    echo "<h3>Question " . $questionId . ": " . htmlspecialchars($question_desc) . "</h3>";

    // Get answers for this question
    $answer_sql = "SELECT * FROM `answer` WHERE `quizId` = '$quizId' AND `questionId` = '$questionId' ORDER BY `answerId` ASC";
    $answer_result = $conn->query($answer_sql);

    while ($answer_row = $answer_result->fetch_assoc()) {
        $answerId = $answer_row['answerId'];
        $answer_desc = $answer_row['desc'];

        if ($answerId == $correctAnswerId) {
            echo "<div class='answer correct'>✔ Option $answerId: " . htmlspecialchars($answer_desc) . "</div>";
        } else {
            echo "<div class='answer'>Option $answerId: " . htmlspecialchars($answer_desc) . "</div>";
        }
    }

    echo "</div>";
}

echo "</div><div class='bottom-div'></div>";

echo "</center>
   
</body>
</html>";

$conn->close();
?>
