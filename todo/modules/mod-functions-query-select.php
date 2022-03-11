<?php
 function SELECT_GetTaskPoints($id)
 {
     $sqlq = "SELECT COUNT(taskid)
     AS nlikes
     FROM user_post_likes
     WHERE taskid = ?";

     global $conn;
     
     if($stmt = $conn->prepare($sqlq))
     {
         $stmt->bind_param("i", $id);
         if($stmt->execute())
         {
             $stmt->store_result();
             $stmt->bind_result($val);
             $stmt->fetch();
             return $val;
         }
         else
         {
             if(Admin())
                 AddAlert($conn->error, "danger");
             return false;
         }
     }
     else
     {
         if(Admin())
                 AddAlert($conn->error, "danger");
         return false;
     }
 }

 function SELECT_IsTaskedLikedByUser($userid, $taskid)
 {
     if(!LoggedIn())
         return false;
     
     $sqlq = "SELECT id
     FROM user_post_likes
     WHERE taskid = ? AND userid = ?";

     global $conn;
     if($stmt = $conn->prepare($sqlq))
     {
         $stmt->bind_param("ii", $taskid, $userid);
         if($stmt->execute())
         {
             //echo " okliked";
             $stmt->store_result();
             if($stmt->num_rows == 1)
                 return true;
             return false;
         }
         else
         {
             if(Admin())
                 AddAlert($conn->error, "danger");
             return false;
         }
     }
     else
     {
         if(Admin())
                 AddAlert($conn->error, "danger");
         return false;
     }

 }

// GET TASK
function SELECT_GetTasksGlobal($type = "", $currentuserid = "", $user = "", $taskid = "", $start = "", $limit = "")
 {
     $sqlq = SELECT_GetTaskQueryString($type);
     //echo $sqlq."<br>";
     $rez = [];
     global $conn;
 
     if($stmt = $conn->prepare($sqlq))
     {
         SELECT_TaskBindParam($stmt, $type, $user, $taskid, $start, $limit);

         if($stmt->execute())
         {
             $stmt->store_result();
             if($stmt->num_rows > 0)
             {
                 
                 $stmt->bind_result($id, $title, $description, $added_date, $type, $state, $visibility, $username);
                 while($stmt->fetch())
                 {
                     $points = SELECT_GetTaskPoints($id);
                     $likedbyuser = SELECT_IsTaskedLikedByUser($currentuserid, $id);
                     $added_date = strtotime($added_date) + 60 * 60;
                     
                     $rez[] = [
                             'taskid' => $id,
                             'title' => $title,
                             'description' => $description,
                             'added_date' => $added_date,
                             'username' => $username,
                             'points' => $points,
                             'likedbyuser' => $likedbyuser,
                             'type' => $type,
                             'state' => $state,
                             'visibility' => $visibility
                     ];
                 }
             
             }
             return $rez;
         }
         else
         {
             if(Admin())
                 AddAlert($conn->error, "danger");
             return false;
         }
     }
     else
     {
         if(Admin())
                 AddAlert($conn->error, "danger");
         return false;
     }

 }

 function SELECT_GetTaskById($id)
 {
     $sqlq = "SELECT tasktable.id, tasktable.title, tasktable.description, tasktable.added_date, tasktable.type, tasktable.state, tasktable.visibility, usertable.username
     FROM tasktable
     INNER JOIN usertable ON tasktable.user = usertable.id
     WHERE tasktable.id = ?";
    //echo $sqlq."<br>";
     $rez = [];
     global $conn;
 
    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("i", $id);
        if($stmt->execute())
        {
            $stmt->store_result();
            if($stmt->num_rows == 1)
            {
                
                $stmt->bind_result($id, $title, $description, $added_date, $type, $state, $visibility, $username);
                if($stmt->fetch())
                {
                    $points = SELECT_GetTaskPoints($id);
                    $likedbyuser = SELECT_IsTaskedLikedByLoggedIn($id);
                    $added_date = strtotime($added_date) + 60 * 60;
                    $rez = [
                        'taskid' => $id,
                        'title' => $title,
                        'description' => $description,
                        'added_date' => $added_date,
                        'username' => $username,
                        'points' => $points,
                        'likedbyuser' => $likedbyuser,
                        'type' => $type,
                        'state' => $state,
                        'visibility' => $visibility
                    ];
                }
            
            }
            return $rez;
        }
        else
        {
            if(Admin())
                    AddAlert($conn->error, "danger");
            return false;
        }
    }
    else
    {
        if(Admin())
                    AddAlert($conn->error, "danger");
        return false;
    }


 }

 /*function SELECT_GetTasks($start, $limit)
 {
     $sqlq = "SELECT tasktable.id, tasktable.title, tasktable.description, tasktable.added_date, tasktable.type, tasktable.state, usertable.username
     FROM tasktable
     INNER JOIN usertable ON tasktable.user = usertable.id
     ORDER BY tasktable.id DESC
     LIMIT ?, ?";

     $rez = [];
     global $conn;
 
     if($stmt = $conn->prepare($sqlq))
     {
         $stmt->bind_param("ii", $start, $limit);
         if($stmt->execute())
         {
             $stmt->store_result();
             if($stmt->num_rows > 0)
             {
                 
                 $stmt->bind_result($id, $title, $description, $added_date, $type, $state, $username);
                 while($stmt->fetch())
                 {
                     $points = SELECT_GetTaskPoints($id);
                     $likedbyuser = SELECT_IsTaskedLikedByLoggedIn($id);
                     $added_date = strtotime($added_date) + 60 * 60;
                     
                     $rez[] = [
                             'taskid' => $id,
                             'title' => $title,
                             'description' => $description,
                             'added_date' => $added_date,
                             'username' => $username,
                             'points' => $points,
                             'likedbyuser' => $likedbyuser,
                             'type' => $type,
                             'state' => $state
                     ];
                 }
             
             }
             return $rez;
         }
         else
         {
             if(Admin())
                 AddAlert($conn->error, "danger");
             return false;
         }
     }
     else
     {
         if(Admin())
                 AddAlert($conn->error, "danger");
         return false;
     }

 }

 function SELECT_GetTasksByUser($userid, $start, $limit)
 {
     $sqlq = "SELECT tasktable.id, tasktable.title, tasktable.description, tasktable.added_date, tasktable.type, tasktable.state, usertable.username
     FROM tasktable
     INNER JOIN usertable ON tasktable.user = usertable.id
     WHERE tasktable.user = ?
     ORDER BY tasktable.id DESC
     LIMIT ?, ?";

     $rez = [];
     global $conn;
 
 if($stmt = $conn->prepare($sqlq))
 {
     $stmt->bind_param("iii", $userid, $start, $limit);
     if($stmt->execute())
     {
         $stmt->store_result();
         if($stmt->num_rows > 0)
         {
             
             $stmt->bind_result($id, $title, $description, $added_date, $type, $state, $username);
             while($stmt->fetch())
             {
                 $points = SELECT_GetTaskPoints($id);
                 $likedbyuser = SELECT_IsTaskedLikedByLoggedIn($id);
                 $added_date = strtotime($added_date) + 60 * 60;
                $rez[] = [
                     'taskid' => $id,
                     'title' => $title,
                     'description' => $description,
                     'added_date' => $added_date,
                     'username' => $username,
                     'points' => $points,
                     'likedbyuser' => $likedbyuser,
                     'type' => $type,
                     'state' => $state
                ];
             }
             
         }
         return $rez;
     }
     else
     {
         if(Admin())
                 AddAlert($conn->error, "danger");
         return false;
     }
 }
 else
 {
     if(Admin())
                 AddAlert($conn->error, "danger");
     return false;
 }


 }
*/
 

 function SELECT_IsLikedByUser($taskid, $userid)
    {
        $sqlq = "SELECT id
        FROM user_post_likes
        WHERE userid = ? AND taskid = ?";

        global $conn;
            
        if($stmt = $conn->prepare($sqlq))
        {
            $stmt->bind_param("ii", $userid, $taskid);
            if($stmt->execute())
            {
                $stmt->store_result();
                if($stmt->num_rows >= 1)
                    return "liked";
                else
                    return "not_liked";
            }
            else
            {
                if(Admin())
                    AddAlert($conn->error, "danger");
                return false;
            }
        }
        else
        {
            if(Admin())
                    AddAlert($conn->error, "danger");
            return false;
        }
    }

function SELECT_GetUserIdByUsername($username)
{
    $sqlq = "SELECT id FROM usertable WHERE username = ?";
    global $conn;

    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("s", $username);
        if($stmt->execute())
        {
            $stmt->store_result();

            if($stmt->num_rows == 1)
            {
                $stmt->bind_result($rez);
                if(!$stmt->fetch())
                {
                    if(Admin())
                        AddAlert($conn->error, "danger");
                    return false;
                }
                return $rez;
            }
            else
            {
                if(Admin())
                    AddAlert("Nu exista utilizatorul cerut", "danger");
                return false;
            }
        }
        else
        {
            if(Admin())
                AddAlert($conn->error, "danger");
            return false;
        }
    }
    else
    {
        if(Admin())
            AddAlert($conn->error, "danger");
        return false;
    }


}

function SELECT_GetUserBy($queryel, $querytype, $value)
{
    $sqlq = "SELECT ID, username, email, points, is_admin, created_at FROM usertable WHERE ".$queryel." = ?";
    global $conn;
    //echo $sqlq;

    if(!($stmt = $conn->prepare($sqlq)))
    {
        if(Admin())
            AddAlert($conn->error, "danger");
        return false;
    }
    
    $stmt->bind_param($querytype, $value);
    if(!($stmt->execute()))
    {
        if(Admin())
            AddAlert($conn->error, "danger");
        return false;
    }
    
    $stmt->store_result();

    if(!($stmt->num_rows == 1))
    {
        
        AddAlert("Nu exista utilizatorul cerut", "danger");
        return false;
    }


    $id = $username = $email = $points = $is_admin = $created_at = "";
    $stmt->bind_result($id, $username, $email, $points, $is_admin, $created_at);

    if(!$stmt->fetch())
    {
        if(Admin())
            AddAlert($conn->error, "danger");
        return false;
    }
    $rez = [
        'id'=>$id,
        'username'=>$username,
        '$email'=>$points,
        '$points'=>$is_admin,
        '$created_at'=>$created_at
    ];
    return $rez;


}

function SELECT_GetTaskLikes($taskid)
{
    $sqlq = "SELECT usertable.id, usertable.username
    FROM user_post_likes
    INNER JOIN usertable ON user_post_likes.userid = usertable.id
    WHERE user_post_likes.taskid = ?
    ORDER BY user_post_likes.added_date ASC";

    $rez = [];
    global $conn;

    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("i", $taskid);
        if($stmt->execute())
        {
            $stmt->store_result();
            if($stmt->num_rows > 0)
            {
                
                $stmt->bind_result($userid, $username);
                while($stmt->fetch())
                {
                   $rez[] = [
                        'userid' => $userid,
                        'username' => $username
                   ];
                }
                
            }
            return $rez;
        }
        else
        {
            if(Admin())
                AddAlert($conn->error, "danger");
            return false;
        }
    }
    else
    {
        if(Admin())
            AddAlert($conn->error, "danger");
        return false;
    }

}


 ?>