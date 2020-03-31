<?php declare(strict_types=1);
include_once "db/functions.php";
include_once "db/globals.php";
//Gather input from admin_home to update attendance via db functions.

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $lectureId = $_POST["lectureId"];
    $studentId = (int)$_POST["studentId"];
    $week = (int)$_POST["week"];
    $newAttendance = $_POST["newAttendance"] == "attended" ? 1 : 0;

    $database = new Database();
    $database->updateAttendance($lectureId, $studentId, $week, $newAttendance);

    header("location: lecturer_home.php?view=attendance");
}

?>