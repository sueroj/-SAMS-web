<?php declare(strict_types=1);
include "globals.php";

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

	//Function used to verify username and password id and password is checked against the DB,
	//if authenticated, a session is started and user is redirected from login page to their
	//respective homepage.
    function verifyAccount(int $_id, string $_passwd)
	{
		$field = null;

		$sql = "SELECT id, account, first, last, passwd FROM alumni WHERE id='$_id'";
		$result = $this->database->query($sql);

		$field = $result->fetch_array(MYSQLI_ASSOC);
		if ($field != null){
			if (md5($_passwd) == $field["passwd"]){
				session_start();
				$_SESSION["loggedin"] = true;
				$_SESSION["id"] = $_id;
				$_SESSION["first"] = $field["first"];
				$_SESSION["last"] = $field["last"];
				$_SESSION["account"] = $field["account"];
				$this->database->close();
				return 1;
			} else {
				$this->database->close();
				return 0;
				}
		} else{
			return 0;
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

	//Creates the main table used by the SAMS web database.
	//---------------------------------------------------------
	//*************** MOVED TO db_configure.php **************
	//-----------------------------------------------------------
	//
	// function createStudents()
	// {	
	// 	//Verify if database "alumni" exists in MySQL first; if not, create one.
	// 	$sql = "CREATE DATABASE samsdb";
	// 	if ($this->database->query($sql) === TRUE){
	// 		$output =  "New database samsdb created.\n";
	// 	}

	// 	$sql = "CREATE TABLE students (
	// 	id INT(7) UNSIGNED PRIMARY KEY,
	// 	first VARCHAR(30) NOT NULL,
	// 	last VARCHAR(30) NOT NULL,
	// 	course VARCHAR(30) NOT NULL,
	// 	account INT(1) UNSIGNED,
	// 	passwd VARCHAR(40) NOT NULL
	// 	)";

	// 	if ($this->database->query($sql) === TRUE) {
	// 		$output = "Table students created successfully";
	// 	} else {
	// 		$output = "Error creating table: " . $this->database->error;
	// 	}
	// 	return $output;
	// }



	//Views records stored in the samsDb database -> students table
	//So far, only recieves user input for searching by ID # and the
	//debug command "all" records, which lists all records.
	function viewRecord(string $_input)
	{
		$output = null;

		switch ($_input)
		{
			case "all":
			$sql = "SELECT * FROM students ORDER BY id";
			$result = $this->database->query($sql);
			break;
			case "rooms":
			$sql = "SELECT * FROM rooms ORDER BY room";
			$result = $this->database->query($sql);
			break;
			default:
			$sql = "SELECT * FROM students WHERE id='$_input' ORDER BY id";
			$result = $this->database->query($sql);
		}


		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$output .= "<tr><td>" . $row["id"] . "</td>".
						"<td>" . $row["first"] . "</td>".
						"<td>" . $row["last"] . "</td>".
						"<td>" . $row["course"] . "</td>".
						"<td>" . $row["passwd"] . "</td></tr>";
			}
		} else {
			$output = "0 results";
		}
		return $output;
	}

	//Main function used for create new records.
	function insertStudent(int $_id, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		$sql = "INSERT INTO students (id, first, last, course, account, passwd)
		VALUES ('$_id', '$_first', '$_last', '$_course', '$_acct', '$_passwd')";
		
		if ($this->database->query($sql) === TRUE) {
		$output = "New student added.";
		} else {
				$output = "Error: " . $sql . "<br>" . $this->database->error;
				}
		
		return $output;

	}

	function insertCourse()
	{





	}

	function insertModule()
	{





	}

	function insertAttendance()
	{





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

	//Function will be used to update records individually.
	//
	//************************************************************
	//TO DO: -Not used right now, may be used later at for backend.
	//************************************************************
	//
	//
	// function insertBlank(int $_id, string $_input)
	// {
	// 	$sql = "UPDATE alumni SET first='$_input' WHERE id='$_id'";
		
	// 	if ($this->database->query($sql) === TRUE) {
	// 	$output = "New record processed.";
	// 	} else {
	// 			$output = "Error: " . $sql . "<br>" . $this->database->error;
	// 			}

	// 	$this->database->close();
	// 	echo $output;
	// }

	//Function used for delete table students, exists for when table alumni
	//needs to remade with undate field properties.
	function dropStudents()
	{
		$sql = "DROP TABLE students";

		if ($this->database->query($sql) === TRUE) {
			$output = "Table students deleted";
		} else {
			$output = "Error deleting table: " . $this->database->error;
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