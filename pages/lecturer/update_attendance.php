<?php declare(strict_types=1);
session_start();
if(!isset($_SESSION["loggedin"]))
{
header("location: /");
exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/functions.php";
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