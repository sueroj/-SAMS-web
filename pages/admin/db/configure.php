<?php declare(strict_types=1);
include_once "static_data.php";
include_once "globals.php";
include_once "functions.php";

class Configure
{
    private $database;

	function __construct()
	{
		$this->database = self::connectDb();
    }

    function __deconstruct()
	{
		$this->database->close();
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
        lectureId VARCHAR(30) NOT NULL,
        week INT(7),
        room VARCHAR(10),
        studentId VARCHAR(10) NOT NULL,
        attended BOOL,
        CONSTRAINT UC_AttendanceRecord UNIQUE (lectureId, week, studentId)
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
        $dbFunctions = new Database();

        $sql = "CREATE TABLE lectures (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        moduleCode VARCHAR(30),
        start_time INT(4) UNSIGNED,
        stop_time INT(4) UNSIGNED,
        week INT(3) UNSIGNED,
        lecturer VARCHAR(30),
        room VARCHAR(10),
        CONSTRAINT UC_Lecture UNIQUE (date, moduleCode)
        )";
        $this->database->query($sql);

        $lectureDate = StaticData::lectureDate;
        $lectureModule = StaticData::lectureModule;
        $lectureTime = StaticData::lectureTime;
        $lectureStop = StaticData::lectureStop;
        $lectureWeek = StaticData::lectureWeek;
        $lecturerId = StaticData::lecturerId;
        $lectureRoom = StaticData::lectureRoom;

        for ($x=0; $x<count($lectureDate); $x++)
        {
            $dbFunctions->insertLecture($lectureDate[$x], $lectureModule[$x], $lectureTime[$x], $lectureStop[$x], $lectureWeek[$x], $lecturerId[$x], $lectureRoom[$x]);
        }

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
        moduleCode VARCHAR(7),
        name VARCHAR(30),
        courseCode VARCHAR(10),
        weeks INT(3) UNSIGNED,
        CONSTRAINT UC_Module UNIQUE (moduleCode, name)
        )";
        $this->database->query($sql);

        
        $moduleCode = StaticData::moduleCode;
        $moduleName = StaticData::moduleName;
        $moduleCourseCode = StaticData::moduleCourseCode;

        for ($x=0; $x<count($moduleName); $x++)
        {
            $sql = "INSERT INTO modules (moduleCode, name, courseCode, weeks)
            VALUES ('$moduleCode[$x]', '$moduleName[$x]', '$moduleCourseCode[$x]', 12)";
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
        courseCode VARCHAR(4),
        name VARCHAR(30),
        start_date DATE,
        end_date DATE,
        CONSTRAINT UC_Course UNIQUE (courseCode, name)
        )";
        $this->database->query($sql);

        $courseCode = StaticData::courseCode;
        $courseName = StaticData::courseName;

        for ($x=0; $x<count($courseName); $x++)
        {
            $sql = "INSERT INTO courses (courseCode, name)
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
        room VARCHAR(10) NOT NULL UNIQUE,
        capacity INT(5) UNSIGNED NOT NULL
        )";
        $this->database->query($sql);

         $roomName = StaticData::roomName;
         $roomCapacity = StaticData::roomCapacity;

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

    function createRoomCapacity()
    {
        $sql = "CREATE TABLE roomCapacity (
        id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        room VARCHAR(10) NOT NULL,
        date DATE,
        fill INT(5) UNSIGNED NOT NULL,
        scheduled INT(5) UNSIGNED NOT NULL,
        capacity INT(5) UNSIGNED NOT NULL,
        CONSTRAINT UC_RoomSession UNIQUE (room, date)
        )";
        $this->database->query($sql);
		
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
        userId INT(7) UNSIGNED NOT NULL UNIQUE,
        first VARCHAR(30) NOT NULL,
        last VARCHAR(30) NOT NULL,
        courseCode VARCHAR(30) NOT NULL,
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
        userId INT(7) UNSIGNED NOT NULL UNIQUE,
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
        userId INT(7) UNSIGNED NOT NULL UNIQUE,
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