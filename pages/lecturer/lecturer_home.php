<?php
session_start();
include_once "db/functions.php";
include_once "db/configure.php";
include_once "db/test_users.php";

$database = new Database();
$configure = new Configure();

$configure->checkDb();

$output = "";

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
	switch (checkData($_GET["view"]))
	{
        case "modules":
            $output = $database->getModules();
            //$output = $database->getData("modules");
        break;
        case "classes":
            //$output = $database->getData("classes");
        break;
        case "attendance":
            //$output = $database->getData("attendance");
        break;
        case "logout":
            session_unset();
            session_destroy();
            header("location: /");
            die();
        break;
		default:
			$output = "Invalid option.";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $record = $database->viewModule($_POST["module"]);
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
	<link rel="stylesheet" type="text/css" href="/css/lecturer_styles.css"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>SAMS | Log In</title>
</head>

<body>
 	<nav class="global_nav_bar">
 		<div class="logo" title="Return to Home Page">SAMS</div>
    	<div class="nav_bar">Student Adminstration Management System</div>
    </nav>

<div class="content">
    <nav class="v_nav_bar">
        <a class="v_nav" href="lecturer_home.php?view=modules">Modules</a>
        <a class="v_nav" href="lecturer_home.php?view=classes">Classes</a>
        <a class="v_nav" href="lecturer_home.php?view=attendance">Attendance</a>
    </nav>
    
    <div class="select_form">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="modules">Select module:</label>
            <select id="modules" name="module">
                <?php echo $output; ?>
            </select>

                

          <!--  <label for="week">Select week:</label>
            <select id="week" name="week">    
                   <?php/* 
                    for ($x=1; $x<13; $x++)
                    {
                        echo "<option value='$x'>" . "Week " . $x . "</option>";
                    }*/
                    ?>  
            </select> -->
            <button class="submit_btn" type="submit">Submit</button>
        </form>
    </div>

    <div id="data" class="data">
            <table>

            <?php echo $record; ?>

            </table>
    </div>

</div>

</body>
</html>