<?php
//initial_configure.php: -*****FOR DEVELOPMENT USE ONLY*****
//                       -This is used for setting up new systems with the database configuration 
//                        required for the SAMS web application demonstration.
require_once "test_users.php";
require_once "configure.php";
require_once "functions.php";


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
echo TestUsers::generateUsers();
echo $database->insertAttendance() . "<br>";
echo TestUsers::generateAttendance() . "<br>";

echo $database->updateRoomUsage() . "<br>";
echo $database->updateRoomFill() . "<br>";

header( "refresh:5;url=/" );
echo 'You will be redirected in 5 seconds. If not, click <a href="/">here</a>.'; 

?>