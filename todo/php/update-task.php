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
    if(!isset($_GET['state']))
    {
        AddAlert("Eroare la accesarea paginii.", "danger");
        header("location: ../index.php");
        die();
    }

    $taskid = intval(trim($_GET['id']));
    $newstate = $_GET['state'];

    if(!in_array($newstate, $TASK_STATES))
    {
        AddAlert("Starea nouă nu este conformă.", "danger");
        header("location: ../index.php");
        die();
    }

    //var_dump($requested_task);
    //var_dump($taskid);*/
    $requested_task = SELECT_GetTasksGlobal("of_id", $SESSION['userid'], "", $taskid, "", "");
    $requested_task = $requested_task[0];
    
    //var_dump($requested_task);
    

    if(isUser($requested_task['username']))
    {
        if(!UPDATE_TaskById($taskid, "state", $newstate))
        {
            AddAlert("Nu a putut fi modificată postarea cerută.", "danger");
        }
        else
        {
            AddAlert("Postarea dumneavoastră a fost modificată.", "success");
        }
        $previous = "../index.html";
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
        header("location: ".$previous);
        die();
    }
    else
    {
        
        AddAlert("Nu poți modifica postarea altui utilizator", "danger");
        header("location: ../index.php");
        die();
    }

    


?>