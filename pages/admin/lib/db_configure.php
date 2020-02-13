<?php declare(strict_types=1);
include "static/static_data.php";

class Configure
{
    private $database;

	function __construct()
	{
		$this->database = self::connectDb();
    }
    
    function createStudents()
    {	
    //Verify if database "samdb" exists in MySQL first; if not, create one.
    $sql = "CREATE DATABASE samsdb";
    if ($this->database->query($sql) === TRUE){
        $output =  "New database samsdb created.\n";
    }

    $sql = "CREATE TABLE students (
    id INT(7) UNSIGNED PRIMARY KEY,
    first VARCHAR(30) NOT NULL,
    last VARCHAR(30) NOT NULL,
    course VARCHAR(30) NOT NULL,
    account INT(1) UNSIGNED,
    passwd VARCHAR(40) NOT NULL
    )";

    if ($this->database->error !== "") {
        $output = $this->database->error;
        } else {
                $output = "Table students created.";
                }
    return $output;
    }

    function createCourses()
    {
    $sql = "CREATE TABLE courses (
        course VARCHAR(30) PRIMARY KEY NOT NULL,
        code VARCHAR(4),
        enrolled INT(10),
        attendance INT(5),
        start_date DATE,
        end_date DATE,
        weeks INT(5)
        )";
        $this->database->query($sql);

        $courseName = Courses::courseName;
        $courseCode = Courses::courseCode;
        $courseWeeks = Courses::courseWeeks;

        for ($x=0; $x<count($courseName); $x++)
        {
            $sql = "INSERT INTO courses (course, code, weeks)
            VALUES ('$courseName[$x]', '$courseCode[$x]', '$courseWeeks[$x]')";
            $this->database->query($sql);
        }
    
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table courses created.";
                    }
        return $output;
    }

    function createModules()
    { //Lecture = DayofWeek SQL: DAYOFWEEK(date)
    $sql = "CREATE TABLE modules (
        module VARCHAR(30) PRIMARY KEY NOT NULL,
        course VARCHAR(30),
        room VARCHAR(10),
        lectureDay VARCHAR(10),
        time INT(4),
        start_date DATE,
        end_date DATE,
        weeks INT(3),
        attendance INT(5),
        enrolled INT(5)
        )";
        $this->database->query($sql);

        $moduleName = Modules::moduleName;
        $moduleCourseCode = Modules::moduleCourseCode;

        for ($x=0; $x<count($moduleName); $x++)
        {
            $sql = "INSERT INTO modules (module, course, weeks)
            VALUES ('$moduleName[$x]','$moduleCourseCode[$x]', 12)";
            $this->database->query($sql);
        }

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table modules created.";
                    }
        return $output;
    }

    function createAttendance()
    {//Attendance = 12 character string that will represent true or false.

        $sql = "CREATE TABLE attendance (
        record INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
        id INT(7) NOT NULL,
        module VARCHAR(30),
        room VARCHAR(10),
        week1 BOOL, week2 BOOL, week3 BOOL, week4 BOOL, week5 BOOL, week6 BOOL,
        week7 BOOL, week8 BOOL, week9 BOOL, week10 BOOL, week11 BOOL, week12 BOOL
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table attendance created.";
                    }
        return $output;
    }

    function createRooms()
    {
        $sql = "CREATE TABLE rooms (
        room VARCHAR(10) PRIMARY KEY NOT NULL,
        attendance INT(5),
        enrolled INT(5),
        capacity INT(5)
        )";
        $this->database->query($sql);

        $roomName = Rooms::roomName;
        $roomCapacity = Rooms::roomCapacity;

        for ($x=0; $x<count($roomName); $x++)
        {
            $room = $roomName[$x];
            $attendance = null;         //temporary until algorithm is implemented
            $enrolled = null;           //temporary until algorithm is implemented
            $capacity = $roomCapacity[$x];

            $sql = "INSERT INTO rooms (room, attendance, enrolled, capacity)
            VALUES ('$room', '$attendance', '$enrolled', '$capacity')";
            $this->database->query($sql);
        }
		
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table rooms created.";
                    }
        return $output;

    }

    function calcDateRange()
    {
        $sql = "SELECT start_date, weeks FROM modules";
        $result = $this->database->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {      
                $sql = "INSERT INTO modules (end_date)
                VALUES ('$row[start_date]', INTERVAL '$row[weeks]' WEEK)";
                $this->database->query($sql);
            }
        } else {
            $output = "Error: " . $sql . "<br>" . $this->database->error;
        }
        return $output;
    }

    //Connect to Database, used by many other functions.
    function connectDb()
    {
        $conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
        if ($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }


}


?>