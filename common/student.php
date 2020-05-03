<?php declare(strict_types=1);
include "functions.php";
include "user.php";

class Student extends User
{
    private $course;

    private $attended;

    private $passwd;

    public function __construct(int $_id, string $_first, string $_last, string $_course, string $_passwd)
    {
        $this->id = $_id;
        $this->first = $_first;
        $this->last = $_last;
        $this->course = $_course;
        $this->passwd = password_hash($_passwd, PASSWORD_DEFAULT);
    }

    public function addStudent()
    {
        $database = new Database();
        $database->insertUser($this->id, $this->first, $this->last, $this->course, User::Student, $this->passwd);
        $database->insertAttendance();
    }

    //Data input security check
    private function checkData(string $data)
    {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
}
?>