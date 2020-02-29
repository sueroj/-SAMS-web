<?php
include_once "test_users.php";
include_once "configure.php";
include_once "functions.php";


$configure = new Configure();
$database = new Database();

echo $configure->createAttendance() . "<br>";
echo $configure->createLectures() . "<br>";
echo $configure->createModules() . "<br>";
echo $configure->createCourses() . "<br>";
echo $configure->createRooms() . "<br>";
echo $configure->createRoomUsage()."<br>";
echo $configure->createStudents() . "<br>";
echo $configure->createLecturers() . "<br>";
echo $configure->createAdmins() . "<br>";

echo TestUsers::addUsers() . "<br>";
echo $database->insertAttendance() . "<br>";
echo TestUsers::generateAttendance() . "<br>";

echo $database->updateRoomUsage() . "<br>";
echo $database->updateRoomFill() . "<br>";

header( "refresh:5;url=/" );
echo 'You will be redirected in 5 seconds. If not, click <a href="/">here</a>.'; 

?>