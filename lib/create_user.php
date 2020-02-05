<?php declare(strict_types=1);
include "student.php";

//Gather input to create objects from Student Class
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = (int)$_POST["id"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $course = $_POST["course"];
    $pass = $_POST["psw"];
    $student = new Student($id, $fname, $lname, $course);
    $student->addStudent();
}

?>