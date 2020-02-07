<?php declare(strict_types=1);
include "globals.php";

function createTable()
{
	$database = connectDb();
	
	//Verify if database "alumni" exists in MySQL first; if not, create one.
	$sql = "CREATE DATABASE samsdb";
	if ($database->query($sql) === TRUE){
		$output =  "New database samsdb created.\n";
	}

	$sql = "CREATE TABLE alumni (
	id INT(7) UNSIGNED PRIMARY KEY,
	first VARCHAR(30) NOT NULL,
	last VARCHAR(30) NOT NULL,
	course VARCHAR(30) NOT NULL,
	account INT(1) UNSIGNED,
	passwd VARCHAR(40) NOT NULL
	)";

	if ($database->query($sql) === TRUE) {
		$output = "Table alumni created successfully";
	} else {
		$output = "Error creating table: " . $database->error;
	}
	return $output;
}

function viewRecord(string $_input)
{
	$output = null;
	$database = connectDb();

	if($_input == "all"){
		$sql = "SELECT * FROM alumni ORDER BY id";
		$result = $database->query($sql);
	} else {
		$sql = "SELECT * FROM alumni WHERE id='$_input' ORDER BY id";
		$result = $database->query($sql);
	}


	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$output .= "<tr><td>" . $row["id"] . "</td>".
					   "<td>" . $row["first"] . "</td>".
					   "<td>" . $row["last"] . "</td>".
					   "<td>" . $row["course"] . "</td>".
					   "<td>" . $row["passwd"] . "</td></tr>";
		}
	} else {
		$output = "0 results";
	}
	return $output;
}

function createRecord(int $_id, string $_first, string $_last, string $_course, int $_acct, string $_passwd)
{
	$database = connectDb();

	$sql = "INSERT INTO alumni (id, first, last, course, account, passwd)
	VALUES ('$_id', '$_first', '$_last', '$_course', '$_acct', '$_passwd')";
	
	if ($database->query($sql) === TRUE) {
    $output = "New record processed.";
	} else {
			$output = "Error: " . $sql . "<br>" . $database->error;
			}

	$database->close();
	echo $output;
}



function deleteTable()
{
	$database = connectDb();

	$sql = "DROP TABLE alumni";

	if ($database->query($sql) === TRUE) {
		$output = "Table alumni deleted";
	} else {
		$output = "Error deleting table: " . $database->error;
	}
	return $output;
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