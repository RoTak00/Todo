<?php
session_start();

include_once "../modules/mod-functions.php";
require_once "../modules/db-config.php";

$return_arr = [];

if(!isset($_POST['php_taskid']))
{
    $return_arr = array("error" => "not_set");
}
else if(!LoggedIn())
{
    $return_arr = array("error" => "not_connected");
}
else
{
    $taskid = $_POST['php_taskid'];
    $userid = $_SESSION['userid'];

    $isliked = SELECT_IsLikedByUser($taskid, $userid);
    $currlikes = SELECT_GetTaskPoints($taskid);

    if($isliked == "liked")
    {
        if(!DELETE_UnlikeTask($taskid, $userid))
            $return_arr = array("error" => "db_error");
        else
        {
            $currlikes -= 1;
            $status = "not_liked";
            $return_arr = array("error" => "", "likes" => $currlikes, "status" => $status);
        }
    }
    else if($isliked == "not_liked")
    {
        if(!INSERT_LikeTask($taskid, $userid))
            $return_arr = array("error" => "db_error");
        else
        {
            $currlikes += 1;
            $status = "liked";
            $return_arr = array("error" => "", "likes" => $currlikes, "status" => $status);
        }
    }
}

echo json_encode($return_arr);

?>