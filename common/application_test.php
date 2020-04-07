<?php
//application_test.php: -*****FOR DEVELOPMENT USE ONLY*****
//                      -This is used for automated testing within the SAMS web application.
require_once "test_users.php";
require_once "configure.php";
require_once "functions.php";


$configure = new Configure();
$database = new Database();

$output = "";
$testName = "Test Login Input";
$userInput = array("", " ", "aaa", 123, "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", "!@#$%^", "''''''''''", 1000000*1000000, "<h1>html</h1>", "<script>alert('')</script>");
$passInput = array("", " ", "aaa", 123, "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", "!@#$%^", "''''''''''", 1000000*1000000, "<h1>html</h1>", "<script>alert('')</script>");

try 
{
    for ($x=0; $x<count($userInput); $x++)
    {
        for ($y=0; $y<count($passInput); $y++)
        {
            $output = $database->verifyAccount(checkData($userInput[$x]), checkData($passInput[$y]));
        }
    }
    echo "Login Tests Passed<br>";

}
catch (Exception $e)
{
    echo $testName . ": " . $output . " " . $e-getMessage();
}

header( "refresh:5;url=/" );
echo 'You will be redirected in 5 seconds. If not, click <a href="/">here</a>.'; 

//Data input security check
function checkData(string $data)
{
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}


?>