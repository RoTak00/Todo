<?php 
session_start();
include_once '../modules/mod-functions.php';

if(isset($_GET['t']))
    $taskid = $_GET['t'];
else
{
    AddAlert("Eroare.", "danger");
    header("location: ../index.php");
    die();
    
}


$requested_task = SELECT_GetTasksGlobal("of_id", $_SESSION['userid'], "", $taskid, "", "");
if($requested_task == false)
{
    AddAlert("Eroare.", "danger");
    header("location: ../index.php");
    die();
}
$requested_task = $requested_task[0];


if(isset($_GET['action']))
    $action = $_GET['action'];
else
{
    AddAlert("Eroare.", "danger");
    header("location: ../index.php");
    die();
    
}
//echo "ok";



if(!isUser($requested_task['username']))
{
    AddAlert("Nu poți modifica task-ul altui utilizator", "danger");
    header("location: ../index.php");
    die(); 

}
//echo $action;

if(in_array($action, ['setdone', 'settodo', 'setaborted', 'setdeleted']))
{
    $newstate = [];
    if($action == 'setdone') $newstate = 'done';
    if($action == 'settodo') $newstate = 'todo';
    if($action == 'setaborted') $newstate = 'aborted';
    if($action == 'setdeleted') $newstate = 'deleted';

    if(UPDATE_TaskById($taskid, "state", $newstate))
    {
        AddAlert("Acțiune efectuată cu succes.", "success");
    }
    else
    {
        AddAlert("Nu a putut fi efectuată acțiunea.", "danger");
    }
    
    header("location: task.php?task=".$taskid);
    die();
}

if($action == "deletedescription")
{
    if(UPDATE_TaskById($taskid, "description", "" ))
    {
        AddAlert("Acțiune efectuată cu succes.", "success");
    }
    else
    {
        AddAlert("Nu a putut fi efectuată acțiunea.", "danger");
    }
    header("location: task.php?task=".$taskid);
    die();
}


if(isset($_POST['data']))
    $data = trim($_POST['data']);
else
{
    AddAlert("A apărut o eroare", "danger");
    header("location: ../index.php");
    die(); 
}

if($action == "title")
{
    if($data === "")
    {
        AddAlert("Nu ai adăugat un titlu", "danger");
        header("location: task.php?task=".$taskid);
        die();
    }
    // check title dim ok
    if(strlen($data) < 3)
    {
        AddAlert("Oferă un nume mai descriptiv task-ului tău, de minim 3 litere!", "danger");
        header("location: task.php?task=".$taskid);
        die();
    }
    if(strlen($data) > 50)
    {
        AddAlert("Numele task-ului nu poate depăși 50 de litere!", "danger");
        header("location: task.php?task=".$taskid);
        die();
    }

    if(UPDATE_TaskById($taskid, "title", $data))
    {
        AddAlert("Acțiune efectuată cu succes.", "success");
    }
    else
    {
        AddAlert("Nu a putut fi efectuată acțiunea.", "danger");
    }
    header("location: task.php?task=".$taskid);
    die();
}

if($action == "description")
{
    if($data === "")
    {
        AddAlert("Nu ai adăugat o descriere", "danger");
        header("location: task.php?task=".$taskid);
        die();
    }

    if(UPDATE_TaskById($taskid, "description", $data))
    {
        AddAlert("Acțiune efectuată cu succes.", "success");
    }
    else
    {
        AddAlert("Nu a putut fi efectuată acțiunea.", "danger");
    }
    header("location: task.php?task=".$taskid);
    die();
}

if($action == "type")
{
    if($data === "")
    {
        AddAlert("Nu ai adăugat un tip", "danger");
        header("location: task.php?task=".$taskid);
        die();
    }

    if(!in_array($data, $TASK_TYPES))
    {
        AddAlert("Tipul task-ului nu este conform.", "danger");
        header("location: /index.php");
        die();
    }

    if(UPDATE_TaskById($taskid, "type", $data))
    {
        AddAlert("Acțiune efectuată cu succes.", "success");
    }
    else
    {
        AddAlert("Nu a putut fi efectuată acțiunea.", "danger");
    }
    header("location: task.php?task=".$taskid);
    die();
}

if($action == "visibility")
{
    if($data === "")
    {
        AddAlert("Nu ai adăugat vizibilitate", "danger");
        header("location: task.php?task=".$taskid);
        die();
    }

    if(!in_array($data, $TASK_VISIBILITIES))
    {
        AddAlert("Vizibilitatea task-ului nu este conform.", "danger");
        header("location: /index.php");
        die();
    }

    if(UPDATE_TaskById($taskid, "visibility", $data))
    {
        AddAlert("Acțiune efectuată cu succes.", "success");
    }
    else
    {
        AddAlert("Nu a putut fi efectuată acțiunea.", "danger");
    }
    header("location: task.php?task=".$taskid);
    die();
}

?>



