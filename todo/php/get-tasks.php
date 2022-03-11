<?php
session_start();

// functions and db connection
include_once "../modules/mod-functions.php";
include_once "../modules/db-config.php";


// post variables out of the ajax call
if(isset($_POST['php_row_number']))
    $row_number = $_POST['php_row_number'];

if(isset($_POST['php_tasksperload']))
    $tasksperload = $_POST['php_tasksperload'];

if(isset($_POST['php_pagetype']))
    $page_type = $_POST['php_pagetype'];

if(isset($_POST['php_requesteduserid']))
    $profile_id = $_POST['php_requesteduserid'];

if(isset($_POST['php_f_state']))
    $f_state = $_POST['php_f_state'];

if(isset($_POST['php_visibility']))
    $visibility = $_POST['php_visibility'];

// session variables
$currentuserid = $_SESSION['userid'];
$currentuesrname = $_SESSION['username'];
$loggedin = LoggedIn();


// constructing the select string
$selectstring = "order_id desc limit";


if($page_type == "index")
    $selectstring .= " all";
else if ($page_type == "user")
    $selectstring .= " of_user";
    
if($f_state == "todo")
{
    $selectstring .= " todo";
}

$selectstring .= " ".$visibility;

// get tasks
if($page_type == "index")
{
    $result = SELECT_GetTasksGlobal($selectstring, $currentuserid, "", "", $row_number, $tasksperload);
}
else if($page_type == "user")
{
    $result = SELECT_GetTasksGlobal($selectstring, $currentuserid, $profile_id, "", $row_number, $tasksperload);
}

// is user shown?
$showUser = true;
if($page_type == "user")
    $showUser = false;


// echo show tasks
foreach($result as $task)
{
    ShowTask($task, $showUser);
}

// if result empty, output ARR_EMPTY for 'error' handle
if(empty($result))
    echo "ARR_EMPTY";

// exit script
exit();

?>