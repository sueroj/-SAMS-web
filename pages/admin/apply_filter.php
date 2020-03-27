<?php declare(strict_types=1);
include_once "db/functions.php";
include_once "db/globals.php";

//Gather input from admin_home to update attendance via db functions.
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $filter = $_POST["filter"];
    header("location: admin_home.php?view=filter&filter=" . $filter);
}

?>