<?php declare(strict_types=1);
require_once "static_data.php";
require_once "globals.php";
require_once "functions.php";
// Configure.php -Used to configure the samsdb database. 
//               -Connects to database and creates all of the tables used in the web application.
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
		$this->database = null;;
    }
    
    //Verify if database "samsdb" exists in MySQL first; if not, create one.
    function checkDb()
    {  
		try
		{
			$pdo = new PDO("mysql:host=" . Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$sql = "CREATE DATABASE samsdb";

			$pdo->exec($sql);
			return "Database samsdb created.<br>";
		}
		catch(PDOException $e){}
    }

    //Create attendance table.
    function createAttendance()
    {
        try 
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

            $this->database->exec($sql);
            return "Table attendance created.";
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    //Create Lectures table.
    function createLectures()
    {
        $dbFunctions = new Database();

        try
        {
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
    
            $this->database->exec($sql);

            $lectureDate = StaticData::lectureDate;
            $lectureModule = StaticData::lectureModule;
            $lectureTime = StaticData::lectureTime;
            $lectureStop = StaticData::lectureStop;
            $lectureWeek = StaticData::lectureWeek;
            $lecturerId = StaticData::lecturerId;
            $lectureRoom = StaticData::lectureRoom;

            for ($y=0; $y<count($lectureModule); $y++)
            {
                $lectureWeek = 1;
                for ($x=0; $x<count($lectureDate); $x++)
                {
                    $dbFunctions->insertLecture($lectureDate[$x], $lectureModule[$y], $lectureTime, $lectureStop, $lectureWeek++, 1, $lecturerId[$y], $lectureRoom[$y]);
                }
            }
            return "Table lectures created.";
        }

        catch(PDOException $e)
        {
        return $e->getMessage();
        }
    }

    //Create modules table.
    function createModules()
    {
        $dbFunctions = new Database();

        try
        {
            $sql = "CREATE TABLE modules (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            moduleCode VARCHAR(7),
            name VARCHAR(30),
            courseCode VARCHAR(10),
            weeks INT(3) UNSIGNED,
            CONSTRAINT UC_Module UNIQUE (moduleCode, name)
            )";
            $this->database->exec($sql);
            
            $moduleCode = StaticData::moduleCode;
            $moduleName = StaticData::moduleName;
            $moduleCourseCode = StaticData::moduleCourseCode;
            $weeks = 12;
    
            for ($x=0; $x<count($moduleName); $x++)
            {
                $dbFunctions->insertModule($moduleCode[$x], $moduleName[$x], $moduleCourseCode[$x], $weeks);
            }
            return "Table modules created.";
        }

        catch(PDOException $e)
        {
        return $e->getMessage();
        }
    }

    //Create courses table.
    function createCourses()
    {
        $dbFunctions = new Database();

        try
        {
            $sql = "CREATE TABLE courses (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            courseCode VARCHAR(4),
            name VARCHAR(30),
            CONSTRAINT UC_Course UNIQUE (courseCode, name)
            )";
            $this->database->exec($sql);
    
            $courseCode = StaticData::courseCode;
            $courseName = StaticData::courseName;
    
            for ($x=0; $x<count($courseName); $x++)
            {
                $dbFunctions->insertCourse($courseCode[$x], $courseName[$x]);
            }
            return "Table courses created.";
        }

        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    //Create rooms table.
    function createRooms()
    {
        $dbFunctions = new Database();

        try
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
                $dbFunctions->insertRoom($roomName[$x], $roomCapacity[$x]);
            }
            return "Table rooms created.";
        }
		
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    //Create roomsCapacity table. Tentative. May be combined with rooms table.
    function createRoomUsage()
    {
        try
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
            $this->database->exec($sql);
            
            return "Table roomUsage created.";
        }

        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }
    
    //Create students table.
    function createStudents()
    {	
        try
        {
            $sql = "CREATE TABLE students (
            id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userId INT(7) UNSIGNED NOT NULL UNIQUE,
            first VARCHAR(30) NOT NULL,
            last VARCHAR(30) NOT NULL,
            courseCode VARCHAR(30) NOT NULL,
            account INT(1) UNSIGNED,
            passwd VARCHAR(255) NOT NULL
            )";
            $this->database->exec($sql);
            
            return "Table students created.";
        }

        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    //Create Lecturers table.
    function createLecturers()
    {	
        try
        {
            $sql = "CREATE TABLE lecturers (
            id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userId INT(7) UNSIGNED NOT NULL UNIQUE,
            first VARCHAR(30) NOT NULL,
            last VARCHAR(30) NOT NULL,
            account INT(1) UNSIGNED,
            passwd VARCHAR(255) NOT NULL
            )";
            $this->database->exec($sql);
            
            return "Table lecturers created.";
        }

        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    //Create Admins table.
    function createAdmins()
    {	
        try
        {
            $sql = "CREATE TABLE admins (
            id INT(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userId INT(7) UNSIGNED NOT NULL UNIQUE,
            first VARCHAR(30) NOT NULL,
            last VARCHAR(30) NOT NULL,
            account INT(1) UNSIGNED,
            passwd VARCHAR(255) NOT NULL
            )";
            $this->database->exec($sql);
            
            return "Table admins created.";
        }

        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    //Try to connect to samsdb Database. If no database found, try to create new one.
    function connectDb()
    {
        try {
			$pdo = new PDO("mysql:host=" . Globals::SERVER_LOGIN  . ";dbname=" . Globals::SERVER_DB, Globals::SERVER_USER, Globals::SERVER_PWD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			}
		catch(PDOException $e)
			{
            echo "Database samsdb was not detected. Creating new database.";
            try
            {
                $pdo = new PDO("mysql:host=" . Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $sql = "CREATE DATABASE samsdb";
    
                $pdo->exec($sql);
                return "Database samsdb created.<br>";
            }
            catch(PDOException $e){}
			}
		return $pdo;
    }


}


?>