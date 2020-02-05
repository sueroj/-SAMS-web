<?php declare(strict_types=1);
include "globals.php";

//Connect to MySQL Database
function createDb(string $newDb){
	$database = connectDb();
	//Create or verify DB exists
	$sql = "CREATE DATABASE " . $newDb;
	if ($database->query($sql) === TRUE){
		echo "database " . $newDb . " created.";
	} else {
		echo "Error creating database: " . $database->error;
	}
}

function createTable(int $_id, string $_first, string $_last, string $_course)
{
	$database = connectDb();
	
	//Verify if database "studentdb" exists in MySQL first; if not, create one.
	$sql = "CREATE DATABASE studentdb";
	if ($database->query($sql) === TRUE){
		echo "New database studentdb created.";
	}

	$statement = $database->prepare("CREATE TABLE student (id INT(7) UNSIGNED PRIMARY KEY,
	firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, course VARCHAR(30) NOT NULL) VALUES (?, ?, ?, ?)");
	$statement->bind_param("isss", $id, $firstname, $lastname, $course);

	// set parameters and execute
	$id = $_id;
	$firstname = $_first;
	$lastname = $_last;
	$course = $_course;
	$statement->execute();
}

function viewTable(int $_id)
{
	$database = connectDb();
	$statement = $database->prepare("SELECT id FROM student VALUES (?)");
	$statement->bind_param("i", $id);

	// set parameters and execute
	$id = $_id;
	// $firstname = $_first;
	// $lastname = $_last;
	// $course = $_course;
	$statement->execute();

	$result = $database->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		}
	} else {
		echo "0 results";
	}
}

function sortTable(){

}

function updateTable(){

}

function deleteTable(){

}

//Connect to Database to be reused by many other functions
function connectDb()
{
	$conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD, Globals::SERVER_DB);
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	} else { echo "Connected to database. ";}
	
	return $conn;
}
?>