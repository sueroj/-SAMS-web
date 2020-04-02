<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: /');
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/globals.php";

$database = new Database();
$moduleCode = $_GET["module"];
$trimester = $_GET["trimester"];
$lectureId = $database->getlectureId($moduleCode, $trimester);
//Gets attendance table graphic and attendance percentage.
$attendance = $database->getStudentAttendance($moduleCode, $trimester, $_SESSION["userId"]);
$moduleName = $database->getModuleName($moduleCode);

//Get if lectures are scheduled, else hide the table
if ($lectureId !== false)
{
    echo "<script> var displayTable = 'block'; </script>";
}

//attendanceTable(): -Draws the table. Gets its data from lectures table via db functions.
function attendanceTable()
{
    $database = new Database();
    $moduleCode = $_GET["module"];
    $output = "";

    for ($week=1; $week<13; $week++)
    {
        $lecture = $database->getLecture($week, $moduleCode);
        $output .= "<tr>
                  <td>$week</td>
                  <td>" . $lecture["date"] . "</td>
                  <td>" . $lecture["start"] . "</td>
                  <td>" . $lecture["stop"] . "</td>
                    <td>
                        <label for='present'>
                            <input type='radio' name=$week value='present' required> Present
                        </label>
                        <label for='absent'>
                            <input type='radio'  name=$week value='absent' required> Absent
                        </label>
                    </td>
                </tr>";
    }
    return $output;
}

//Form submit:-Updates attendance table via db functions.
//            -Refreshes attendance table graphic and attendance percentage
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    for ($week=1; $week<13; $week++)
    {
        if ($_POST[$week] == "present") {
            $database->updateAttendance($lectureId, $_SESSION["userId"], $week, 1);
        } else if ($_POST[$week] == "absent") {
            $database->updateAttendance($lectureId, $_SESSION["userId"], $week, 0);
        } else {
            echo "An error has occurred.";
        }
    }
    $attendance = $database->getStudentAttendance($moduleCode, $trimester, $_SESSION["userId"]);
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="/css/attendance_styles.css" type="text/css" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>James Secure Systems</title>
<script>
    function checkLectures()
    {
        var form = document.getElementById("attendance");
            attendance.style.display = displayTable;
    } 
</script>
</head>
    
<body onload=checkLectures()>
        
<nav class="global_nav_bar">
    <div class="nav_bar">
        <a class="submit_btn" href="logout.php">Log out</a>    
    </div>
    <div class="logo" title="Return to Home Page"><a href="student_home.php">SAMS</a></div>
    <div class="nav_bar">Student Adminstration Management System</div>
</nav>
        
<h1 class="title"><?php echo $moduleName; ?></h1>    

<form id="attendance" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?module=" . $moduleCode . "&trimester=" . $trimester; ?>" method="post" hidden>
    <table>
        <tbody>
            <tr>
                <th>Week</th>
                <th>Date</th>
                <th>Time</th>
                <th>Room</th>
                <th>Attendance</th>
            </tr>
            <!-- Draw rest of table data & buttons -->
            <?php echo $lectureId === false ? "No lectures are scheduled for this module. Please check again soon." : attendanceTable(); ?>
        </tbody>
    </table>
     
<br>
<input type="submit" name="submit" value="Mark Attendance"/>
</form>
        
<div class="statsbox">
    <!-- Display attendance data from database -->
    <p><?php echo $lectureId === false ? "No lectures are scheduled for this module. Please check again soon." : "Attendance: " . $attendance["attended"] . " " . $attendance["percentAttended"] ?> </p>
</div>
        
</body>
</html>