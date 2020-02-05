<?php declare(strict_types=1);
include "db_functions.php";

class Student
{
    private $id;

    private $firstname;

    private $lastname;

    private $course;

    private $module;

    private $attended;

    private $password;

    public function __construct(int $_id, string $_first, string $_last, string $_course)
    {
        $this->id = $_id;
        $this->firstname = $_first;
        $this->lastname = $_last;
        $this->course = $_course;
    }

    public function addStudent()
    {
       createRecord($this->id, $this->firstname, $this->lastname, $this->course, 0);
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