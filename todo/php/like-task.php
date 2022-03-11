<?php
session_start();

include_once "../modules/mod-functions.php";
require_once "../modules/db-config.php";

if(!isset($_GET['id']))
{
    AddAlert("Eroare la accesarea paginii.", "danger");
    header("location: ../index.php");
    die();
}

$task = $_GET['id'];

if(!LoggedIn())
{
    AddAlert("Nu sunteți conectat.", "danger");
    header("location: ../index.php");
    die();
}

if(!LikeUnlikeTask($task, $_SESSION['userid']))
{
    AddAlert("A apărut o eroare.", "danger");
    header("location: ../index.php");
    die();
}

AddAlert("Acțiune efectuată cu succes", "success");

$previous = "../index.html";
if(isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
}
header("location: ".$previous);
die();

?>