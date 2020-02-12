﻿<?php
include "db_functions.php";
include "db_configure.php";
include "../static/testUsers.php"; //test only -- delete after testing

//
//This is the main page for the SAMS web backend environment. This page contains tools for
//managing and viewing the database.

checkDb();
$database = new Database();
$configure = new Configure();
$output = null;
$record = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	switch (checkData($_POST["selection"]))
	{
		case "configure": 
			$output = $database->createDb();
            $output = $database->createTable();
            $output = $configure->createStudents();
            $output = $configure->createCourses();
            //output = $configure->createModules();
            $output = $configure->createRooms();
            //$output = $configure->createAttendance();
        break;
        case "update":
            //$output = $database->updateRecord(checkData($_POST["record"]));
        break;
        case "view":
            $record = $database->viewRecord(checkData($_POST["record"]));
        break;
        case "delete":
            //$output = $database->removeStudent(checkData($_POST["record"]));
        break;
        case "drop":
            $output = $database->dropStudents();
        break;
        case "testUsers":           //Test Users for development purposes only
            $output = TestUsers::addUsers();
        break;
		default:
			$record = "Invalid option.";
	}
}

function checkData($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function checkDb()
{
    $database = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
    if ($database->connect_error){
        die("Connection failed: " . $conn->connect_error);	
    }

    //Create or verify DB exists
    $sql = "CREATE DATABASE samsdb";
    if ($database->query($sql) === TRUE){
        $output = "database samsdb created.";
    } else {
        $output = "Error creating database: " . $database->error;
    }
    return $output;
}


?>

<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="/css/db_styles.css"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SAMS | Database Management</title>
</head>

<body>
<h1>SAMS Database Management</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="textbox">
    <input type="text" name="record" placeholder="Enter Record">
        <select name="selection">
            <option value="view">View Record</option>
            <option value="update">Update Record</option>
            <option value="delete">Delete Record</option>
            <option value="drop">Delete Table</option>
            <option value="configure">Configure Database</option>
            <!-- Test Users for development only -->
            <option value="testUsers">Create Test Users</option>
        </select>
    <button type="submit">Submit</button>
    </div>
</form>
<div>
    <?php
        $conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
        if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
        } else { echo "Database Status: Connected. ";}
    ?>
</div>

<div class="data">
<table>

    <?php echo $record; ?>

</table>
</div>
        Console:
    <div class="data" style="height:200px;">
        <?php echo $output; ?>
    </div>


</body>
<html>