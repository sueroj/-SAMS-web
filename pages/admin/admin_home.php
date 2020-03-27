<?php
session_start();
include_once "db/functions.php";
include_once "db/configure.php";
include_once "db/test_users.php";

if(!isset($_SESSION["loggedin"]))
{
header("location: /");
exit;
}

$database = new Database();
$configure = new Configure();

$configure->checkDb();

$output = "";
$displayFilterBtn = null;

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
	switch (checkData($_GET["view"]))
	{
        case "students":
            $output = $database->getData("students");
        break;
        case "lectures":
            $output = $database->getData("lectures");
        break;
        case "modules":
            $output = $database->getData("modules");
        break;
        case "attendance":
            $output = $database->getData("attendance");
            $displayFilterBtn = "<button class='edit_btn' id='filter_button' onclick='applyFilter()'>Apply Filter</button>";
        break;
        case "filter":
            $output = $database->getFilteredAttendance($_GET["filter"]);
            $displayFilterBtn = "<button class='edit_btn' id='filter_button' onclick='applyFilter()'>Apply Filter</button>";
        break;
        case "alerts":
            $output = $database->getData("alerts");
        break;
        case "rooms":
            $output .= $database->updateRoomUsage();
            $database->updateRoomFill();
            $output .= $database->getData("roomUsage");
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

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    switch (checkData($_POST["selection"]))
	{
        case "configure": 
            $output = $database->createDb()."<br>";
            $output .= $configure->createAttendance()."<br>";
            $output .= $configure->createLectures()."<br>";
            $output .= $configure->createModules()."<br>";
            $output .= $configure->createCourses()."<br>";
            $output .= $configure->createRooms()."<br>";
            $output .= $configure->createRoomUsage()."<br>";
            $output .= $configure->createStudents()."<br>";
            $output .= $configure->createLecturers()."<br>";
            $output .= $configure->createAdmins()."<br>";
            $output .= $database->insertAttendance();
        break;
        case "view":
            $output = $database->getData(checkData($_POST["record"]));
        break;
        case "testUsers":           //Test Users for development purposes only
            $output = TestUsers::addUsers();
            $output = TestUsers::generateUsers();
            $database->insertAttendance();
        break;
        case "testAttendance":           //Test Attendance for development purposes only
            $output = TestUsers::generateAttendance();
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
<html lang="en-US">

<head>
    <link rel="stylesheet" type="text/css" href="/css/admin_styles.css">
    <link rel="stylesheet" href="/css/jquery-ui.css">
       <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
    <title>SAMS | Admin homepage</title>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        function getAlerts()
        {
            var pulser = document.getElementById("pulser_button");
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                pulser.style.display = this.responseText;
            }
            };
            xhttp.open("GET", "get_alerts.php?t=" + Math.random(), true);
            xhttp.send();
        } 

        function updateAttendance()
        {
            $("#dialog").dialog({ resizable: false, width: 340, modal: true });
        }

        function applyFilter()
        {
            $("#filter").dialog({ resizable: false, width: 340, modal: true });
        }


    </script>
</head>
    
<body onload="getAlerts()">
    
<nav class="global_nav_bar">
    <div class="nav_bar">
        <a class="submit_btn" href="admin_home.php?view=logout">Log out</a> 
    </div>
    <div class="logo" title="Return to Home Page"><a href="/">SAMS</a></div>
    <div class="nav_bar"><?php echo $_SESSION["first"] . " " . $_SESSION["last"]; ?></div>
    <div class="nav_bar">Student Adminstration Management System</div>

</nav>

<div class="content">
    <nav class="v_nav_bar">
        <a class="v_nav" href="admin_home.php?view=students">Students</a>
        <a class="v_nav" href="admin_home.php?view=lectures">Lectures</a>
        <a class="v_nav" href="admin_home.php?view=modules">Modules</a>
        <a class="v_nav" href="admin_home.php?view=attendance">Attendance</a>
        <a class="v_nav" href="admin_home.php?view=rooms">Rooms</a>
        <a class="v_nav_pulser" id="pulser_button" href="admin_home.php?view=alerts" hidden>Alerts</a>
    </nav>

    <div>
        <div class="input_form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="text" name="record" placeholder="Search">
                    <select name="selection">
                        <option value="view">View Data</option>
                        <option value="configure">Configure Database</option>             
                        <option value="testUsers">Create Test Users</option>
                        <option value="testAttendance">Generate Attendance</option>
                    </select>
                    <button class="submit_btn" type="submit">Submit</button>
            </form>

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

            <div id="filter" title="Apply Filter" hidden>
                <form action="apply_filter.php" method="POST">
                    <label for="filter"><b>Attendance Filter</b></label>
                    <div class="slidecontainer">
                        <input name="filter" type="range" min="1" max="100" value="50" class="slider" id="slider">
                        <span id="sliderVal"></span>
                    </div>
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

<script>
var slider = document.getElementById("slider");
var output = document.getElementById("sliderVal");
output.innerHTML = slider.value;

slider.oninput = function() {
  output.innerHTML = this.value;
}
</script>

</body>
</html>

