<?php
include_once "db/functions.php";
include_once "db/globals.php";
//
//Used by the admin_home.php page to query the database for any attendance records < 50%.
//			   See db/functions -> getAlerts()...
$database = new Database();

echo $database->getAlerts();

?>