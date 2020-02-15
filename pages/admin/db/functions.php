<?php declare(strict_types=1);

//
//db_functions contains most, if not, all of the functions intended to be used 
//by the SAMS web database. Many more functions will be added as the project progresses.
//
//TO DO:-Add deconstructor for closing the Database connection
//		-Add anti-SQL injection security measures. Clean up returns.
//		-Add more search/return options.
//		-Create unique command for listing passwords.
//

class Database
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

	//Function used to verify username and password id and password is checked against the DB,
	//if authenticated, a session is started and user is redirected from login page to their
	//respective homepage.
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

	//Views records stored in the samsDb database -> students table
	//So far, only recieves user input for searching by ID # and the
	//debug command "all" records, which lists all records.
	function getData(string $_input)
	{
		$output = null;

		switch ($_input)
		{
			case "students":
			$sql = "SELECT * FROM students ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("userId", "first", "last", "courseCode", "account", "passwd");
			break;
			case "courses":
			$sql = "SELECT * FROM courses ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("courseCode", "name", "start_date", "end_date");
			break;
			case "modules":
			$sql = "SELECT * FROM modules ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("moduleCode", "name", "courseCode", "weeks");
			break;
			case "attendance":
			$sql = "SELECT * FROM attendance ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("lectureId", "room", "studentId", "attended");
			break;
			case "roomCapacity":
			$sql = "SELECT * FROM roomCapacity ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("room", "date", "fill", "scheduled", "capacity");
			break;
			default:
			$sql = "SELECT * FROM students WHERE id='$_input' ORDER BY id";
			$result = $this->database->query($sql);
			$columns = array("userId", "first", "last", "course");
		}

		if ($result->num_rows > 0) {
			switch (count($columns))
			{
				case 2:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td></tr>";
					}
				case 3:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td></tr>";
					}
				break;
				case 4:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td>".
					"<td>" . $row[$columns[3]] . "</td></tr>";
					}
				break;
				case 5:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td>".
					"<td>" . $row[$columns[3]] . "</td>".
					"<td>" . $row[$columns[4]] . "</td></tr>";
					}
				break;
				case 6:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td>".
					"<td>" . $row[$columns[3]] . "</td>".
					"<td>" . $row[$columns[4]] . "</td>".
					"<td>" . $row[$columns[5]] . "</td></tr>";
					}
				break;
				case 7:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td>".
					"<td>" . $row[$columns[3]] . "</td>".
					"<td>" . $row[$columns[4]] . "</td>".
					"<td>" . $row[$columns[5]] . "</td>".
					"<td>" . $row[$columns[6]] . "</td></tr>";
					}
				break;
				case 11:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td>".
					"<td>" . $row[$columns[3]] . "</td>".
					"<td>" . $row[$columns[4]] . "</td>".
					"<td>" . $row[$columns[5]] . "</td>".
					"<td>" . $row[$columns[6]] . "</td>".
					"<td>" . $row[$columns[7]] . "</td>".
					"<td>" . $row[$columns[8]] . "</td>".
					"<td>" . $row[$columns[9]] . "</td>".
					"<td>" . $row[$columns[10]] . "</td>";
					}
				break;
				default:
					// output data of each row
					while($row = $result->fetch_assoc()) {
					$output .= "<tr><td>" . $row[$columns[0]] . "</td>".
					"<td>" . $row[$columns[1]] . "</td>".
					"<td>" . $row[$columns[2]] . "</td>".
					"<td>" . $row[$columns[3]] . "</td>".
					"<td>" . $row[$columns[4]] . "</td></tr>";
					}
			}

		} else {
			$output = "0 results";
		}
		return $output;
	}

	//Main function used for create new records.
	function insertStudent(int $_id, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		$sql = "INSERT INTO students (userId, first, last, courseCode, account, passwd)
		VALUES ('$_id', '$_first', '$_last', '$_course', '$_acct', '$_passwd')";
		$this->database->query($sql);
		
		if ($this->database->error !== "") {
		echo $this->database->error . "<br>";
		} else {
				echo "New student added<br>";
				}
	}

	function insertUser(int $_id, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		switch ($_acct)
		{
			case User::Student:
				$sql = "INSERT INTO students (userId, first, last, courseCode, account, passwd)
				VALUES ('$_id', '$_first', '$_last', '$_course', '$_acct', '$_passwd')";
			break;
			case User::Lecturer:
				$sql = "INSERT INTO lecturers (userId, first, last, account, passwd)
				VALUES ('$_id', '$_first', '$_last', '$_acct', '$_passwd')";
			break;
			case User::Admin:
				$sql = "INSERT INTO admins (userId, first, last, account, passwd)
				VALUES ('$_id', '$_first', '$_last', '$_acct', '$_passwd')";
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
	}

	function insertCourse()
	{





	}

	function insertLecture(string $_date, string $_module, int $_time, int $_stop, int $_week, int $_userId, string $_room)
	{
            $sql = "INSERT INTO lectures (date, moduleCode, start_time, stop_time, week, lecturer, room)
            VALUES ('$_date', '$_module', '$_time', '$_stop', '$_week', '$_userId', '$_room')";
			$this->database->query($sql);
		
        if ($this->database->error !== "") {
            $output = $this->database->error;
            } else {
                    $output = "New lectures added.";
                    }
        return $output;
	}

	//Function used to add rooms to the database, features are in work to calculate students enrolled and attendance.
	function insertRoom(string $_room, int $_capacity)
    {

		$room = $_room;
		$attendance = null;         //temporary
		$enrolled = null;           //temporary
		$capacity = $_capacity;

		$sql = "INSERT INTO rooms (room, attendance, enrolled, capacity)
		VALUES ('$room', '$attendance', '$enrolled', '$capacity')";
		$this->database->query($sql);
		
		if ($this->database->query($sql) === TRUE) {
		$output = "New room added.";
		} else {
				$output = "Error: " . $sql . "<br>" . $this->database->error;
				}
		
		return $output;
    }

	//Function used for delete table students, exists for when table alumni
	//needs to remade with undate field properties.

	//Update room check. If modules.room contains a room, copy that room to attendance.room
	function updateRoomCapacity()
	{
		$sql = "INSERT INTO roomCapacity (room, date, capacity)
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

				$sql = "UPDATE roomCapacity SET scheduled='$studentCount'
				WHERE room='$room'";
				$this->database->query($sql);
			}
		}

		
		if ($this->database->error !== "") {
		$output = $this->database->error;
		} else {
				$output = "Rooms updated";
				}
		return $output;
	}

	function updateAttendance($_lectureId,  $_studentId, $_newAttendance)
	{
		$sql = "UPDATE attendance SET attended=$_newAttendance
				WHERE lectureId='$_lectureId' AND studentId='$_studentId'";
		$this->database->query($sql);

		if ($this->database->error !== "") {
			$output = $this->database->error;
			} else {
					$output = "Attendance updated.";
					}
			return $output;
	}

		
	function insertAttendance()
	{
		$sql = "INSERT INTO attendance (lectureId, week, room, studentId)
				SELECT CONCAT(date,lectures.moduleCode), week, lectures.room, students.userId FROM lectures, students, modules
				WHERE lectures.moduleCode = modules.moduleCode AND modules.courseCode = students.courseCode";
				$this->database->query($sql);
		
		if ($this->database->error !== "") {
		$output = $this->database->error;
		} else {
				$output = "Attendance updated";
				}
		return $output;
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