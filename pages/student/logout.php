<?php
//simple php to destroy sessions and logout user, then redirect to login page
session_start();
session_destroy();
header('Location: /');
?>
