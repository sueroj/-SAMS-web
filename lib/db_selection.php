<?php
include "db_functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	switch (checkData($_POST["selection"]))
	{
		case "newDatabase": 
			createDb(checkData($_POST["record"]));
        break;
        case "create":
            createTables(($_POST)["record"]);
        break;
        case "view":
            viewRecord(checkData($_POST["record"]));
        break;
		default:
			echo "Enter selection";
	}
}

function checkData($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>