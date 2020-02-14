<?php declare(strict_types=1);
include "student.php";

//Gather input to create objects from Student Class
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = (int)$_POST["sid"];
    $first = $_POST["fname"];
    $last = $_POST["lname"];
    $course = $_POST["course"];
    $passwd = $_POST["psw"];
    $student = new Student($id, $first, $last, $course, $passwd);
    $student->addStudent();
}

?>