<?php 
//checking to see if user is logged in, and if they arent then redirecting to the login page
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: /');
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/functions.php";

$database = new Database();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/css/student_styles.css">
       <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>James Student Home</title>
</head>
    
<body>
    
<nav class="global_nav_bar">
    <div class="nav_bar_btn">
        <a class="submit_btn" href="logout.php">Log out</a> 
    </div>
    <div class="logo" title="Return to Home Page"><a href="/">SAMS</a></div>
    <div class="nav_bar">Student Adminstration Management System</div>
</nav> 
        
<h1 class="title">Welcome <?php echo $_SESSION["first"] . " " . $_SESSION["last"]; ?></h1>

<div class="column">
    <div class="section">
        <img class="profile_img" src="/images/linkedin%20pic.jpg" alt="Profile pic placeholder">
        <h1><?php echo $_SESSION["userId"] ?></h1>
        <p><?php echo $_SESSION["first"] . " " . $_SESSION["last"]; ?></p>
        <p><?php echo $_SESSION["courseCode"] ?></p>
        <p>Anglia Ruskin University</p>       
    </div>
</div>
    
<div class="column">
    <div class="section">
        <h1>Modules</h1>

        <div class="moduletitle">
            <p><a class="module_link" href="attendance.php?module=CS&trimester=TRI1">Secure Systems</a></p>
            <?php $attendance = $database->getStudentAttendance("CS", "TRI1", $_SESSION["userId"]);
                  echo $attendance === false ? "N/A" : $attendance["attended"] . " " . $attendance["percentAttended"]; ?>
            <p><a class="module_link" href="attendance.php?module=DWA&trimester=TRI1">Web Applications</a></p>
            <?php $attendance = $database->getStudentAttendance("DWA", "TRI1", $_SESSION["userId"]);
                  echo $attendance === false ? "N/A" : $attendance["attended"] . " " . $attendance["percentAttended"]; ?>
            <p><a class="module_link" href="attendance.php?module=RM&trimester=TRI1">Research Methods</a></p>
            <?php $attendance = $database->getStudentAttendance("RM", "TRI1", $_SESSION["userId"]);
                  echo $attendance === false ? "N/A" : $attendance["attended"] . " " . $attendance["percentAttended"]; ?>
        </div>
    </div> 
</div>

</body>
</html>

