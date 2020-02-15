<?php
session_start();
include_once "pages/admin/db/functions.php";
include_once "scripts/user.php";
include_once "scripts/globals.php";

//Session "checker": Checks if user is already logged into the system,
//automatically proceeds to directory.php if the user is logged in.
//Commented out for development/debug.
// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
// {
// 	header("location: /html/directory.php");
// 	exit;
// }

$user = $passwd = "";
$error = null;

checkDb();

//Username/password verification, send to directory.php if okay.
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$database = new Database();

	if(empty(checkData($_POST["user"]))){
		$user_err = "Enter Username.";
	} else {
		$user = checkData($_POST["user"]);
	}

	if(empty(checkData($_POST["passwd"]))){
		$passwd_err = "Enter Username.";
	} else {
		$passwd = checkData($_POST["passwd"]);
	}

	if($database->verifyAccount(checkData($_POST["user"]), checkData($_POST["passwd"])))
	{
		switch ($_SESSION["account"])
		{
			case User::Student:
				header("location: /pages/student/student_home.php");
				break;
			case User::Lecturer:
				header("location: /pages/lecturer/lecturer_home.html");
				break;
			case User::Admin:
				header("location: /pages/admin/admin_home.php");
				break;
		}
	} else {
		$error = "Invalid username and/or password.";
	}
}

//Data input security check
function checkData(string $data)
{
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}

function checkDb()
{
    $database = new mysqli(Globals::SERVER_LOGIN, Globals::SERVER_USER, Globals::SERVER_PWD);
    if ($database->connect_error){
        die("Connection failed: " . $database->connect_error);	
    }

    //Create or verify DB exists
    $sql = "CREATE DATABASE samsdb";
    if ($database->query($sql) === TRUE){
        $output = "database samsdb created.";
	}
}
?>

<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>SAMS | Log In</title>
</head>

<body>
 	<nav class="global_nav_bar">
 		<div class="logo" title="Return to Home Page">SAMS</div>
    	<div class="nav_bar">Student Adminstration Management System</div>
    </nav>
    
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	  <div class="textbox">
	    <label for="user"><b>Username</b></label>
		<input type="text" placeholder="Enter Username" name="user" required>
	
	    <label for="passwd"><b>Password</b></label>
	    <input type="password" placeholder="Enter Password" name="passwd" required>
	    
		<label class="password"><a href="pages/new_user.html">New User?</a></label>
		
		<label class="test"><?php echo $error; ?></label>
	    <button type="submit" class="button">Login</button>
	    
	  </div>
	</form>
	<a href="pages/admin/db/initial_configure.php">Initial Config</a>

</body>
</html>