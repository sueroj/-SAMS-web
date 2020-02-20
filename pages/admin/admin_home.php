<?php
session_start();
include_once "db/functions.php";
include_once "db/configure.php";
include_once "db/test_users.php";

checkDb();
$database = new Database();
$configure = new Configure();

$output = "";
$store = array();
$x = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	switch (checkData($_POST["selection"]))
	{
        case "Students":
            $output = $database->getData("students");
        break;
        case "Courses":
            $output = $database->getData("courses");
        break;
        case "Modules":
            $output = $database->getData("modules");
        break;
        case "Attendance":
            $database->insertAttendance();
            $output = $database->getData("attendance");
        break;
        case "Alerts":
            $output = $database->getData("alerts");
        break;
        case "Rooms":
            $output = $database->updateRoomCapacity()."<br>";
            $output .= $database->updateRoomFill()."<br>";
            $output .= $database->getData("roomCapacity");
        break;
		case "configure": 
            $output = $database->createDb()."<br>";
            $output .= $configure->createAttendance()."<br>";
            $output .= $configure->createLectures()."<br>";
            $output .= $configure->createModules()."<br>";
            $output .= $configure->createCourses()."<br>";
            $output .= $configure->createRooms()."<br>";
            $output .= $configure->createRoomCapacity()."<br>";
            $output .= $configure->createStudents()."<br>";
            $output .= $configure->createLecturers()."<br>";
            $output .= $configure->createAdmins()."<br>";
            $output .= $database->insertAttendance();
        break;
        case "insert":
            $output = $database->updateRecord(checkData($_POST["record"]));
        break;
        case "view":
            $output = $database->getData(checkData($_POST["record"]));
        break;
        case "delete":
            $output = $database->deleteRecord(checkData($_POST["record"]));
        break;
        case "drop":
            $output = $database->deleteTable();
        break;
        case "testUsers":           //Test Users for development purposes only
            $output = TestUsers::addUsers();
            $database->insertAttendance();
        break;
        case "testAttendance":           //Test Attendance for development purposes only
            $output = TestUsers::generateAttendance();
        break;
		default:
			$output = "Invalid option.";
	}
}

function checkDb()
{
    $database = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
    if ($database->connect_error){
        die("Connection failed: " . $conn->connect_error);	
    }

    //Create or verify DB exists
    $sql = "CREATE DATABASE samsdb";
    if ($database->query($sql) === TRUE){
        $output = "database samsdb created.";
    } else {
        $output = "Error creating database: " . $database->error;
    }
    return $output;
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
       <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
    <title>SAMS | Admin homepage</title>

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
    </script>
</head>
    
<body onload="getAlerts()">
    
<nav class="global_nav_bar">
    <div class="logo" title="Return to Home Page"><a href="/HTML/Admin/Admin%20homepage.html">SAMS</a></div>
    <img class="profilePicture" src="/images/joel.jpg" alt="User Profile Picture">
    <div class="nav_bar"><?php echo $_SESSION["first"] . " " . $_SESSION["last"]; ?></div> 
    <div class="nav_bar">Student Adminstration Management System</div>
</nav>

<div class="content">
    <nav class="v_nav_bar">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input class="v_nav" name="selection" type="submit" value="Students">
            <input class="v_nav" name="selection" type="submit" value="Courses">
            <input class="v_nav" name="selection" type="submit" value="Modules">
            <input class="v_nav" name="selection" type="submit" value="Attendance">
            <input class="v_nav" name="selection" type="submit" value="Rooms">
            <input class="v_nav_pulser" id="pulser_button" name="selection" type="submit" value="Alerts" hidden>
        </form>
    </nav>

    <div>
        <div class="input_form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="text" name="record" placeholder="Search">
                    <select name="selection">
                        <option value="view">View Data</option>
                   <!--     <option value="insert">Insert Data</option> -->
                   <!--     <option value="delete">Delete Data</option> -->
                        <option value="configure">Configure Database</option>
                        
                        <option value="testUsers">Create Test Users</option>
                        <option value="testAttendance">Generate Attendance</option>
                    </select>
                    <button class="submit_btn" type="submit">Submit</button>
            </form>
        </div>

        <div id="data" class="data">
            <table>

            <?php echo $output; ?>

            </table>
        </div>
    </div>


</div>

<?php   //FOR TESTING & DEBUG USE ONLY
        $conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
        if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
        } else { echo "Database Status: Connected. ";}
?>

</body>
</html>

