<?php
function DELETE_TaskById($id)
{
    $sqlq = "DELETE
    FROM tasktable
    WHERE id = ?";

    global $conn;

    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("i", $id);
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

function DELETE_UnlikeTask($taskid, $userid)
    {
        
        $sqlq = "DELETE FROM user_post_likes
        WHERE userid = ? AND taskid = ?";

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