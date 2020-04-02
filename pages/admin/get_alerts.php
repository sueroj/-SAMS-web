<?php
session_start();
if(!isset($_SESSION["loggedin"]))
{
header("location: /");
exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/functions.php";

//Used by the admin_home.php page to query the database for any attendance records < 50%.
//			   See db/functions -> getAlerts()...

$database = new Database();

echo $database->getAlerts();

?>