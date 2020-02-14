<?php
//include "db/functions.php";
include "user.php";

//Test Users for development purposes only. Creates 2 users for each of the 3 account types student, lecturer, admin.
//
//Passwords intentionally left shown unhashed.
//
class TestUsers
{

    static function addUsers()
    {
    $database = new Database();

    $id = 11112222;
    $first = "Warren";
    $last = "G";
    $course = "MSc Urban Planning";
    $passwd = "abcd";
    $database->insertStudent($id, $first, $last, $course, User::Student, md5($passwd));
    
    $id = 22223333;
    $first = "Snoop";
    $last = "Dogg";
    $course = "Music Theory";
    $passwd = "1234";
    $database->insertStudent($id, $first, $last, $course, User::Student, $passwd);
    
    $id = 33334444;
    $first = "Bill";
    $last = "Gates";
    $course = "MSc Comp Science";
    $passwd = "abcd";
    $database->insertStudent($id, $first, $last, $course, User::Lecturer, md5($passwd));

    $id = 44445555;
    $first = "Steve";
    $last = "Jobs";
    $course = "MSc Machine Learning";
    $passwd = "abcd";
    $database->insertStudent($id, $first, $last, $course, User::Lecturer, $passwd);

    $id = 55556666;
    $first = "LeBron";
    $last = "James";
    $course = "Sports Science";
    $passwd = "abcd";
    $database->insertStudent($id, $first, $last, $course, User::Admin, md5($passwd));

    $id = 12312312;
    $first = "Stephen";
    $last = "Curry";
    $course = "Sports Science";
    $passwd = "abcd";
    $database->insertStudent($id, $first, $last, $course, User::Admin, $passwd);

    return "Test users added. Use view data to view information.";
    }
}

?>