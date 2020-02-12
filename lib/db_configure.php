<?php declare(strict_types=1);
include "../static/rooms.php";

class Configure
{
    private $database;

	function __construct()
	{
		$this->database = self::connectDb();
    }
    
    function createStudents()
    {	
    //Verify if database "alumni" exists in MySQL first; if not, create one.
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

    if ($this->database->query($sql) === TRUE) {
        $output = "Table students created successfully";
    } else {
        $output = "Error creating table: " . $this->database->error;
    }
    return $output;
    }

    function createCourses()
    {
    $sql = "CREATE TABLE courses (
        course VARCHAR(30) PRIMARY KEY NOT NULL,
        enrolled INT(10),
        attendance INT(5),
        start_date DATE,
        end_date DATE,
        module1 VARCHAR(30),
        module2 VARCHAR(30),
        module3 VARCHAR(30),
        module4 VARCHAR(30),
        module5 VARCHAR(30),
        module6 VARCHAR(30)
        )";
    
        if ($this->database->query($sql) === TRUE) {
            $output = "Table courses created successfully";
        } else {
            $output = "Error creating table: " . $this->database->error;
        }
        return $output;
    }

    function createModules()
    {
    $sql = "CREATE TABLE modules (
        module VARCHAR(30) PRIMARY KEY NOT NULL,
        room VARCHAR(10),
        start_date DATETIME,
        end_date DATETIME,
        attendance INT(5),
        enrolled INT(5),
        capacity INT(5)
        )";







    
        if ($this->database->query($sql) === TRUE) {
            $output = "Table modules created.";
        } else {
            $output = "Error creating table: " . $this->database->error;
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

        $roomName = Rooms::roomName;
        $roomCapacity = Rooms::roomCapacity;

        for ($x=0; $x<count($roomName); $x++)
        {
            $room = $roomName[$x];
            $attendance = null;         //temporary until algorithm is implemented
            $enrolled = null;           //temporary until algorithm is implemented
            $capacity = $roomCapacity[$x];

            //echo $room;

            $sql = "INSERT INTO rooms (room, attendance, enrolled, capacity)
            VALUES ('$room', '$attendance', '$enrolled', '$capacity')";
            $this->database->query($sql);
        }

		
		if ($this->database->query($sql) === TRUE) {
		$output = "Table rooms created.";
		} else {
				$output = "Error: " . $sql . "<br>" . $this->database->error;
				}
		
		return $output;
    }

    /* function createAttendance()
    {
    $sql = "CREATE TABLE $id (
        $module1 VARCHAR(30) PRIMARY KEY NOT NULL,
        $module2
        $module3
        $module4 ... 

        $start_range DATE,
        $end_range DATE
        )";
    
        if ($this->database->query($sql) === TRUE) {
            $output = "Table courses created successfully";
        } else {
            $output = "Error creating table: " . $this->database->error;
        }
        return $output;
    } */

    //Connect to Database, used by many other functions.
    private function connectDb()
    {
        $conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
        if ($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }


}





?>