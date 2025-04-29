<?php
session_start();

$userId = $_SESSION['userId'];
$topic = $_SESSION['new_quiz_topic'];
$number_of_questions = $_SESSION['new_quiz_number_of_questions'];
$time_hours = $_SESSION['new_quiz_time_hours'];
$time_minutes = $_SESSION['new_quiz_minutes'];

$db_servername = $_SESSION['db_servername'];
$db_username = $_SESSION['db_username'];
$db_password = $_SESSION['db_password'];
$db_name = $_SESSION['db_name'];
$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Create tables if not exist
$quiz_info = "SELECT `quizId` FROM `quiz`";
if ($conn->query($quiz_info) === false) {
    $conn->query("CREATE TABLE `quiz` (
        `quizId` VARCHAR(16) NOT NULL PRIMARY KEY,
        `topic` VARCHAR(64) NOT NULL,
        `userId` VARCHAR(16) NOT NULL,
        `quizTime` VARCHAR(8),
        `visibilty` INT(1) NOT NULL)");

    $conn->query("CREATE TABLE `question` (
        `quizId` VARCHAR(16) NOT NULL,
        `questionId` VARCHAR(16) NOT NULL,
        `correctAnswerId` VARCHAR(16) NOT NULL,
        `desc` VARCHAR(500) NOT NULL,
        CONSTRAINT PK_question PRIMARY KEY (`quizId`, `questionId`))");

    $conn->query("CREATE TABLE `answer` (
        `quizId` VARCHAR(16) NOT NULL,
        `questionId` VARCHAR(16) NOT NULL,
        `answerId` VARCHAR(16) NOT NULL,
        `desc` VARCHAR(100) NOT NULL,
        CONSTRAINT PK_answer PRIMARY KEY (`quizId`, `questionId`, `answerId`))");

    $conn->query("CREATE TABLE `attempt` (
        `quizId` VARCHAR(16) NOT NULL,
        `userId` VARCHAR(16) NOT NULL,
        `marks` INT(3) NOT NULL,
        CONSTRAINT PK_attempt PRIMARY KEY (`quizId`, `userId`))");
}

// UI and response display
echo "<!DOCTYPE html><html>
<head>
    <title>Save Quiz</title>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='../../Styles/main.css'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8faff;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2b2e6c;
        }
        .message {
            font-size: 18px;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .success {
            background-color: #e1f9e3;
            color: #2b6b2b;
        }
        .error {
            background-color: #ffe1e1;
            color: #a10000;
        }
        .default-button {
            padding: 10px 20px;
            background-color: #4a4eff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        .default-button:hover {
            background-color: #3b3fde;
        }
    </style>
</head>
<body>
<div class='container'>
    <a href='../../Homepage.php'><button class='default-button'>Home</button></a>
    <h2>Saving Your Quiz...</h2>";

$quiz = $conn->query("SELECT `quizId` FROM `quiz`");
$quiz_count = $quiz->num_rows + 1;
$quizId = "QZ" . str_pad($quiz_count, 4, '0', STR_PAD_LEFT); // e.g., QZ0001

$insert_quiz_sql = "INSERT INTO `quiz` 
(`quizId`, `topic`, `userId`, `quizTime`, `visibilty`) 
VALUES 
('$quizId', '" . mysqli_real_escape_string($conn, $topic) . "', '$userId', '$time_hours:$time_minutes', 1)";

if ($conn->query($insert_quiz_sql) === true) {
    for ($q = 1; $q <= $number_of_questions; $q++) {
        $question_text = $_POST['question_num_' . $q];
        $correct_answer_number = $_POST['answer_to_question_' . $q];
        $questionId = $q;

        $conn->query("INSERT INTO `question` 
        (`quizId`, `questionId`, `correctAnswerId`, `desc`)
        VALUES
        ('$quizId', '$questionId', '$correct_answer_number', '" . mysqli_real_escape_string($conn, $question_text) . "')");

        for ($a = 1; $a <= 4; $a++) {
            $answer_text = $_POST[$a . '_opption_to_' . $q];
            $conn->query("INSERT INTO `answer` 
            (`quizId`, `questionId`, `answerId`, `desc`)
            VALUES
            ('$quizId', '$questionId', '$a', '" . mysqli_real_escape_string($conn, $answer_text) . "')");
        }
    }

    echo "<div class='message success'>✅ Quiz <strong>'$topic'</strong> created successfully with ID: <strong>$quizId</strong>.</div>";
} else {
    echo "<div class='message error'>❌ Oops! Quiz upload failed. Please try again.</div>";
}

echo "</div></body></html>";
?>
