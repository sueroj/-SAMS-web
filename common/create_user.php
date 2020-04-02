<?php declare(strict_types=1);
include_once "student.php";
include_once "globals.php";

//Gather input to create objects from Student Class
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $userId = (int)$_POST["userId"];
    $first = $_POST["fname"];
    $last = $_POST["lname"];
    $course = $_POST["course"];
    $passwd = $_POST["psw"];
    $student = new Student($userId, $first, $last, $course, $passwd);
    $student->addStudent();

    header( "refresh:5;url=/" );
    echo "New user added.<br>";
    echo 'You will be redirected in 5 secs. If not, click <a href="/">here</a>.';
    die();
}



?>