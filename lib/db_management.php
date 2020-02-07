﻿<?php
//
//This is the main page for the SAMS web backend environment. This page contains tools for
//managing and viewing the database.
include "db_functions.php";
$output = null;
$record = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	switch (checkData($_POST["selection"]))
	{
		case "configure": 
			$output = createDb();
            $output = createTable();
        break;
        case "update":
            $output = updateRecord(checkData($_POST["record"]));
        break;
        case "view":
            $record = viewRecord(checkData($_POST["record"]));
        break;
        case "delete":
            $output = deleteRecord(checkData($_POST["record"]));
        break;
        case "drop":
            $output = deleteTable();
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