<?php
session_start();
include_once "db/functions.php";
include_once "db/configure.php";
include_once "db/test_users.php";

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
            $output = $database->getDataLecturer("modules");
        break;
        case "lectures":

            $output = $database->getDataLecturer("lectures");
        break;
        case "attendance":

            $output = $database->getDataLecturer("attendance");
        break;
        case "studentattend";

            $output = $database->getDataLecturer("studentattend");
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

/*if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $record = $database->viewModule($_POST["module"]);
}*/

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
 		<div class="logo" title="Return to Home Page">SAMS</div>
    	<div class="nav_bar">Student Adminstration Management System</div>
    </nav>

<div class="content">
    <nav class="v_nav_bar">
        <a class="v_nav" href="lecturer_home.php?view=modules">Modules</a>
        <a class="v_nav" href="lecturer_home.php?view=lectures">Lectures</a>
        <a class="v_nav" href="lecturer_home.php?view=attendance">Attendance</a>
        <a class="v_nav" href="lecturer_home.php?view=studentattend">Student Attendance</a>
    </nav>
    
    <div class="update_attendance">

    <button class="edit_btn" onclick="updateAttendance()">Update Attendance</button>
            <?php echo $displayFilterBtn ?>
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

</body>
</html>