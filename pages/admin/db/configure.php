<?php declare(strict_types=1);
include_once "static_data.php";
include_once "globals.php";
include_once "functions.php";
// Configure.php -Used to configure the samsdb database. 
//               -Connects to database and creates all of the tables used in the web application
//               -Also loads initial static data used for demonstration.
//               -Referenced by admin_home.php and initial_configure.php (via index.php <Initial Config> link).

class Configure
{
    private $database;

    //Constructor connected class Database to the samsdb database.
	function __construct()
	{
		$this->database = self::connectDb();
    }

    //Deconstructor for closing database connection.
    function __deconstruct()
	{
		$this->database->close();
    }
    
    //Verify if database "samsdb" exists in MySQL first; if not, create one.
    function checkDb()
    {  
        $sql = "CREATE DATABASE samsdb";
        if ($this->database->query($sql) === TRUE){
        $output =  "New database samsdb created.\n";
        }
    }

    //Create attendance table: -lectureId = lectures.id
    //                         -lectureCode = lectures.date . lectures.moduleCode
    //                         -moduleId = lectures.moduleCode . lectures.trimester
    //                         -attended = 12-character string; stores attendance record for a module when split. Each character represents a different week.
    //                         -percentAttended = attended / week * 100.
    function createAttendance()
    {

        $sql = "CREATE TABLE attendance (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        lectureId INT(6) NOT NULL,
        lectureCode VARCHAR(30) NOT NULL,
        moduleId VARCHAR(30) NOT NULL,
        room VARCHAR(10),
        studentId VARCHAR(10) NOT NULL,
        attended VARCHAR(12) NOT NULL DEFAULT '000000000000',
        percentAttended DOUBLE(4,2),
        CONSTRAINT UC_AttendanceRecord UNIQUE (lectureId, studentId)
        )";
        $this->database->query($sql);

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table attendance created.";
                    }
        return $output;
    }

    //Create Lectures table
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
        trimester ENUM('TRI1', 'TRI2', 'TRI3') NOT NULL,
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
            $dbFunctions->insertLecture($lectureDate[$x], $lectureModule[$x], $lectureTime[$x], $lectureStop[$x], $lectureWeek[$x], 1, $lecturerId[$x], $lectureRoom[$x]);
        }

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table lectures created.";
                    }
        return $output;
    }

    //Create modules table
    function createModules()
    {
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
        $weeks = 12;

        for ($x=0; $x<count($moduleName); $x++)
        {
            $sql = $this->database->prepare("INSERT INTO modules (moduleCode, name, courseCode, weeks)
            VALUES (?, ?, ?, ?)");
            $sql->bind_param("sssi", $moduleCode[$x], $moduleName[$x], $moduleCourseCode[$x], $weeks);
            $sql->execute();
        }

        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table modules created.";
                    }
        return $output;
    }

    //Create courses table.
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
            $sql = $this->database->prepare("INSERT INTO courses (courseCode, name)
            VALUES (?, ?)");
            $sql->bind_param("ss", $courseCode[$x], $courseName[$x]);
            $sql->execute();
        }
    
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table courses created.";
                    }
        return $output;
    }

    //Create rooms table.
    function createRooms()
    {
        $sql = "CREATE TABLE rooms (
        room VARCHAR(10) NOT NULL PRIMARY KEY UNIQUE,
        capacity INT(5) UNSIGNED NOT NULL
        )";
        $this->database->query($sql);

         $roomName = StaticData::roomName;
         $roomCapacity = StaticData::roomCapacity;

         for ($x=0; $x<count($roomName); $x++)
         {
             $room = $roomName[$x];
             $capacity = $roomCapacity[$x];

             $sql = $this->database->prepare("INSERT INTO rooms (room, capacity)
             VALUES (?, ?)");
             $sql->bind_param("si", $room, $capacity);
             $sql->execute();
         }
		
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "Table rooms created.";
                    }
        return $output;
    }

    //Create roomsCapacity table. Tentative. May be combined with rooms table.
    function createRoomUsage()
    {
        $sql = "CREATE TABLE roomUsage (
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
                    $output = "Table roomUsage created.";
                    }
        return $output;
    }
    
    //Create students table.
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

    //Create Lecturers table.
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

    //Create Admins table.
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

    //Connect to Database.
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