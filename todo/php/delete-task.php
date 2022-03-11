<?php

session_start();

// Database Connection module and functions
include_once "../modules/mod-functions.php";
require_once "../modules/db-config.php";

// check post task id is set
if(!isset($_POST['t']))
{
    AddAlert("Eroare la accesarea paginii.", "danger");
    header("location: ../index.php");
    die();
}

$taskid = $_POST['t'];

// check requested task exists
if(!($requested_task = (SELECT_GetTasksGlobal("of_id", $_SESSION['userid'], "", $taskid))[0]))
{
    AddAlert("Nu există postarea cerută", "danger");
    header("location: ../index.php");
    die();
}

// check current user is owner of task
if(!(isUser($requested_task['username'])))
{
    AddAlert("Nu poți șterge postarea altui utilizator", "danger");
    header("location: ../index.php");
    die();
}

// Attempt to delete task by id
if(!DELETE_TaskById($requested_task['taskid']))
{
    AddAlert("Nu a putut fi ștearsă postarea cerută.", "danger");
    header("location: ../index.php?user=".$_SESSION['username']);
    die();
}

// Success
AddAlert("Postarea dumneavoastră a fost ștearsă.", "success");
header("location: ../index.php?user=".$_SESSION['username']);
die();


?>