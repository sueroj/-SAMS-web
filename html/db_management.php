<<<<<<< HEAD
﻿<?php
include "../lib/db_functions.php";
$output = null;
$record = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	switch (checkData($_POST["selection"]))
	{
		case "newDatabase": 
			$output = createDb();
        break;
        case "createTables":
            $output = createTables(($_POST)["record"]);
        break;
        case "update":
            $output = updateRecord(checkData($_POST["record"]));
        break;
        case "view":
            $record = viewRecord(checkData($_POST["record"]));
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
<nav>
       <h1>SAMS Database Management</h1>
</nav>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="textbox">
    <input type="text" name="record" placeholder="Enter Record" required>
        <select name="selection">
            <option value="null">Manage Records</option>
            <option value="newDatabase">Create Database</option>
            <option value="createTables">Create Tables</option>
        </select>
    <input type="radio" name="selection" value="view">View Record
    <input type="radio" name="selection" value="update">Update Record
    <input type="radio" name="selection" value="delete">Delete Record
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
<!--<tr>
    <th>SID</th>
    <th>Last</th>
    <th>First</th>
    <th>Course</th>
</tr>-->
<!--PHP code will be placed here to auto create table data
will possibly move php code to seperate file with link-->
<tr>
    <?php echo $record; ?>
</tr>
</table>
</div>
        Output:
    <div class="data" style="height:200px;">
        <?php echo $output; ?>
    </div>


</body>
=======
﻿<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SAMS | Database Management</title>
    <style>
        body {
            background-color: #ffffcc;
            margin: 20px;
        }
        input[type=text], input[type=password] {
 		margin: 8px 0;
  		border: 1px solid #aeb6bf;
        box-sizing: border-box;
        width: 250px;
        }
        .data {
            box-sizing: border-box;
            border:1px solid black;
            width: 800px;
            height: 600px;
            background-color: white;
        }
        table{
            width: 100%;
            
        }
        th{
            border-bottom: 1px solid black
        }
        td {
            text-align: center;
        }
        .serverStatus {
            border: 1px solid black;
            fill: green;
        }
    </style>
</head>

<body>
<nav>
       <h1>SAMS Database Management</h1>
</nav>
<form action="/lib/db_selection.php" method="POST">
    <div class="textbox">

    <input type="text" name="record" placeholder="Enter Record" required>
    <input type="radio" name="selection" value="newDatabase">Create Database
    <input type="radio" name="selection" value="create">Create Record
    <input type="radio" name="selection" value="view">View Record
    <input type="radio" name="selection" value="update">Update Record
    <input type="radio" name="selection" value="delete">Delete Record
    <button type="submit">Submit</button>
    </div>
</form>

<div class="data">
<table>
<tr>
    <th>SID</th>
    <th>Last</th>
    <th>First</th>
    <th>Course</th>
</tr>
<!--PHP code will be placed here to auto create table data
will possibly move php code to seperate file with link-->
<tr>
    <td>sample1</td>
    <td>sample2</td>
    <td>sample3</td>
    <td>sample4</td>
</tr>
<tr>
        <td>Joe</td>
        <td>sample2</td>
        <td>sample3</td>
        <td>sample4</td>
</tr>

</table>
</div>

<!--PHP code for database status query here-->
<div>
    <?php
    include "../lib/globals.php";
        $conn = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
        if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
        } else { echo "Database Status: Connected. ";}
    ?>
</div>

</body>
>>>>>>> e518cec3e89e17d4576f190778cfed755f01a3d1
<html>