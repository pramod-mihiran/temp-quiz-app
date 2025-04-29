<?php

session_start();

  $_SESSION['userId'] = "AS100100";

	$userId=$_SESSION['userId'];

?>

<!-- --------------------------------------------------------------------------------------------- -->

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Homepage</title>
    <link rel='stylesheet' href='./Styles/main.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>

</head>

<body>
    <div class='dashboard'>
        <!-- Sidebar -->
        <div class='sidebar'>
          <h2>Quiz App</h2>
          <ul>
            <li id='dashboard-tab' class='active'><i class='fas fa-home'></i> Dashboard</li>
            <a href='./quizes/'><li ><i class='fas fa-book'></i> All Quizes</li></a>
            <li id='messages-tab'><i class='fas fa-envelope'></i> Messages</li>
            <li id='schedule-tab'><i class='fas fa-calendar'></i> Schedule</li>
            <li id='settings-tab'><i class='fas fa-cog'></i> Settings</li>
          </ul>
        </div>

        <!-- Main Content -->
        <div class='main-content'>
            <header>
              <h1>Dashboard</h1>
            </header>
        </div>

        <!-- Right Panel -->
        <div class='right-panel'>
            
        </div>
    </div>
    <script src='AllQuizesJS.js'></script>
</body>
</html>


<!-- uploarding--------------------------------------------------------------------------------------------- -->



?>