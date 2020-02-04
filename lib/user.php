<html>
    <body>

<?php

$name;

//if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $name = htmlspecialchars($_GET["usr"]);
    
    if (empty($name))
    {
        echo "Name is empty";
    }
    else{
        echo $name . " Success";
    }
//}

?>

</body>
</html>
