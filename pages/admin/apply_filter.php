<?php declare(strict_types=1);
session_start();
if(!isset($_SESSION["loggedin"]))
{
header("location: /");
exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/functions.php";

//Gather input from admin_home to update attendance via db functions.
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $filter = $_POST["filter"];
    header("location: admin_home.php?view=filter&filter=" . $filter);
}

?>