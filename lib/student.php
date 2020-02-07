<?php declare(strict_types=1);
include "db_functions.php";
include "users.php";

class Student extends Users
{
    private $id;

    private $first;

    private $last;

    private $course;

    private $module;

    private $attended;

    private $passwd;

    public function __construct(int $_id, string $_first, string $_last, string $_course, string $_passwd)
    {
        $this->id = $_id;
        $this->first = $_first;
        $this->last = $_last;
        $this->course = $_course;
        $this->passwd = md5($_passwd);
    }

    public function addStudent()
    {
       createRecord($this->id, $this->first, $this->last, $this->course, Users::Student, $this->passwd);
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