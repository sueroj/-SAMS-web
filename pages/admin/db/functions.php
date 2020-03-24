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
		$this->database = null;
	}

	//verifyAccount(): -Used to verify username and password with the database.
	//				   -If authenticated, a session is started and the user is redirected from login page to their respective account type homepage.
    function verifyAccount(int $_userId, string $_passwd)
	{
		$field = null;
		try
		{
			$result = $this->database->prepare("SELECT * FROM students WHERE userId=?");
			$result->execute([$_userId]);
			$field = $result->fetch();

				if ($field === false){
				$result = $this->database->prepare("SELECT * FROM lecturers WHERE userId=?");
				$result->execute([$_userId]);
				$field = $result->fetch();

					if ($field === false){
						$result = $this->database->prepare("SELECT * FROM admins WHERE userId=?");
						$result->execute([$_userId]);
						$field = $result->fetch();
					}
			 	}
			
			
			if ($field !== false){
				if (md5($_passwd) == $field["passwd"]){
					session_start();
					$_SESSION["loggedin"] = true;
					$_SESSION["userId"] = $_userId;
					$_SESSION["first"] = $field["first"];
					$_SESSION["last"] = $field["last"];
					$_SESSION["account"] = $field["account"];
					return true;
				} else {
					return false;
					}
			} else {
				return false;
			}
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
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

	function getAttendance(string $_input)
	{
		$output = null;

		try
		{
			switch ($_input)
			{
				case "attendance":
					$result = $this->database->query("SELECT * FROM attendance GROUP BY studentId ORDER BY studentId");
					$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
					$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
				break;
				case "alerts":
					$result = $this->database->query("SELECT * FROM attendance WHERE percentAttended < 50 GROUP BY studentId ORDER BY studentId");
					$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
					$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
				break;
				default:
				$result = $this->database->prepare("SELECT * FROM attendance WHERE studentId=:studentId OR moduleId=:moduleId ORDER BY id");
				$result->execute(['studentId' => $_input, 'moduleId' => $_input]);
				$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
				$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
			}

			$output .= "<tr>";
			for ($x=0; $x<count($formatted); $x++)
			{
				$output .= "<th>" . $formatted[$x] . "</th>";
			}
			$output .= "</tr>";
			
				while ($row = $result->fetch())
				{
					$output .= "<tr>";
					for ($x=0; $x<count($columns); $x++)
					{
						if ($row[$columns[$x]] == $row["attended"])
						{
							$splitAttended = str_split($row[$columns[$x]]);
							$row[$columns[$x]] = "";
							for ($y=0; $y<count($splitAttended); $y++)
							{
								if ($splitAttended[$y] == 1)
								{
									$splitAttended[$y] = "<img src='/images/present.png' alt='1'>";
								} else {
									$splitAttended[$y] = "<img src='/images/absent.png' alt='0'>";;
								}
								$row[$columns[$x]] .= $splitAttended[$y];
							}
						}
						$output .= "<td>" . $row[$columns[$x]] . "</td>";
					}
					$output .= "</tr>";
				}

			return $output;
		}
		catch(PDOException $e)
		{
			return "Error: " . $e->getMessage();
		}
	}

	//getData(): -Selects data from the database by table.
	//			 -Default uses the attendance table search tool. Which searches for student attendance according to user input.
	function getData(string $_input)
	{
		$output = null;
		$columns["attended"] = "";
		$diagram = false;

		try
		{
			switch ($_input)
			{
				case "students":
					$result = $this->database->query("SELECT * FROM students ORDER BY id");
					$columns = array("userId", "first", "last", "courseCode");
					$formatted = array("User ID", "First Name", "Last Name", "Course");
				break;
				case "lectures":
					$result = $this->database->query("SELECT * FROM lectures ORDER BY id");
					$columns = array("date", "moduleCode", "week", "trimester", "lecturer", "room");
					$formatted = array("Date", "Module", "Week", "Trimester", "Lecturer", "Room");
				break;
				case "modules":
					$result = $this->database->query("SELECT * FROM modules ORDER BY id");
					$columns = array("moduleCode", "name", "courseCode", "weeks");
					$formatted = array("Module Code", "Module Name", "Course", "Weeks");
				break;
				case "attendance":
					$result = $this->database->query("SELECT * FROM attendance GROUP BY studentId ORDER BY studentId");
					$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
					$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
					$diagram = true;
				break;
				case "alerts":
					$result = $this->database->query("SELECT * FROM attendance WHERE percentAttended < 50 GROUP BY studentId ORDER BY studentId");
					$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
					$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
					$diagram = true;
				break;
				case "roomUsage":
					$result = $this->database->query("SELECT * FROM roomUsage ORDER BY id");
					$columns = array("room", "date", "fill", "scheduled", "capacity");
					$formatted = array("Room", "Date", "Fill", "Scheduled", "Capacity");
				break;
				default:
					$result = $this->database->prepare("SELECT * FROM attendance WHERE studentId=:studentId OR moduleId=:moduleId ORDER BY id");
					$result->execute(['studentId' => $_input, 'moduleId' => $_input]);
					$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
					$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
					$diagram = true;
			
					if ($result->fetch() === false)
					{
						$result = $this->database->prepare("SELECT * FROM roomUsage WHERE room=:room ORDER BY id");
						$result->execute(['room' => $_input]);
						$columns = array("room", "date", "fill", "scheduled", "capacity");
						$formatted = array("Room", "Date", "Fill", "Scheduled", "Capacity");
					}
			}

			$output .= "<tr>";
			for ($x=0; $x<count($formatted); $x++)
			{
			$output .= "<th>" . $formatted[$x] . "</th>";
			}
			$output .= "</tr>";
			
				while ($row = $result->fetch())
				{
					$output .= "<tr>";
					for ($x=0; $x<count($columns); $x++)
					{
						if ($diagram === true)
						{
							if ($row[$columns[$x]] == $row["attended"])
							{
								$splitAttended = str_split($row[$columns[$x]]);
								$row[$columns[$x]] = "";
								for ($y=0; $y<count($splitAttended); $y++)
								{
									if ($splitAttended[$y] == 1)
									{
										$splitAttended[$y] = "<img src='/images/present.png' alt='1'>";
									} else {
										$splitAttended[$y] = "<img src='/images/absent.png' alt='0'>";
									}
									$row[$columns[$x]] .= $splitAttended[$y];
								}
							}
						}
						$output .= "<td>" . $row[$columns[$x]] . "</td>";
					}
					$output .= "</tr>";
				}

			return $output;
		}
		catch(PDOException $e)
		{
			return "Error: " . $e->getMessage();
		}
		
	}

	//insertStudent(): Adds a new student to the students table.
	function insertStudent(int $_userId, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		try
		{
			$sql = $this->database->prepare("INSERT INTO students (userId, first, last, courseCode, account, passwd)
			VALUES (:userId, :first, :last, :courseCode, :acct, :passwd)");
			$sql->bindParam(':userId', $_userId);
			$sql->bindParam(':first', $_first);
			$sql->bindParam(':last', $_last);
			$sql->bindParam(':courseCode', $_course);
			$sql->bindParam(':acct', $_acct);
			$sql->bindParam(':passwd', $_passwd);
			$sql->execute();

			return "New student added.<br>";
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	//insertUser(): -Adds a new user to their respective table (students, lecturers, admins) based on account type ($_acct). 
	//				-3 types are Student, Lecturer, or Admin.
	function insertUser(int $_userId, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
		try
		{
			switch ($_acct)
			{
				case User::Student:
					$sql = $this->database->prepare("INSERT INTO students (userId, first, last, courseCode, account, passwd)
					VALUES (:userId, :first, :last, :courseCode, :acct, :passwd)");
					$sql->bindParam(':courseCode', $_course);
				break;
				case User::Lecturer:
					$sql = $this->database->prepare("INSERT INTO lecturers (userId, first, last, account, passwd)
					VALUES (:userId, :first, :last, :acct, :passwd)");
				break;
				case User::Admin:
					$sql = $this->database->prepare("INSERT INTO admins (userId, first, last, account, passwd)
					VALUES (:userId, :first, :last, :acct, :passwd)");
				break;
				default:
					echo "An error has occurred.";
			}
			$sql->bindParam(':userId', $_userId);
			$sql->bindParam(':first', $_first);
			$sql->bindParam(':last', $_last);
			$sql->bindParam(':acct', $_acct);
			$sql->bindParam(':passwd', $_passwd);
			$sql->execute();

			return "New user added.<br>";
		}
		
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}
	//insertLecture(): Adds a new lecture to the lectures table.
	function insertLecture(string $_date, string $_module, int $_time, int $_stop, int $_week, int $_trimester, int $_userId, string $_room)
	{
		try
		{
			$sql = $this->database->prepare("INSERT INTO lectures (date, moduleCode, start_time, stop_time, week, trimester, lecturer, room)
			VALUES (:date, :moduleCode, :start_time, :stop_time, :week, :trimester, :lecturer, :room)");
			$sql->bindParam(':date', $_date);
			$sql->bindParam(':moduleCode', $_module);
			$sql->bindParam(':start_time', $_time);
			$sql->bindParam(':stop_time', $_stop);
			$sql->bindParam(':week', $_week);
			$sql->bindParam(':trimester', $_trimester);
			$sql->bindParam(':lecturer', $_userId);
			$sql->bindParam(':room', $_room);
			$sql->execute();

			return "New module added.";
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	function insertModule(string $_moduleCode, string $_name, string $_courseCode, int $_weeks)
	{
		try
		{
			$sql = $this->database->prepare("INSERT INTO modules (moduleCode, name, courseCode, weeks)
			VALUES (:moduleCode, :name, :courseCode, :weeks)");
			$sql->bindParam(':moduleCode', $_moduleCode);
			$sql->bindParam(':name', $_name);
			$sql->bindParam(':courseCode', $_courseCode);
			$sql->bindParam(':weeks', $_weeks);
			$sql->execute();

			return "New module added.";
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	function insertCourse(string $_courseCode, string $_name)
	{
		try
		{
			$sql = $this->database->prepare("INSERT INTO courses (courseCode, name)
			VALUES (:courseCode, :name)");
			$sql->bindParam(':courseCode', $_courseCode);
			$sql->bindParam(':name', $_name);
			$sql->execute();
	
			return "New course added.";
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	//insertRoom: Adds a new room to the rooms table.
	function insertRoom(string $_roomName, int $_roomCapacity)
    {
		try
		{
			$sql = $this->database->prepare("INSERT INTO rooms (room, capacity)
			VALUES (:room, :capacity)");
			$sql->bindParam(':room', $_roomName);
			$sql->bindParam(':capacity', $_roomCapacity);
			$sql->execute();
	
			return "New room added.";
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}
    }

	//updateRoomFill: Calculates room Fill column.
	function updateRoomFill()
	{
		try
		{
			$sql = "SELECT lectures.date, lectures.week, attended, lectures.room FROM attendance
			INNER JOIN lectures ON attendance.lectureId=lectures.id";
			$result = $this->database->query($sql);
	
			if ($result !== false)
			{
				$sql = "UPDATE roomUsage SET fill=0";
				$this->database->exec($sql);
	
				while($row = $result->fetch()) 
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
						$this->database->exec($sql);
					}
				}
			}
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	//updateroomUsage():  Adds rooms the roomUsage table, with column imported from the rooms table and lectures table.
	//					  The attendance.studentId column is counted while grouped by lectureId. The count result is equal
	//					  to the number of students scheduled for a module lecture. This value is used to update the
	//					  roomUsage.scheduled column.
	function updateRoomUsage()
	{

		try
		{
			$sql = "INSERT INTO roomUsage (room, date, capacity)
			SELECT rooms.room, lectures.date, rooms.capacity FROM lectures, rooms
			WHERE lectures.room = rooms.room";
			$this->database->exec($sql);
		}
		catch(PDOException $e){}

		try
		{
			$sql = "SELECT COUNT(studentId), lectureId, room FROM attendance
			GROUP BY lectureId";
			$result = $this->database->query($sql);
	
			if ($result !== false)
			{
				while($row = $result->fetch(PDO::FETCH_NUM)) 
				{
					$studentCount = $row[0];
					$room = $row[2];
	
					$sql = "UPDATE roomUsage SET scheduled='$studentCount'
					WHERE room='$room'";
					$this->database->exec($sql);
				}
			}
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
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

		try
		{
			$sql = $this->database->prepare("SELECT attended, moduleId FROM attendance WHERE lectureId=:lectureId AND studentId=:studentId");
			$sql->execute(['lectureId' => $_lectureId, 'studentId' => $_studentId]);
			$result = $sql->fetch(PDO::FETCH_NUM);
			$attendanceStr = $result[0];
			$moduleId = $result[1];

			if($result !== false)
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

			$sql = $this->database->prepare("UPDATE attendance SET attended=? WHERE moduleId=? AND studentId=?");
			$sql->execute([$updatedAttendance, $moduleId, $_studentId]);

			$percentAttended = ($sumAttendance / 12) * 100;

			$sql = $this->database->prepare("UPDATE attendance SET percentAttended=? WHERE moduleId=? AND studentId=?");
			$sql->execute([$percentAttended, $moduleId, $_studentId]);
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	//insertAttendance(): Adds new Attendance records automatically into attendance table based on information from 
	//					  the lectures, students, and modules tables.
	function insertAttendance()
	{
		try
		{
			$sql = "INSERT INTO attendance (lectureId, lectureCode, moduleId, room, studentId)
			SELECT lectures.id, CONCAT(date,lectures.moduleCode), CONCAT(lectures.moduleCode, trimester), lectures.room, students.userId FROM lectures, students, modules
			WHERE lectures.moduleCode = modules.moduleCode AND modules.courseCode = students.courseCode";
			$this->database->exec($sql);

			return "Attendance updated.<br>";
		}

		catch(PDOException $e)
		{
			return $e->getMessage();
		}

	}

	//getAlerts(): -Used by the admin_home.php page to query the database for any attendance records < 50%.
	//			   -True: show Alert button on admin home.
	//			   -False: hide Alert button on admin home.
	function getAlerts()
	{
		$setAlert = "none";

		$sql = "SELECT percentAttended FROM attendance";
		$result = $this->database->query($sql);

		if($result !== false)
		{
			while($row = $result->fetch()) 
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
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}

	//Connect to Database
    private function connectDb()
	{
		try {
			$pdo = new PDO("mysql:host=" . Globals::SERVER_LOGIN  . ";dbname=" . Globals::SERVER_DB, Globals::SERVER_USER, Globals::SERVER_PWD);
			// set the PDO error mode to exception
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			}
		catch(PDOException $e)
			{
				echo "Connection failed: " . $e->getMessage();
			}
		return $pdo;
	}
}
?>