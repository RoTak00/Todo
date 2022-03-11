<?php


function INSERT_Task($title, $description, $type, $visibility, $userid)
{
    $sqlq = "INSERT INTO tasktable (title, description, type, visibility, user) VALUES (?, ?, ?, ?, ?)";
    global $conn;

    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("ssssi", $title, $description, $type, $visibility, $userid);
        if($stmt->execute())
        {
            return $conn->insert_id;
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

function INSERT_LikeTask($taskid, $userid)
    {
        
        $sqlq = "INSERT INTO user_post_likes(userid, taskid)
        VALUES (?, ?)";

        global $conn;
    
        if($stmt = $conn->prepare($sqlq))
        {
            $stmt->bind_param("ii", $userid, $taskid);
            if($stmt->execute())
            {
                return true;
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