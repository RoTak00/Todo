<?php

 
function LikeUnlikeTask($taskid, $userid)
{
    $liked = SELECT_IsLikedByUser($taskid, $userid);
    //var_dump($liked);
    $rez = true;
    if($liked == "liked")
        $rez = DELETE_UnlikeTask($taskid, $userid);
    else if($liked == "not_liked")
        $rez = INSERT_LikeTask($taskid, $userid);

    return $rez;
    
}

function SELECT_GetTaskQueryString($type)
{
    $sqlq = "SELECT tasktable.id, tasktable.title, tasktable.description, tasktable.added_date, tasktable.type, tasktable.state, tasktable.visibility, usertable.username
    FROM tasktable
    INNER JOIN usertable ON tasktable.user = usertable.id";

    $param_array = explode(" ", $type);

    // condition
    $c1 = false;
    if(in_array("all", $param_array))
        $sqlq .= "";
    else if (in_array("of_user", $param_array))
    {
        $sqlq .= " WHERE tasktable.user = ?";
        $c1 = true;
    }
    else if (in_array("of_id", $param_array))
    {
        $sqlq .= " WHERE tasktable.id = ?";
        $c1 = true;
    }
    else
        $sqlq .= "";
    

    if(in_array("todo", $param_array))
    {
        $sqlq .= ($c1 ? " AND" : " WHERE").' tasktable.state = "todo"';
        $c1 = true;
    }
    else
        $sqlq .= "";

    if(in_array("visibility_all", $param_array))
    {
        $sqlq .= "";
    }
    else if(in_array("visibility_loggedin", $param_array))
    {
        $sqlq .= ($c1 ? " AND" : " WHERE").' tasktable.visibility != "private" AND tasktable.state != "deleted"';
        $c1 = true;
    }
    else if(in_array("visibility_public", $param_array))
    {
        $sqlq .= ($c1 ? " AND" : " WHERE").' tasktable.visibility = "all" AND tasktable.state != "deleted"';
        $c1 = true;
    }
    // ordering

    if(in_array("order_id", $param_array))
        $sqlq .= " ORDER BY tasktable.id";
    else
        $sqlq .= "";

    // ordering 2

    if(in_array("desc", $param_array))
        $sqlq .= " DESC";
    else if(in_array("asc", $param_array))
        $sqlq .= " ASC";
    else
        $sqlq .= "";
    
    // limit

    if(in_array("limit", $param_array))
        $sqlq .= " LIMIT ?, ?";
    else
        $sqlq .= "";
    //echo $sqlq;
    return $sqlq;
}

function SELECT_TaskBindParam(&$stmt, $type, $user, $taskid, $start, $limit)
{
    $param_array = explode(" ", $type);
    //var_dump($param_array);
    $bind_array = [];
    $bind_string = "";

    if(in_array("of_user", $param_array))
    {
        //echo "user";
        $bind_array []= $user;
        $bind_string .= "i";
    }
    else if(in_array("of_id", $param_array))
    {
        //echo "id";
        $bind_array []= $taskid;
        $bind_string .= "i";
    }

    if(in_array("limit", $param_array))
    {
        //echo "limit";
        $bind_array []= $start;
        $bind_array []= $limit;
        $bind_string .= "ii";
    }
    //var_dump($bind_string);
    //var_dump($bind_array);

    $stmt->bind_param($bind_string, ...$bind_array);
}

?>