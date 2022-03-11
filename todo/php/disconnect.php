<?php
session_start();
include_once "../modules/mod-functions.php";


// Unset session variables to log out
unset($_SESSION['loggedin']);
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION["admin"]);

// redirect to login page
AddAlert("Te-ai deconectat.", "warning");
header("location: /php/login.php");
die();

?>