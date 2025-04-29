<?php
session_start();

// Database connection
$db_servername = $_SESSION['db_servername'];
$db_username = $_SESSION['db_username'];
$db_password = $_SESSION['db_password'];
$db_name = $_SESSION['db_name'];

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get quiz ID from URL
if (!isset($_GET['quizId'])) {
    die("Quiz ID not provided.");
}
$quizId = $_GET['quizId'];

// Fetch leaderboard data
// $sql = "SELECT attempt.userID, attempt.marks, user.firstName, user.lastName
//         FROM attempt
//         INNER JOIN user ON attempt.userID = user.userId
//         WHERE attempt.quizId = '$quizId'
//         ORDER BY attempt.marks DESC, user.firstName ASC";

$sql = "SELECT attempt.userId, attempt.marks
        FROM attempt
        WHERE attempt.quizId = '$quizId'
        ORDER BY attempt.marks DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href='../Styles/main.css'>
    <style>
        .leaderboard-table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .leaderboard-table th, .leaderboard-table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ccc;
        }
        .leaderboard-table th {
            background-color: #f4f4f4;
            font-size: 18px;
        }
        .rank-1 {
            background-color: gold;
            font-weight: bold;
        }
        .rank-2 {
            background-color: silver;
            font-weight: bold;
        }
        .rank-3 {
            background-color: #cd7f32;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <center>
        <table width="100%">
            <tr>
                <td align="left">
                    <a href="../Homepage.php"><button class="default-button">Home</button></a>
                </td>
                <td align="right">
                    <a href="view.php">
                        <button class="default-button">Back to Quiz</button>
                    </a>
                </td>
            </tr>
        </table>

        <div class="main-div default-shadow">
            <h1>Leaderboard - Quiz ID: <?php echo htmlspecialchars($quizId); ?></h1><hr>

            <?php
            if ($result->num_rows > 0) {
                echo "<table class='leaderboard-table'>";
                echo "<tr><th>Rank</th><th>Name</th><th>Marks</th></tr>";

                $rank = 1;
                while ($row = $result->fetch_assoc()) {
                    $class = "";
                    if ($rank == 1) $class = "rank-1";
                    elseif ($rank == 2) $class = "rank-2";
                    elseif ($rank == 3) $class = "rank-3";

                    $name = $row['userId'];//htmlspecialchars($row['firstName'] . " " . $row['lastName']);
                    $marks = $row['marks'];

                    echo "<tr class='$class'>
                            <td>#$rank</td>
                            <td>$name</td>
                            <td>$marks</td>
                        </tr>";
                    $rank++;
                }

                echo "</table>";
            } else {
                echo "<p>No attempts found for this quiz yet.</p>";
            }
            ?>

        </div>
        <div class="bottom-div"></div>
    </center>
</body>

</html>

<?php
$conn->close();
?>
