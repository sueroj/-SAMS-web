<?php declare(strict_types=1);

class Student
{
    private $id;

    private $firstname;

    private $lastname;

    private $course;

    private $module;

    private $attended;

    private $password;

    public function Student(int $_id, string $_first, string $_last, int $_course)
    {
        $id = $_id;
        $first = $_first;
        $last = $_last;
        $course = $_course;
    }

}
?>