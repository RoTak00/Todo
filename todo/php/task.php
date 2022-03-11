
<?php
session_start();

include_once '../modules/mod-functions.php';  

if(!LoggedIn())
{
    AddAlert("Conectează-te pentru a putea vedea detaliile acestei postări!", "warning");
    header("location: login.php");
    exit;
}

//display specific page variables
$current_page = "task";
$current_page_title = "Postare";

?>

<?php include_once '../modules/db-config.php';  ?>

<?php
    if(!isset($_GET['task'])){
        AddAlert("Nu se poate căuta postarea cerută", "danger");
        header("location: ../index.php");
        die();
    }

    //var_dump($_POST);
    $taskId = $_GET['task'];

    $currentuserid = LoggedIn();

    $requestedTask = SELECT_GetTasksGlobal("of_id", $currentuserid, "", $taskId)[0];
    if($requestedTask == false)
    {
        AddAlert("Nu se poate încărca postarea cerută", "danger");
        header("location: ../index.php");
        die();
    }

    if($requestedTask['state'] == 'deleted' && !isUser($requestedTask['username']))
    {
        AddAlert("Postarea pe care vrei să o accesezi a fost ștearsă", "warning");
        header("location: ../index.php");
        die();
    }

    if($requestedTask['visibility'] == 'private' && !isUser($requestedTask['username']))
    {
        AddAlert("Postarea pe care vrei să o accesezi este privată", "danger");
        header("location: ../index.php");
        die();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>

<?php include "../modules/mod-head.php"; ?>

</head>
<body>
 
<?php include "../modules/mod-navbar.php"; ?>

<div class = "container mt-5">
<?php  ShowTaskFull($requestedTask); ?>
</div>
<div class = "container mt-5">
<?php  ShowTaskOptions($requestedTask); ?>
</div>



<?php include "../modules/mod-scripts.php"; ?>
<script src = "../js/task-action-confirmation.js"></script>
</body>
</html>