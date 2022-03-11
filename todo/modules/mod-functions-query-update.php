<?php

    function UPDATE_TaskById($taskid, $field, $data)
    {
        $sqlq = "UPDATE tasktable
        SET ".$field." = ?
        WHERE id = ?";

        global $conn;

        if($stmt = $conn->prepare($sqlq))
        {
            $stmt->bind_param("si", $data, $taskid);
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