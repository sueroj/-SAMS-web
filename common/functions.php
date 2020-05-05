<?php declare(strict_types=1);
require_once "globals.php";

//functions.php: -Core database functions file intended to be called by the SAMS web application frontend.

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

	//verifyAccount(): -Verify username and password with the database.
	//				   -If authenticated, a session is started and the user is redirected from login page to their respective account type homepage.
	//Reference: [/] index.php, [/common] application_test.php
    function verifyAccount($_userId, string $_passwd)
	{
		//Filter input validation: input must be between 6 - 8 digits.
		$options = array(
			'options' => array(
				'min_range' => 99999,
				'max_range' => 100000000
			)
		);

		if (filter_var($_userId, FILTER_VALIDATE_INT, $options) === false)
		{
			return false;
		}
			
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
				if (password_verify($_passwd, $field["passwd"])){
					session_start();
					$_SESSION["loggedin"] = true;
					$_SESSION["userId"] = $_userId;
					$_SESSION["first"] = $field["first"];
					$_SESSION["last"] = $field["last"];
					$_SESSION["account"] = $field["account"];
					if ($_SESSION["account"] == 0)
					{
						$_SESSION["courseCode"] = $field["courseCode"];
					}
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

	//getModules(): -Get a Module for a select tag; return [moduleCode, name] as options.
	//Reference: [/lecturer] lecturer_home.php
	function getModules()
	{
		$output = null;

		try
		{
			$result = $this->database->prepare("SELECT moduleCode, name FROM modules");
			$result->execute();

			while ($row = $result->fetch())
				{
					$output .=  "<option value=" . $row["moduleCode"] . ">" . $row["name"] . "</option>";
				}
			return $output;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//getModules(): -Get a Classes for a select tag; return [moduleCode] as options.
	//References: [/lecturer] lecturer_home.php
	function getClasses()
	{
		$output = null;

		try
		{
			$result = $this->database->prepare("SELECT moduleCode, start_time, stop_time, week, trimester, room FROM lectures");

			while ($row = $result->fetch())
			{
				$output .= "<option value=" . $row["moduleCode"] . ">" . $row["moduleCode"] . "</option>";
			
			}

			return $output;
		}

		catch (PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//viewModule(): -View module attendance for a student using [moduleCode]; return [studentId, percentAttended].
	//Reference: [/lecturer] lecturer_home.php
	function viewModule($_moduleCode)
	{
		$output = null;

		try
		{
			$result = $this->database->prepare("SELECT studentId, percentAttended FROM attendance");
			$result->execute([$_moduleCode]);

			$output .=  "<table>";
			while ($row = $result->fetch())
				{
					$output .=  "<tr><td>" . $row["studentId"] . " " . $row["percentAttended"] . "</tr></td>";
				}
			$output .=  "</table>";
			return $output;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}
	
	//getModuleName(): -Get full module name using [moduleCode]; return [name].
	//Reference: [/student] attendance.php
	function getModuleName($_moduleCode)
	{
		$output = null;

		try
		{
			$result = $this->database->prepare("SELECT name FROM modules WHERE moduleCode=?");
			$result->execute([$_moduleCode]);

			if ($result !== false)
			{
				while ($row = $result->fetch())
				{
					$output = $row["name"];
				}
			}
			else
			{
				$output = false;
			}
			return $output;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//getLecture(): -Get lecture details using [week, moduleCode]; return [date, start_time, stop_time].
	//Reference: [/student] attendance.php
	function getLecture($_week, $_moduleCode)
	{
		$output = null;

		try
		{
			$result = $this->database->prepare("SELECT date, start_time, stop_time FROM lectures WHERE week=? AND moduleCode=?");
			$result->execute([$_week, $_moduleCode]);

			if ($result !== false)
			{
				while ($row = $result->fetch())
				{
					$output["date"] = $row["date"];
					$output["start"] =  $row["start_time"];
					$output["stop"] = $row["stop_time"];
				}
			}
			else
			{
				$output["date"] = "N/A";
				$output["start"] =  "N/A";
				$output["stop"] = "N/A";
			}
			return $output;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//getLectureId(): -Get LectureId using [moduleCode, trimester]; return [lectureId].
	//Reference: [/student] attendance.php
	function getLectureId(string $_moduleCode, string $_trimester)
	{
		$output = null;
		$moduleId = $_moduleCode . $_trimester;

		try
		{
			$result = $this->database->prepare("SELECT DISTINCT lectureId FROM attendance WHERE moduleId=?");
			$result->execute([$moduleId]);

			if ($result->fetch() !== false)
			{
				while ($row = $result->fetch())
				{
					$output = $row["lectureId"];
				}
			}
			else
			{
				$output = false;
			}
			return $output;
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//getStudentAttendance: -Get student attendance string graphic using [moduleCode, trimester, userId], if one exists.
	//						-returns [attended, percentAttended], if none ["No Attendance Record Found", "N/A"]
	//Reference: [/student] attendance.php, student_home.php
	function getStudentAttendance(string $_moduleCode, string $_trimester, int $_userId)
	{
		$output = null;
		$moduleId = $_moduleCode . $_trimester;

		try
		{
			$result = $this->database->prepare("SELECT attended, percentAttended FROM attendance WHERE moduleId=? AND studentId=?");
			$result->execute([$moduleId, $_userId]);

			if ($result->fetch() !== false)
			{
				while ($row = $result->fetch())
				{
					$output["attended"] = $row["attended"];
					$output["percentAttended"] =  $row["percentAttended"];
				}

					$splitAttended = str_split($output["attended"]);
					$output["attended"] = "";
					for ($y=0; $y<count($splitAttended); $y++)
					{
						if ($splitAttended[$y] == 1)
						{
							$splitAttended[$y] = "<img src='/images/present.png' title='Week ".($y+1)."' alt='1'>";
						} else {
							$splitAttended[$y] = "<img src='/images/absent.png' title='Week ".($y+1)."' alt='0'>";
						}
						$output["attended"] .= $splitAttended[$y];
					}

					if($output["percentAttended"] >= 99.99)
					{
						$output["precentAttended"] = 100;
					}
			}
			else
			{
				$output["attended"] = "No Attendance Record Found";
				$output["percentAttended"] =  "N/A";
			}
			return $output;
		}
		catch(PDOException $e)
		{
			return "Error: " . $e->getMessage();
		}

	}

	//getFilteredAttendance(): -Selects data from the attendance table,
	//							-Filters the percentAttended column by user input 1 - 100.
	//Reference: [/admin] admin_home.php
	function getFilteredAttendance(int $_filter)
	{
		$output = null;
		$columns["attended"] = "";
		$diagram = false;

		try
		{
			$result = $this->database->prepare("SELECT * FROM attendance WHERE percentAttended<:percentAttended GROUP BY studentId ORDER BY studentId");
			$result->execute(['percentAttended' => $_filter]);
			$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
			$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
			$diagram = true;

			//Output for column names
			$output .= "<tr>";
			for ($x=0; $x<count($formatted); $x++)
			{
			$output .= "<th>" . $formatted[$x] . "</th>";
			}
			$output .= "</tr>";
			
				//Output for database results
				while ($row = $result->fetch())
				{
					$output .= "<tr>";
					for ($x=0; $x<count($columns); $x++)
					{
						//Use images to create the attendance diagram
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
										$splitAttended[$y] = "<img src='/images/present.png' title='Week ".($y+1)."' alt='1'>";
									} else {
										$splitAttended[$y] = "<img src='/images/absent.png' title='Week ".($y+1)."' alt='0'>";
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

	//getData(): -Selects data from the database by table.
	//			 -Default uses the attendance table search tool. Which searches for student attendance according to user input.
	//Reference: [/admin] admin_home.php [/lecturer] lecture_home.php
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
				case "classes":
					$result = $this->database->query("SELECT * FROM roomUsage ORDER BY id");
					$columns = array("date");
					$formatted = array("Date");
				break;
				case "attendance":
					$result = $this->database->query("SELECT * FROM attendance GROUP BY studentId, moduleId ORDER BY studentId, lectureId");
					$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
					$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
					$diagram = true;
				break;
				case "alerts":
					$result = $this->database->query("SELECT * FROM attendance WHERE percentAttended < 50 GROUP BY studentId, moduleId ORDER BY studentId, lectureId");
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
					$result = $this->database->prepare("SELECT * FROM attendance WHERE studentId=:studentId OR moduleId=:moduleId GROUP BY studentId, moduleId ORDER BY studentId, lectureId");
					$result->execute(['studentId' => $_input, 'moduleId' => $_input]);

					if ($result->fetch() === false)
					{
						$result = $this->database->prepare("SELECT * FROM roomUsage WHERE room=:room ORDER BY id");
						$result->execute(['room' => $_input]);
						$columns = array("room", "date", "fill", "scheduled", "capacity");
						$formatted = array("Room", "Date", "Fill", "Scheduled", "Capacity");
						$diagram = false;
					}
					else
					{
						$result = $this->database->prepare("SELECT * FROM attendance WHERE studentId=:studentId OR moduleId=:moduleId GROUP BY studentId, moduleId ORDER BY studentId, lectureId");
						$result->execute(['studentId' => $_input, 'moduleId' => $_input]);
						$columns = array("lectureId", "moduleId", "studentId", "attended", "percentAttended");
						$formatted = array("Lecture ID", "Module ID", "Student ID", "Weeks Attended", "% Attended");
						$diagram = true;
					}
			}

			//Output for column names
			$output .= "<tr>";
			for ($x=0; $x<count($formatted); $x++)
			{
			$output .= "<th>" . $formatted[$x] . "</th>";
			}
			$output .= "</tr>";
			
				//Output for database results
				while ($row = $result->fetch())
				{
					$output .= "<tr>";
					for ($x=0; $x<count($columns); $x++)
					{
						//Use images to create the attendance diagram
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
										$splitAttended[$y] = "<img src='/images/present.png' title='Week ".($y+1)."' alt='1'>";
									} else {
										$splitAttended[$y] = "<img src='/images/absent.png' title='Week ".($y+1)."' alt='0'>";
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
	//Reference: [/common] student.php, test_users.php
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

	//insertLecture(): -Adds a new lecture to the lectures table.
	//Reference: [/common] configure.php
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

	//insertModule(): -Adds a new module to the modules table.
	//Reference: [/common] configure.php
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

	//insertCourse(): -Adds a new course to the courses table.
	//Reference: [/common] configure.php
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

	//insertRoom(): -Adds a new room to the rooms table.
	//Reference: [/common] configure.php
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

	//updateRoomFill(): -Calculates room Fill column.
	//Reference: [/common] initial_configure.php, [/admin] admin_home.php
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

	//updateroomUsage():  -Adds rooms the roomUsage table, with column imported from the rooms table and lectures table.
	//					  -The attendance.studentId column is counted while grouped by lectureId. The count result is equal
	//					   to the number of students scheduled for a module lecture. This value is used to update the
	//					   roomUsage.scheduled column.
	//Reference: [/common] initial_configure.php, [/admin] admin_home.php
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
	//					  -Split attendance string into an array. Value $_newAttendance updates splitAttendance[week] for $_week.
	//                    -The array is repackaged into a 12-character string and written into table attendance
	//				  	  -Sum of the string is stored as $sumAttendance and divided by lecture week * 100. 
	//				 	  -This value equals the percentage of lectures that a student has attended and used to update the attendance.percentAttendance column.
	//Reference: [/common] test_users.php, [/admin] update_attendance.php, [/lecturer] update_attendance.php, [/student] attendance.php
	function updateAttendance(int $_lectureId, int $_studentId, int $_week, int $_newAttendance)
	{
		$_week = $_week - 1;

		try
		{
			$sql = $this->database->prepare("SELECT attended, moduleId FROM attendance WHERE lectureId=:lectureId AND studentId=:studentId");
			$sql->execute(['lectureId' => $_lectureId, 'studentId' => $_studentId]);
			$result = $sql->fetch();
			$attendanceStr = $result["attended"];
			$moduleId = $result["moduleId"];

			if($result !== false)
			{
			$splitAttendance = str_split($attendanceStr);
			$splitAttendance[$_week] = $_newAttendance;
			$attendanceStr = $splitAttendance[0];
			$sumAttendance = (int)$splitAttendance[0];

			for ($x=1; $x<count($splitAttendance); $x++)
			{
			$attendanceStr .= $splitAttendance[$x];
			$sumAttendance += $splitAttendance[$x];
			}
			}

			$sql = $this->database->prepare("UPDATE attendance SET attended=? WHERE moduleId=? AND studentId=?");
			$sql->execute([$attendanceStr, $moduleId, $_studentId]);

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
	//Reference: [/common] initial_configure.php, student.php
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
	//			   -True: show Alert button on admin home, False: hide Alert button on admin home.
	//Reference: [/admin] get_alerts.php
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

	//verifyAttendance(): -Used by update_attendance.php to verify if an attendance record exists before updating.
	//Reference: [/admin] update_attendance.php
	function verifyAttendance(int $_lectureId, int $_userId)
	{
		try
		{
			$result = $this->database->prepare("SELECT lectureId FROM attendance WHERE lectureId=?");
			$result->execute([$_lectureId]);
			$lectureIdResult = $result->fetch();

			$result = $this->database->prepare("SELECT studentId FROM attendance WHERE studentId=?");
			$result->execute([$_userId]);
			$studentIdResult = $result->fetch();

			if ($lectureIdResult !== false && $studentIdResult !== false)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(PDOException $e)
		{
			echo "Error: " . $e->getMessage();
		}
	}

	//Connect to Database
    private function connectDb()
	{
		$pdo = "";

		try {
			$pdo = new PDO("mysql:host=" . Globals::SERVER_LOGIN  . ";dbname=" . Globals::SERVER_DB, Globals::SERVER_USER, Globals::SERVER_PWD);
			// set the PDO error mode to exception - ERRMODE_SILENT for demo build, ERRMODE_EXCEPTION for dev
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
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