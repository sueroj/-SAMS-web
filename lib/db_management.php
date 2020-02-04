<?php declare(strict_types=1);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    createDb(checkData($_POST["record"]));
}

function checkData(string $data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Connect to MySQL Database
function createDb(string $newDb){
    connectDb();

    //Create or verify DB exists
    $sql = "CREATE DATABASE " . $newDb;
    if ($conn->query($sql) === TRUE){
        echo "database " . $newDb . " created.";
    } else {
        echo "Error creating database: " . $conn->error;
    }
}

function createRecord(int $_id, string $_first, string $_last, string $_course)
{
	$statement = $conn->prepare("INSERT INTO StudentDB (id, firstname, lastname, course) VALUES (?, ?, ?, ?)");
	$statement->bind_param("sss", $id, $firstname, $lastname, $course);

	// set parameters and execute
	$id = $_id;
	$firstname = $_first;
	$lastname = $_last;
	$course = $_course;
	$statetment->execute();

}

function viewRecord(int $_id)
{
	connectDb();
	$statement = $conn->prepare("SELECT (id, firstname, lastname, course) FROM StudentDB VALUES (?, ?, ?, ?)");
	$statement->bind_param("sss", $id, $firstname, $lastname, $course);

	// set parameters and execute
	$id = $_id;
	$firstname = $_first;
	$lastname = $_last;
	$course = $_course;
	$statement->execute();

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		}
	} else {
		echo "0 results";
	}
}

function sortRecord(){

}

function updateRecord(){

}

function deleteRecord(){

}

//Connect to Database to be reused by many other functions
function connectDb()
{
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    } else { echo "Connected to database. ";}
}
?>