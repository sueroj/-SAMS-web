<?php
session_start();
if(!isset($_SESSION["loggedin"]))
{
header("location: /");
exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/configure.php";

$database = new Database();
$configure = new Configure();

$configure->checkDb();

$output = "";
$record = "";
$displayFilterBtn = null;


if ($_SERVER["REQUEST_METHOD"] == "GET")
{
	switch (checkData($_GET["view"]))
	{
        case "modules":
            //$output = $database->getModules();
            $output = $database->getData("modules");
        break;
        case "lectures":
            $output = $database->getData("lectures");
        break;
        case "classes":
            $output = $database->getData("classes");
        break;
        case "attendance":

            $output = $database->getData("attendance");
        break;
        case "logout":
            session_unset();
            session_destroy();
            header("location: /");
            die();
        break;
		default:
			$output = "Invalid option.";
	}
}

function checkData($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


?>

<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="/css/lecturer_styles.css"/>
    <link rel="stylesheet" href="/css/jquery-ui.css">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SAMS | Log In</title>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>

    function updateAttendance()
        {
            $("#dialog").dialog({ resizable: false, width: 340, modal: true });
        }

    </script>
    

</head>

<body>
<nav class="global_nav_bar">
    <div class="nav_bar">
        <a class="submit_btn" href="lecturer_home.php?view=logout">Log out</a> 
    </div>
    <div class="logo" title="Return to Home Page"><a href="/">SAMS</a></div>
    <div class="nav_bar"><?php echo $_SESSION["first"] . " " . $_SESSION["last"]; ?></div>
    <div class="nav_bar">Student Adminstration Management System</div>
</nav>

<div class="content">
    <nav class="v_nav_bar">
        <a class="v_nav" href="lecturer_home.php?view=modules">Modules</a>
        <a class="v_nav" href="lecturer_home.php?view=classes">Classes</a>
        <a class="v_nav" href="lecturer_home.php?view=lectures">Lectures</a>
        <a class="v_nav" href="lecturer_home.php?view=attendance">Attendance</a>
    </nav>
    <div>
        <div class="update_attendance">
            <button class="edit_btn" onclick="updateAttendance()">Update Attendance</button>
            <!-- <?php //echo $displayFilterBtn ?> -->
                <div id="dialog" title="Update Attendance" hidden>
                    <form action="update_attendance.php" method="POST">
                        <label for="lectureId"><b>Lecture ID</b></label>
                            <input type="text" name="lectureId" required>
                        <label for="studentId"><b>Student ID</b></label>
                            <input type="text" name="studentId" required>
                        <label for="week"><b>Week</b></label>
                            <input type="text" name="week" required>
                        <label for="newAttendance"><b>Attendance Status</b><br></label>
                            <select class="attendance_select" name="newAttendance">
                                <option value="attended">Attended</option>
                                <option value="absent">Absent</option>
                            </select><br>
                        <button class="submit_btn" type="submit">Submit</button>
                    </form>
                </div>
        </div>

        <div id="data" class="data">
                <table>

                <?php echo $output; ?>

                </table>
        </div>
    </div>
</div>


</body>
</html>