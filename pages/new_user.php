<?php declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/student.php";

$error = null;

//Gather input to create objects from Student Class
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $userId = (int)checkData($_POST["userId"]);

  //Filter input validation: input must be between 6 - 8 digits.
  $options = array(
    'options' => array(
      'min_range' => 99999,
      'max_range' => 100000000
    )
  );

  if (filter_var($userId, FILTER_VALIDATE_INT, $options) === false)
  {
    $error = "Invalid Student ID Format";
  }
  else
  {
    $first = checkData($_POST["fname"]);
    $last = checkData($_POST["lname"]);
    $course = checkData($_POST["course"]);
    $passwd = checkData($_POST["psw"]);
    $passwdCheck = checkData($_POST["psw_check"]);

    if ($passwd === $passwdCheck)
    {
      $student = new Student($userId, $first, $last, $course, $passwd);
      $student->addStudent();

      header( "refresh:5;url=/" );
      echo "New user added.<br>";
      echo 'You will be redirected in 5 secs. If not, click <a href="/">here</a>.';
      die();
    } 
    else
    {
      $error = "Passwords do not match. Please Re-enter.";
    }
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
	<link rel="stylesheet" type="text/css" href="/css/styles.css"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SAMS | Account Creation</title>
</head>

<body>
    <nav class="global_nav_bar">
      <div class="logo" title="Return to Home Page"><a class="logo" href="/">SAMS</a></div>
      <div class="nav_bar">Student Adminstration Management System</div>
   </nav>
   
   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
     <div class="textbox">
       <label for="userId"><b>Student ID</b></label>
       <input type="text" placeholder="Enter Student ID" name="userId" required>

       <label for="fname"><b>First Name</b></label>
       <input type="text" placeholder="Enter First Name" name="fname" required>

       <label for="lname"><b>Last Name</b></label>
       <input type="text" placeholder="Enter Last Name" name="lname" required>

       <label for="course"><b>Course</b></label>
       <select name="course">
         <option value="ICT">Information and Computing Technology</option>
         <option value="CS">Computer Science</option>
         <option value="AI">Artificial Intelligence</option>
         <option value="SS">Sports Science</option>
         <option value="AH">Animal Husbandry</option>
       </select>
   
       <label for="psw"><b>Password</b></label>
       <input type="password" placeholder="Enter Password" name="psw" required>
       
       <label for="psw_check"><b>Re-Enter Password</b></label>
       <input type="password" placeholder="Re-Enter Password" name="psw_check" required>

       <label class="test"><?php echo $error; ?></label>
       <button type="submit" class="button">Submit</button>
     </div>
   </form>
</html>