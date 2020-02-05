<?php declare(strict_types=1);
include "globals.php";

function createTables()
{
	$database = connectDb();
	
	//Verify if database "alumni" exists in MySQL first; if not, create one.
	$sql = "CREATE DATABASE samsdb";
	if ($database->query($sql) === TRUE){
		$output =  "New database samsdb created.\n";
	}

	$sql = "CREATE TABLE alumni (
	id INT(7) UNSIGNED PRIMARY KEY,
	firstname VARCHAR(30) NOT NULL,
	lastname VARCHAR(30) NOT NULL,
	course VARCHAR(30) NOT NULL,
	lecturer BOOL
	)";

	if ($database->query($sql) === TRUE) {
		$output = "Table alumni created successfully";
	} else {
		$output = "Error creating table: " . $database->error;
	}
	return $output;
}

function viewRecord(int $_id)
{
	$database = connectDb();

	$sql = "SELECT id, firstname, lastname, course FROM alumni";
	$result = $database->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$output = "<td>" . $row["id"] . "</td>".
					  "<td>" . $row["firstname"] . "</td>".
					  "<td>" . $row["lastname"] . "</td>".
					  "<td>" . $row["course"] . "</td>"."<br>";
		}
	} else {
		$output = "0 results";
	}
	return $output;
}

function createRecord(int $_id, string $_first, string $_last, string $_course, $_acctType)
{
	$database = connectDb();

	$sql = "INSERT INTO alumni (id, firstname, lastname, course, lecturer)
	VALUES ('$_id', '$_first', '$_last', '$_course', '$_acctType')";
	
	if ($database->query($sql) === TRUE) {
    $output = "New record processed.";
	} else {
			$output = "Error: " . $sql . "<br>" . $database->error;
			}

	$database->close();
	echo $output;
}

function sortRecord(){

}



function deleteTable(){

}

//Connect to Database to be reused by many other functions
function connectDb()
{
	$conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}
	return $conn;
}

//Initial Database creation.
function createDb()
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