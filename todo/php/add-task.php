<?php
session_start();

// Database Connection module and functions
include_once '../modules/db-config.php'; 
include_once "../modules/mod-functions.php";


// If not logged in, cannot add task
if(!LoggedIn())
{
    AddAlert("Nu sunteți conectat.", "danger");
    header("location: /php/login.php");
    die();
}


$taskTitle = $taskDescription = "";

// Check required variables set in post
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(!isset($_POST['taskTitle'])){
        AddAlert("Nu ai adăugat un titlu", "danger");
        header("location: /index.php");
        die();
    }

    if(!isset($_POST['taskType'])){
        AddAlert("Nu ai adăugat un tip", "danger");
        header("location: /index.php");
        die();
    }

    if(!isset($_POST['taskVisibility']))
    {
        AddAlert("Nu ai adăugat vizibilitate", "danger");
        header("location: /index.php");
        die();
    }
}

// Grab form variables 
$taskTitle = trim($_POST['taskTitle']);
$taskType = trim($_POST['taskType']);
$taskVisibility = trim($_POST['taskVisibility']);
if(isset($_POST['taskDescription'])) $taskDescription = $_POST['taskDescription'];

// Grab form hidden variables
if(isset($_POST['prevpage_f_state'])) $prevpage_f_state = $_POST['prevpage_f_state'];

// check required vars not empty
if($taskTitle === "")
{
    AddAlert("Nu ai adăugat un titlu", "danger");
    header("location: /index.php");
    die();
}
if($taskType === "")
{
    AddAlert("Nu ai adăugat un tip", "danger");
    header("location: /index.php");
    die();
}
if($taskVisibility === "")
{
    AddAlert("Nu ai adăugat vizibilitate", "danger");
    header("location: /index.php");
    die();
}
    

// check task title dimension ok
if(strlen($taskTitle) < 3)
{
    AddAlert("Oferă un nume mai descriptiv task-ului tău, de minim 3 litere!", "danger");
    header("location: /index.php");
    die();
 }
if(strlen($taskTitle) > 50)
{
    AddAlert("Numele task-ului nu poate depăși 50 de litere!", "danger");
    header("location: /index.php");
    die();
}

//check task type is ok
if(!in_array($taskType, $TASK_TYPES))
{
    AddAlert("Tipul task-ului nu este conform.", "danger");
    header("location: /index.php");
    die();
}

//check task visibility is ok
if(!in_array($taskVisibility, $TASK_VISIBILITIES))
{
    AddAlert("Vizibilitatea task-ului nu este conform.", "danger");
    header("location: /index.php");
    die();
}
    

//data ok, send to database
if(!(INSERT_Task($taskTitle, $taskDescription, $taskType, $taskVisibility, $_SESSION['userid'])))
{
    AddAlert("A apărut o eroare, te rog încearcă din nou mai târziu!", "danger");
    header("location: /index.php");
    die();
}

// data successfully sent, return to index
AddAlert("Postarea a fost adăugată cu succes!", "success");


// construct return uri
$returnURI = "/index.php";
$querystringchar = "?";

if(isset($prevpage_f_state))
{
    $returnURI .= $querystringchar."f_state=".$prevpage_f_state;
    $querystringchar = "&";
}
header("location: ".$returnURI);
die();

?>