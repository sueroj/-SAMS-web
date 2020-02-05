<<<<<<< HEAD
<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}   else
    { 
        echo "Connection Successful";
    }

=======
<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}   else
    { 
        echo "Connection Successful";
    }

>>>>>>> e518cec3e89e17d4576f190778cfed755f01a3d1
?>