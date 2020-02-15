<?php
include_once "user.php";

//Test Users for development purposes only. Creates 2 users for each of the 3 account types student, lecturer, admin.
//
//Passwords intentionally left shown unhashed.
//

class TestUsers
{

    static function addUsers()
    {

    $id = array(11112222 ,22222222, 22223333, 33333333, 44444444, 33334444, 55556666);
    $first = array("Warren", "David", "Snoop", "Invader", "Jimmy", "Bill", "LeBron");
    $last = array("G", "Ortiz", "Dogg", "Zim", "Neutron", "Gates", "James");
    $course = array("ICT", "AI", "CS", "ICT", "CS", "", "");
    $passwd = array("abcd", "abcd", "abcd", "abcd", "abcd", "abcd", "abcd");

    for($x=0; $x<4; $x++)
    {
        self::insertUser($id[$x], $first[$x], $last[$x], $course[$x], User::Student, md5($passwd[$x]));
    }

    self::insertUser($id[5], $first[5], $last[5], $course[5], User::Lecturer, md5($passwd[5]));
    self::insertUser($id[6], $first[6], $last[6], $course[6], User::Admin, md5($passwd[6]));

    return "Test users added. Use view data to view information.";
    }

    static function insertUser(int $_id, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
	{
        $database = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
		if ($database->connect_error){
			die("Connection failed: " . $database->connect_error);
		}
        
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
        $database->query($sql);
		
		if ($database->error !== "") {
	    $output = $database->error . "<br>";
		} else {
				$output = "New user added<br>";
                }
        $database->close();
        return $output;
    }
    
    function generateAttendance()
    {
        $openDb = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
		if ($openDb->connect_error){
			die("Connection failed: " . $openDb->connect_error);
        }
        
        $database = new Database();

        $sql = "SELECT lectureId, studentId FROM attendance";
        $result = $openDb->query($sql);    

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {
                $database->updateAttendance($row["lectureId"], $row["studentId"], rand(0,1));
            }
        }
        $openDb->close();
    }
}

?>