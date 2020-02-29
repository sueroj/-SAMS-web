<?php declare(strict_types=1);

//functions.php:-Contains all of the database functions intended to be called by the SAMS web frontend.
//				-Many more functions will be added as the project progresses.
//
//TO DO: -Add deconstructor for closing the Database connection
//		 -Add anti-SQL injection security measures. Clean up returns.
//		 -Add more search/return options.
//		 -Create unique command for listing passwords.
//

class Database
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

	//verifyAccount(): -Used to verify username and password with the database.
	//				   -If authenticated, a session is started and the user is redirected from login page to their respective account type homepage.
    function verifyAccount(int $_userId, string $_passwd)
	{
		$field = null;

		$sql = "SELECT * FROM students WHERE userId='$_userId'";
		$result = $this->database->query($sql);
		
		if ($result->num_rows < 1){
			$sql = "SELECT * FROM lecturers WHERE userId='$_userId'";
			$result = $this->database->query($sql);
			if ($result->num_rows < 1){
				$sql = "SELECT * FROM admins WHERE userId='$_userId'";
				$result = $this->database->query($sql);
			}
		}	

		$field = $result->fetch_array(MYSQLI_ASSOC);
		if ($field != null){
			if (md5($_passwd) == $field["passwd"]){
				session_start();
				$_SESSION["loggedin"] = true;
				$_SESSION["userId"] = $_userId;
				$_SESSION["first"] = $field["first"];
				$_SESSION["last"] = $field["last"];
				$_SESSION["account"] = $field["account"];
				$this->database->close();
				return true;
			} else {
				$this->database->close();
				return false;
				}
		} else{
			return false;
		}
	}

	function getStudent()
	{

		//Template for getting one or all



	}

	function getCourse()
	{
		//Template for getting one or all




	}

	function getModule()
	{
		//Template for getting one or all




	}

	function getAttendance()
	{
		//Template for getting one or all



	}

	function getRoom()
	{
		//Template for getting one or all



	}

	//getData(): -Selects data from the database by table.
	//			 -Default uses the attendance table search tool. Which searches for student attendance according to user input.
	function getData(string $_input)
	{
		$output = null;

		switch ($_input)
		{
			case "students":
			$sql = "SELECT * FROM students ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("userId", "first", "last", "courseCode");
			break;
			case "lectures":
			$sql = "SELECT * FROM lectures ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("date", "moduleCode", "week", "trimester", "lecturer", "room");
			break;
			case "modules":
			$sql = "SELECT * FROM modules ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("moduleCode", "name", "courseCode", "weeks");
			break;
			case "attendance":
			$sql = "SELECT * FROM attendance ORDER BY lectureCode";
			$result = $this->database->query($sql);
			$columns = array("lectureId", "lectureCode", "moduleId", "studentId", "attended", "percentAttended");
			break;
			case "alerts":
			$sql = "SELECT * FROM attendance WHERE percentAttended < 50
					ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("lectureId", "lectureCode", "studentId", "attended", "percentAttended");
			break;
			case "roomUsage":
			$sql = "SELECT * FROM roomUsage ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("room", "date", "fill", "scheduled", "capacity");
			break;
			default:
			$sql = "SELECT * FROM attendance WHERE studentId='$_input' ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("lectureCode", "moduleId", "studentId", "attended", "percentAttended");
		}

		if ($result->num_rows > 0)
		{
			$output .= "<tr>";
			for ($x=0; $x<count($columns); $x++)
			{
			$output .= "<th>" . $columns[$x] . "</th>";
			}
			$output .= "</tr>";
			
			while ($row = $result->fetch_assoc())
			{
				$output .= "<tr>";
				for ($x=0; $x<count($columns); $x++)
				{
					$output .= "<td>" . $row[$columns[$x]] . "</td>";
				}
				$output .= "</tr>";
			}

		} else {
			$output = "0 results";
		}
		return $output;
	}

	//insertStudent(): Adds a new student to the students table.
	function insertStudent(int $_userId, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		$sql = "INSERT INTO students (userId, first, last, courseCode, account, passwd)
		VALUES ('$_userId', '$_first', '$_last', '$_course', '$_acct', '$_passwd')";
		$this->database->query($sql);
		
		if ($this->database->error !== "") {
		echo $this->database->error . "<br>";
		} else {
				echo "New student added<br>";
				}
	}

	//insertUser(): -Adds a new user to their respective table (students, lecturers, admins) based on account type ($_acct). 
	//				-3 types are Student, Lecturer, or Admin.
	function insertUser(int $_userId, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		switch ($_acct)
		{
			case User::Student:
				$sql = "INSERT INTO students (userId, first, last, courseCode, account, passwd)
				VALUES ('$_userId', '$_first', '$_last', '$_course', '$_acct', '$_passwd')";
			break;
			case User::Lecturer:
				$sql = "INSERT INTO lecturers (userId, first, last, account, passwd)
				VALUES ('$_userId', '$_first', '$_last', '$_acct', '$_passwd')";
			break;
			case User::Admin:
				$sql = "INSERT INTO admins (userId, first, last, account, passwd)
				VALUES ('$_userId', '$_first', '$_last', '$_acct', '$_passwd')";
			break;
			default:
				echo "An error has occurred.";
		}
		$this->database->query($sql);
		
		if ($this->database->error !== "") {
		$output = $this->database->error . "<br>";
		} else {
				$output = "New user added<br>";
				}
		return $output;
	}
	//insertLecture(): Adds a new lecture to the lectures table.
	function insertLecture(string $_date, string $_module, int $_time, int $_stop, int $_week, int $_trimester, int $_userId, string $_room)
	{
            $sql = "INSERT INTO lectures (date, moduleCode, start_time, stop_time, week, trimester, lecturer, room)
            VALUES ('$_date', '$_module', '$_time', '$_stop', '$_week', '$_trimester', '$_userId', '$_room')";
			$this->database->query($sql);
		
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "New lectures added.";
                    }
        return $output;
	}

	//insertRoom: Adds a new room to the rooms table.
	function insertRoom(string $_room, int $_capacity)
    {
		$sql = $this->database->prepare("INSERT INTO rooms (room, capacity)
		VALUES (?, ?)");
		$sql->bind_param("si", $_room, $_capacity);
		$sql->execute();

		if ($this->database->error !== "") {
			$output = $this->database->error . "<br>";
			} else {
				$output = "New room added<br>";
				}
		return $output;
		
    }

	//updateRoomFill: Calculates room Fill column.
	function updateRoomFill()
	{
		$sql = "SELECT lectures.date, lectures.week, attended, lectures.room FROM attendance
		INNER JOIN lectures ON attendance.lectureId=lectures.id";
		$result = $this->database->query($sql);

		if ($result->num_rows > 0)
		{
			$sql = "UPDATE roomUsage SET fill=0";
			$this->database->query($sql);

			while($row = $result->fetch_assoc()) 
			{
				$date = $row["date"];
				$week = $row["week"] - 1;
				$attendanceStr = $row["attended"];
				$room = $row["room"];

				$attendance = str_split($attendanceStr);

				if ($attendance[$week] == 1)
				{
					$sql = "UPDATE roomUsage SET fill=fill+1
					WHERE room='$room' AND date='$date'";
					$this->database->query($sql);
				}
			}
		}

		if ($this->database->error !== "") {
		echo $this->database->error;
		}
	}

	//updateroomUsage():  Adds rooms the roomUsage table, with column imported from the rooms table and lectures table.
	//					  The attendance.studentId column is counted while grouped by lectureId. The count result is equal
	//					  to the number of students scheduled for a module lecture. This value is used to update the
	//					  roomUsage.scheduled column.
	function updateRoomUsage()
	{
		$sql = "INSERT INTO roomUsage (room, date, capacity)
		SELECT rooms.room, lectures.date, rooms.capacity FROM lectures, rooms
		WHERE lectures.room = rooms.room";
		$this->database->query($sql);

		$sql = "SELECT COUNT(studentId), lectureId, room FROM attendance
		GROUP BY lectureId";
		$result = $this->database->query($sql);

		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_array(MYSQLI_NUM)) 
			{
				$studentCount = $row[0];
				$room = $row[2];

				$sql = "UPDATE roomUsage SET scheduled='$studentCount'
				WHERE room='$room'";
				$this->database->query($sql);
			}
		}

		
		if ($this->database->error !== "") {
		echo $this->database->error;
		}
	}

	//updateAttendance(): -Calculates attendance whenever a change is made to the attendance table. 
	//					  -Checks lecture.week column, prevents changes to weeks without lecture data (i.e. an unscheduled lecture).
	//					  -If lecture data is found, the 12-character attendance string is selected for lectureId and studentId.
	//					  -The string is split into an array represented by attendance[week], value $_newAttendance updates attendance[week] for $_week.
	//                    -The array is repackaged into a 12-character string again, named $updatedAttendance, and written into table attendance
	//				  	  -Meanwhile, the sum of the string is stored as $sumAttendance and divided by lecture week * 100. 
	//				 	  -This value equals the percentage of lectures that a student has attended and used to update the attendance.percentAttendance column.
	function updateAttendance(string $_lectureId, int $_studentId, int $_week, int $_newAttendance)
	{
		$_week = $_week - 1;

		$sql = "SELECT lectures.week FROM attendance
		INNER JOIN lectures ON attendance.lectureId=lectures.id
		WHERE lectureId='$_lectureId' AND studentId='$_studentId'";
		$verifyWeek = $this->database->query($sql)->fetch_array(MYSQLI_NUM);

		if ($_week < $verifyWeek[0])
		{
			$sql = "SELECT attended FROM attendance
			WHERE lectureId='$_lectureId' AND studentId='$_studentId'";
			$result = $this->database->query($sql);
			$attendanceStr = $result->fetch_array(MYSQLI_NUM);
			$attendanceStr = $attendanceStr[0];
			
			if($result->num_rows > 0)
			{
				$attendance = str_split($attendanceStr);
				$attendance[$_week] = $_newAttendance;
				$updatedAttendance = $attendance[0];
				$sumAttendance = (int)$attendance[0];

				for ($x=1; $x<count($attendance); $x++)
				{
					$updatedAttendance .= $attendance[$x];
					$sumAttendance += $attendance[$x];
				}
			}

			$sql = "UPDATE attendance SET attended='$updatedAttendance'
					WHERE lectureId='$_lectureId' AND studentId='$_studentId'";
			$this->database->query($sql);

			$sql = "SELECT attendance.lectureId, lectures.week FROM attendance
					INNER JOIN lectures ON attendance.lectureId=lectures.id
					WHERE lectureId='$_lectureId' AND studentId='$_studentId'";
			$result = $this->database->query($sql);
			$attendanceWeek = $result->fetch_array(MYSQLI_NUM);
			$attendanceWeek = $attendanceWeek[1];

			$percentAttended = ($sumAttendance / $attendanceWeek) * 100;

			$sql = "UPDATE attendance SET percentAttended='$percentAttended'
			WHERE lectureId='$_lectureId' AND studentId='$_studentId'";
			$this->database->query($sql);

			if ($this->database->error !== "") {
				$output = $this->database->error;
				} else {
						$output = "Attendance updated.<br>";
						}
				return $output;
		} else
			{
				return "Invalid week entry.<br>";
			}


	}

	//insertAttendance(): Adds new Attendance records automatically into attendance table based on information from 
	//					  the lectures, students, and modules tables.
	function insertAttendance()
	{
		$sql = "INSERT INTO attendance (lectureId, lectureCode, moduleId, room, studentId)
				SELECT lectures.id, CONCAT(date,lectures.moduleCode), CONCAT(lectures.moduleCode, trimester), lectures.room, students.userId FROM lectures, students, modules
				WHERE lectures.moduleCode = modules.moduleCode AND modules.courseCode = students.courseCode";
				$this->database->query($sql);
	}

	//getAlerts(): -Used by the admin_home.php page to query the database for any attendance records < 50%.
	//			   -True: show Alert button on admin home.
	//			   -False: hide Alert button on admin home.
	function getAlerts()
	{
		$setAlert = "none";

		$sql = "SELECT percentAttended FROM attendance";
		$result = $this->database->query($sql);

		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc()) 
			{
				if($row["percentAttended"] < 50)
				{
					$setAlert = "block";
				} 
			}
		}
		return $setAlert;
	}

	//Initial Database creation.
	function createDb()
	{
		$database = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
		if ($this->database->connect_error){
			die("Connection failed: " . $conn->connect_error);	
		}

		//Create or verify DB exists
		$sql = "CREATE DATABASE samsdb";
		if ($this->database->query($sql) === TRUE){
			$output = "database samsdb created.";
		} else {
			$output = "Error creating database: " . $this->database->error;
		}
		return $output;
	}

	//Connect to Database
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