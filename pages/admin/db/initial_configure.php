<?php
include "functions.php";
include "configure.php";
include "test_users.php";


$configure = new Configure();

echo $configure->createAttendance() . "<br>";
echo $configure->createLectures() . "<br>";
echo $configure->createModules() . "<br>";
echo $configure->createCourses() . "<br>";
echo $configure->createRooms() . "<br>";
echo $configure->createStudents() . "<br>";
echo $configure->createLecturers() . "<br>";
echo $configure->createAdmins() . "<br>";

echo TestUsers::addUsers();

?>