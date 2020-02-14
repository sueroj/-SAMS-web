<?php declare(strict_types=1);
include "static_data.php";
include "globals.php";

class Configure
{
    private $database;

	function __construct()
	{
		$this->database = self::connectDb();
    }
    function checkDb()
    {
        //Verify if database "samdb" exists in MySQL first; if not, create one.
        $sql = "CREATE DATABASE samsdb";
        if ($this->database->query($sql) === TRUE){
        $output =  "New database samsdb created.\n";
        }
    }

    function createAttendance()
    {//Attendance = 12 character string that will represent true or false.

        $sql = "CREATE TABLE attendance (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        lectureId VARCHAR(30),
        studentId VARCHAR(10),
        attended BOOL
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table attendance created.";
                    }
        return $output;
    }

    function createLectures()
    {

        $sql = "CREATE TABLE lectures (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        module VARCHAR(30),
        time INT(4) UNSIGNED,
        week INT(3) UNSIGNED,
        lecturer VARCHAR(30),
        room VARCHAR(10)
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table lectures created.";
                    }
        return $output;
    }

    function createModules()
    { //Lecture = DayofWeek SQL: DAYOFWEEK(date)
    $sql = "CREATE TABLE modules (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(5),
        name VARCHAR(30),
        course VARCHAR(10),
        weeks INT(3) UNSIGNED
        )";
        $this->database->query($sql);

        
        $moduleCourseCode = Modules::moduleCourseCode;
        $moduleName = Modules::moduleName;

        for ($x=0; $x<count($moduleName); $x++)
        {
            $sql = "INSERT INTO modules (code, name, weeks)
            VALUES ('$moduleCourseCode[$x]','$moduleName[$x]', 12)";
            $this->database->query($sql);
        }

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table modules created.";
                    }
        return $output;
    }

    function createCourses()
    {
    $sql = "CREATE TABLE courses (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(4),
        name VARCHAR(30),
        start_date DATE,
        end_date DATE
        )";
        $this->database->query($sql);

        $courseCode = Courses::courseCode;
        $courseName = Courses::courseName;

        for ($x=0; $x<count($courseName); $x++)
        {
            $sql = "INSERT INTO courses (code, name)
            VALUES ('$courseCode[$x]', '$courseName[$x]')";
            $this->database->query($sql);
        }
    
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table courses created.";
                    }
        return $output;
    }

    function createRooms()
    {
        $sql = "CREATE TABLE rooms (
        id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        room VARCHAR(10) NOT NULL,
        fill INT(5) UNSIGNED NOT NULL,
        capacity INT(5) UNSIGNED NOT NULL
        )";
        $this->database->query($sql);

         $roomName = Rooms::roomName;
         $roomCapacity = Rooms::roomCapacity;

         for ($x=0; $x<count($roomName); $x++)
         {
             $room = $roomName[$x];
             $capacity = $roomCapacity[$x];

            $sql = "INSERT INTO rooms (room, capacity)
             VALUES ('$room', '$capacity')";
             $this->database->query($sql);
         }
		
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table rooms created.";
                    }
        return $output;
    }
    
    function createStudents()
    {	
        $sql = "CREATE TABLE students (
        id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        studentId INT(7) UNSIGNED NOT NULL,
        first VARCHAR(30) NOT NULL,
        last VARCHAR(30) NOT NULL,
        course VARCHAR(30) NOT NULL,
        account INT(1) UNSIGNED,
        passwd VARCHAR(40) NOT NULL
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table students created.";
                    }
        return $output;
    }

    function createLecturers()
    {	
        $sql = "CREATE TABLE lecturers (
        id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        staffId INT(7) UNSIGNED NOT NULL,
        first VARCHAR(30) NOT NULL,
        last VARCHAR(30) NOT NULL,
        account INT(1) UNSIGNED,
        passwd VARCHAR(40) NOT NULL
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table lecturers created.";
                    }
        return $output;
    }

    function createAdmins()
    {	
        $sql = "CREATE TABLE admins (
        id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        staffId INT(7) UNSIGNED NOT NULL,
        first VARCHAR(30) NOT NULL,
        last VARCHAR(30) NOT NULL,
        account INT(1) UNSIGNED,
        passwd VARCHAR(40) NOT NULL
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table admins created.";
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