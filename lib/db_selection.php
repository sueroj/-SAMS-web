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
            createTable(checkData($_POST)["record"]);
        break;
        case "view":
            viewTable(checkData((int)$_POST["record"]));
        break;
		default:
			echo "Enter selection";
	}
}

function checkData(string $data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>