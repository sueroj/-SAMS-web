<?php
session_start();
require_once "common/functions.php";
require_once "common/configure.php";
require_once "common/user.php";

$configure = new Configure();
$configure->checkDb();

//Session check: Checks if user is already logged into the system,
//automatically proceeds to appropriate homepage if the user is logged in.
// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
// {
// 	switch ($_SESSION["account"])
// 	{
// 		case User::Student:
// 			header("location: /pages/student/student_home.php");
// 			break;
// 		case User::Lecturer:
// 			header("location: /pages/lecturer/lecturer_home.php");
// 			break;
// 		case User::Admin:
// 			header("location: /pages/admin/admin_home.php?view=students");
// 			break;
// 	}
// 	exit();
// }

$user = $passwd = "";
$error = null;
$database = new Database();

//Username/password verification, send to directory.php if okay.
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if($database->verifyAccount(checkData($_POST["user"]), checkData($_POST["passwd"])))
	{
		switch ($_SESSION["account"])
		{
			case User::Student:
				header("location: /pages/student/student_home.php");
				break;
			case User::Lecturer:
				header("location: /pages/lecturer/lecturer_home.php?view=modules");
				break;
			case User::Admin:
				header("location: /pages/admin/admin_home.php?view=students");
				break;
		}
		exit();
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
	    <label for="user"><b>User ID</b></label>
		<input id="userId" type="text" placeholder="Enter User ID" name="user" required>
	
	    <label for="passwd"><b>Password</b></label>
	    <input id="passwd" type="password" placeholder="Enter Password" name="passwd" required>
	    
		<label class="password"><a href="pages/new_user.html">New User?</a></label>
		
		<label class="test"><?php echo $error; ?></label>
	    <button type="submit" class="button">Login</button>
	    
	  </div>
	</form>
	<a href="pages/admin/db/initial_configure.php">Initial Config</a>

</body>
<script>
// var userId = document.getElementById("userId");
// var passwd = document.getElementById("passwd");
// output.innerHTML = slider.value;

// slider.oninput = function() {
//   output.innerHTML = this.value;
// }
</script>
</html>