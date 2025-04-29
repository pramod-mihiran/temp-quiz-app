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

// Fetch all quizzes
$quiz_sql = "SELECT * FROM `quiz` ORDER BY `quizId` ASC";
$quiz_result = $conn->query($quiz_sql);

echo "<!DOCTYPE html><html>
<head>
    <title>Attempt Quiz</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <link rel='stylesheet' type='text/css' href='../Style/main.css'>
    <style>
        .quiz-block {
            margin: 20px auto;
            padding: 15px;
            width: 80%;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
            text-align: left;
        }
        .quiz-title {
            font-size: 20px;
            font-weight: bold;
        }
        .action-button {
            margin-top: 10px;
            display: inline-block;
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .action-button:hover {
            background-color: #218838;
        }
        .marks-text {
            margin-top: 10px;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body><center>";

echo "<table width='100%'><tr>
        <td align='left'><a href='../Homepage.php'><button class='default-button'>Home</button></a></td>
    </tr></table>";

echo "<div class='main-div default-shadow'>";
echo "<h1>Attempt Quizzes</h1><hr>";

if ($quiz_result->num_rows > 0) {
    while ($quiz_row = $quiz_result->fetch_assoc()) {
        $quizId = $quiz_row['quizId'];
        $topic = $quiz_row['topic'];
        $time = $quiz_row['quizTime'];

        // Check if this user already attempted this quiz
        $attempt_sql = "SELECT * FROM `attempt` WHERE `quizId` = '$quizId' AND `userID` = '$userId'";
        $attempt_result = $conn->query($attempt_sql);

        echo "<div class='quiz-block'>";
        echo "<div class='quiz-title'>" . htmlspecialchars($topic) . "</div>";
        echo "<div>Quiz Time: " . htmlspecialchars($time) . " minutes</div>";

        if ($attempt_result->num_rows > 0) {
            // Already attempted, show marks
            $attempt_row = $attempt_result->fetch_assoc();
            $marks = $attempt_row['marks'];
            echo "<div class='marks-text'>✅ Already Attempted | Marks: $marks</div>";
        } else {
            // Not attempted, show attempt button
            echo "<a class='action-button' href='start-attempt.php?quizId=$quizId'>Attempt Quiz</a>";
        }

        echo "</div>";
    }
} else {
    echo "<p>No quizzes available to attempt!</p>";
}

echo "</div><div class='bottom-div'></div>";

echo "</center>
    
</body>
</html>";

$conn->close();
?>
