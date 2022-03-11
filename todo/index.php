<?php

session_start();


// Database Connection module and functions
require 'modules/db-config.php';
include_once 'modules/mod-functions.php';



// Page variables
$page_type = "index";
$current_page_title = "Acasă";
$taskShowUser = true;
$f_state = "";

// Set session variables

$currentuserid = LoggedIn();
$requested_user = false;


// If page requested is userpage:
if(isset($_GET['user']))
{
    //fail if user not logged in, redirect to login page
    if(!LoggedIn())
    {
        AddAlert("Conectează-te pentru a vedea postările acestui utilizator!", "warning");
        header("location: php/login.php");
        die();
    }

    // set page variables
    $page_type = "user";
    $current_page_title = trim($_GET['user']);
    $taskShowUser = false;

    // get requested user by get
    $requested_user = trim($_GET['user']);
    $requested_user = SELECT_GetUserBy('username', 's', $requested_user);

    // if user is not found or another error, return to index page
    if($requested_user == false)
    {
       $page_type = "index";
       $current_page_title = "Acasă";
    }
}

// Set number of tasks to load per ajax call
$tasksperload = 10;
if(isset($_GET['tpp']))
    $tasksperload = $_GET['tpp'];


// Get filter variables by get
if(isset($_GET['f_state']))
{
    $f_state = $_GET['f_state'];
    //$_SESSION['f_state'] = $f_state;
}

// Construct task query selection string

// at the moment, always ordered by id and limited
$selectstring = "order_id desc limit";

// Visibility settings

// if index page
if($page_type == "index")
{
    // and logged in, show all "registered" privacy posts
    if(LoggedIn())
    {
        $selectstring.= " visibility_loggedin";
        $ajax_visibility = "visibility_loggedin";
    }

    // otherwise show all "public" privacy posts
    else
    {
        $selectstring .= " visibility_public";
        $ajax_visibility = "visibility_public";
    }
}

// if user page
if($page_type == "user")
{
    // if is profile of logged in user, show all posts
    if(LoggedIn() && isUser($requested_user['username']))
    {
        $selectstring .= " visibility_all";
        $ajax_visibility = "visibility_all";
    }

    // otherwise only show "reqgistered" privacy posts
    else
    {
        $selectstring .= " visibility_loggedin";
        $ajax_visibility = "visibliity_loggedin";
    }
}

// if index page, show all posts, if user page show only posts of user
if($page_type == "index")
    $selectstring .= " all";
else if ($page_type == "user")
    $selectstring .= " of_user";
    
// if state todo is set, only show posts that are "todo"
if($f_state == "todo")
{
    $selectstring .= " todo";
}

//echo $selectstring;
?>


<!DOCTYPE html>
<html lang="en">
<head>
<?php require "modules/mod-head.php"; ?>

</head>
<body>

<?php include "modules/mod-navbar.php"; ?>

<!-- Greeting container -->
<div class = "container">
<?php

    //if index page and user is logged in, greet and show task add module
    if($page_type == "index" && LoggedIn()) {
        ?><div class = "container"><h1 class = "display-1 text-center"> Salut, <?=htmlspecialchars($_SESSION["username"])?>! </h1></div>

    <?php
    include "modules/mod-create-task.php"; 
    }

    // if user page, show name of user
    if($page_type == "user")
    {
    ?><h2 class = "display-2"> Postările <?=($requested_user['username'] == $_SESSION['username'] ? "tale" : "lui ".$requested_user['username'] ) ?> </h2><?php
    }

?>

</div>
<div class = "container" id = "task-output-content">


<!--Filter buttons for posts, based on finalization at the moment -->
<?php if($f_state != "todo")
{ ?>
<a class = "btn btn-outline-primary d-block my-2" role = "button" href = "./?f_state=todo<?=($requested_user?"&user=".$requested_user['username']:"")?>"> Arată doar nefinalizate </a>
<?php }
?>

<?php if($f_state != "")
{ ?>
<a class = "btn btn-outline-primary d-block my-2" role = "button" href = "./<?=($requested_user?"?user=".$requested_user['username']:"")?>"> Arată toate </a>
<?php }
?>


<?php
    // if index page
    if($page_type == "index")
    {
        $page_tasks = SELECT_GetTasksGlobal($selectstring, $currentuserid, "", "", 0, $tasksperload);
    }

    // if user page
    else if($page_type == "user")
    {  
        $page_tasks = SELECT_GetTasksGlobal($selectstring, $currentuserid, $requested_user['id'], "", 0, $tasksperload);
    }

    // output collected tasks
    foreach($page_tasks as $task)
        ShowTask($task, $taskShowUser);
?>

</div>

<!-- loadmore button in case scrollign doesn't work -->
<div class = "container my-5" id = "container-btn-loadmore">
    <button id = "btn-loadmore" class = "btn btn-outline-primary d-block mx-auto text-center"> Mai multe.. </button>
</div>


<!-- input for ajax numbering -->
<input type = "hidden" id = "data-row-number" value = "<?=$tasksperload?>">
<input type = "hidden" id = "data-tasksperload" value = "<?=$tasksperload?>"> 

<!-- input for ajax filtering -->
<input type = "hidden" id = "data-pagetype" value = "<?=$page_type?>">
<input type = "hidden" id = "data-userid" value = "<?=($requested_user['id']?$requested_user['id']:0)?>">
<input type = "hidden" id = "data-f_state" value = "<?=$f_state?>">
<input type = "hidden" id = "data-visibility" value = "<?=$ajax_visibility?>">


<!-- scripts -->
<?php require "modules/mod-scripts.php"; ?>
<script type = "text/javascript" src = "js/load-tasks-ajax.js"></script>
<script type = "text/javascript" src = "js/like-post-ajax.js"></script>
</body>
</html>