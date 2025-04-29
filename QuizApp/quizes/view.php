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
    <title>All Quizzes</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <link rel='stylesheet' type='text/css' href='../Styles/main.css'>
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
        .button-container {
            margin-top: 10px;
        }
        .view-link, .leaderboard-link {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .leaderboard-link {
            background-color: #28a745;
        }
        .view-link:hover {
            background-color: #0056b3;
        }
        .leaderboard-link:hover {
            background-color: #218838;
        }
    </style>
</head>
<body><center>";

echo "<table width='100%'><tr>
        <td align='left'><a href='../Homepage.php'><button class='default-button'>Home</button></a></td>
    </tr></table>";

echo "<div class='main-div default-shadow'>";
echo "<h1>All Available Quizzes</h1><hr>";

if ($quiz_result->num_rows > 0) {
    while ($quiz_row = $quiz_result->fetch_assoc()) {
        $quizId = $quiz_row['quizId'];
        $topic = $quiz_row['topic'];
        $time = $quiz_row['quizTime'];

        echo "<div class='quiz-block'>";
        echo "<div class='quiz-title'>" . htmlspecialchars($topic) . "</div>";
        echo "<div>Quiz Time: " . htmlspecialchars($time) . " minutes</div>";
        
        echo "<div class='button-container'>";
        echo "<a class='view-link' href='detailed-view.php?quizId=$quizId'>View Quiz</a>";
        echo "<a class='leaderboard-link' href='leaderboard.php?quizId=$quizId'>Leaderboard</a>";
        echo "</div>";

        echo "</div>";
    }
} else {
    echo "<p>No quizzes found!</p>";
}

echo "</div><div class='bottom-div'></div>";

echo "</center>
    
</body>
</html>";

$conn->close();
?>
