<?php
session_start();

$userId = $_SESSION['userId'];

$topic = $_POST['topic'];
$number_of_questions = $_POST['number_of_questions'];
$time_hours = $_POST['time_hours'];
$time_minutes = $_POST['time_minutes'];

echo "<!DOCTYPE html><html>
<head>
    <title>New Quiz</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <link rel='stylesheet' type='text/css' href=''>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8ff;
            margin: 0;
            padding: 0;
        }
        .main-div {
            margin: 20px auto;
            width: 90%;
            max-width: 900px;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .question-block {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #fefefe;
        }
        textarea {
            width: 100%;
            border-radius: 8px;
            padding: 10px;
            resize: vertical;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input.inputTypeText {
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .default-button {
            padding: 10px 20px;
            background-color: #4a4eff;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .default-button:hover {
            background-color: #3236e0;
        }
        .align-right {
            text-align: right;
            padding-right: 10px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            padding: 10px;
        }
    </style>
</head>
<body>
<center>
    <table class='header-table'>
        <tr>
            <td align='left'>
                <a href='../../Homepage.php'><button class='default-button'>Home</button></a>
            </td>
        </tr>
    </table>

    <div class='main-div default-shadow'>
        <h2>Creating Quiz: " . htmlspecialchars($topic) . "</h2>
        <form action='save.php' method='post'>";

$t_num = 1;
$t_num_of_aw = 1;
$num_of_answers = 4;

while ($t_num <= $number_of_questions) {
    echo "<div class='question-block'>";
    echo "<h3>Question $t_num</h3>";
    echo "<textarea name='question_num_$t_num' placeholder='Type question here...' required></textarea><br><br>";

    while ($t_num_of_aw <= $num_of_answers) {
        echo "<label class='align-right'>Answer $t_num_of_aw</label><br>
              <input class='inputTypeText' type='text' name='{$t_num_of_aw}_opption_to_{$t_num}' required><br><br>";
        $t_num_of_aw++;
    }

    echo "<label class='align-right'>Correct Answer (Enter number from 1 to $num_of_answers)</label><br>
          <input class='inputTypeText' type='number' name='answer_to_question_$t_num' required min='1' max='4'><br>";

    echo "</div>";

    $t_num_of_aw = 1;
    $t_num++;
}

echo "<br><input type='submit' name='submit' value='Submit Quiz' class='default-button'>
        </form>
    </div>
</center>
</body>
</html>";

$_SESSION['new_quiz_topic'] = $topic;
$_SESSION['new_quiz_number_of_questions'] = $number_of_questions;
$_SESSION['new_quiz_time_hours'] = $time_hours;
$_SESSION['new_quiz_minutes'] = $time_minutes;
?>
