<?php
require_once "user.php";
require_once "functions.php";

//test_users.php: -*****FOR DEVELOPMENT USE ONLY*****
//                -Creates 2 users for each of the 3 account types student, lecturer, admin.
//                -Passwords intentionally left shown unhashed.

class TestUsers
{
    static function addUsers()
    {
        $database = new Database();

        $id = array(11112222 ,22222222, 22223333, 33333333, 44444444, 33334444, 55556666);
        $first = array("Tim", "David", "Snoop", "Invader", "Jimmy", "Bill", "John");
        $last = array("Warner", "Ortiz", "Dogg", "Zim", "Neutron", "Gates", "Smith");
        $course = array("ICT", "AI", "CS", "ICT", "CS", "", "");
        $passwd = array("abcd", "abcd", "abcd", "abcd", "abcd", "abcd", "abcd");

        for($x=0; $x<4; $x++)
        {
            $database->insertUser($id[$x], $first[$x], $last[$x], $course[$x], User::Student, md5($passwd[$x]));
        }

        $database->insertUser($id[5], $first[5], $last[5], $course[5], User::Lecturer, md5($passwd[5]));
        $database->insertUser($id[6], $first[6], $last[6], $course[6], User::Admin, md5($passwd[6]));

        return "Test users added.";
    }

    static function generateUsers()
    {
        $database = new Database();

        $id = 10000000;
        $first = array("Jimmy", "Ryan", "Dude", "George", "Sergei", "Vincenzo", "Egan", "Juan", "Matt", "Oliver");
        $last = array("Smith", "Petit", "Ortiz", "Sanchez", "Bernal", "Gomez", "Thomas", "Mitchell", "Bridgewood", "Lloyd");
        $course = array("ICT", "CS", "AI", "SS", "AH");

        for($x=0; $x<30; $x++)
        {
            $database->insertUser($id++, $first[rand(0,9)], $last[rand(0,9)], $course[rand(0,4)], User::Student, md5("abcd"));
        }
    }
    
    function generateAttendance()
    {
        $output = "";

        $openDb = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
		if ($openDb->connect_error){
			die("Connection failed: " . $openDb->connect_error);
        }
        
        $database = new Database();

        $sql = "SELECT lectureId, studentId, lectures.week FROM attendance
                INNER JOIN lectures ON lectures.Id = attendance.lectureId";
        $result = $openDb->query($sql);    

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {
                for($x=0; $x<5; $x++)
                {
                    $output .= $database->updateAttendance($row["lectureId"], $row["studentId"], rand(1,($row["week"])), rand(0,1));
                }
            }
        }
        $openDb->close();
        return $output;
    }
}
?>