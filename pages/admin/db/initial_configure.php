<?php
include_once "test_users.php";
include_once "configure.php";


$configure = new Configure();

echo $configure->createAttendance() . "<br>";
echo $configure->createLectures() . "<br>";
echo $configure->createModules() . "<br>";
echo $configure->createCourses() . "<br>";
echo $configure->createRooms() . "<br>";
echo $configure->createRoomCapacity()."<br>";
echo $configure->createStudents() . "<br>";
echo $configure->createLecturers() . "<br>";
echo $configure->createAdmins() . "<br>";

echo TestUsers::addUsers();

?>