<?php

function TaskTypeToOutput($val)
    {
        $rez = [
            "val" => "Nedefinit",
            "style" => "danger"
        ];
        if($val === "day")
        $rez = [
            "val" => "Azi",
            "style" => "text-primary"
        ];
        if($val === "week")
        $rez = [
            "val" => "Termen Scurt",
            "style" => "text-secondary"
        ];
        if($val === "month")
        $rez = [
            "val" => "Termen Lung",
            "style" => "text-black"
        ];
        return $rez;
    }

    function TaskStateToOutput($task, $loggedin, $isPostUser)
    {
        $rez = $icon = $link = $linkend = "";
        if($task['state'] == "todo")
        {
            $icon = '<i class="far fa-circle" style = "color: #000;" title = "De făcut"></i>';
            if($loggedin && $isPostUser)
            {
                $link = '<a href = "/php/update-task.php?id='.$task['taskid'].'&state='.'done'.'">';
                $linkend = '</a>';
            }
        }
        if($task['state'] == "done")
        {
            $icon = '<i class="far fa-check-circle" style = "color: #00FF00;" title = "Finalizat"></i>';
            
        }
        if($task['state'] == "aborted")
        {
            $icon = '<i class="far fa-times-circle" style = "color: #FF0000;" title = "Anulat"></i>';
        }
        if($task['state'] == "deleted")
        {
            $icon = '<i class="fas fa-minus-circle" style = "color: #000;" title = "Șters"></i>';
        }

        $rez = $link.$icon.$linkend;
        return $rez;
    }

    function TaskStateToOutputTaskPage($state)
    {
        $rez = [
            'text' => 'De făcut',
            'color' => '#f2f0df'
        ];

        if($state == 'done')
        {
            $rez['text'] = "Finalizată!";
            $rez['color'] = "#daeddd";
        }

        if($state == 'aborted')
        {
            $rez['text'] = "Anulată!";
            $rez['color'] = "#cca7b1";
        }

        if($state == 'deleted')
        {
            $rez['text'] = "Ștearsă!";
            $rez['color'] = "#8a8788";
        }

        return $rez;
    }

    function TaskDateToOutput($added_date)
    {
        
        $interval = time() - $added_date;
        $rez = "";

        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;
        $month = $week * 30;
        $year = $month * 12;

        if($interval < $minute)
        {
            $rez = "chiar acum";
        }
        else if ($interval <  2 * $minute)
        {
            $rez = "acum 1 minut";
        }
        else if($interval < $hour)
        {
            $rez = "acum ".floor($interval / $minute)." ".(floor($interval / $minute) < 20 ? "" : "de ")."minute";
        }
        else if($interval < 2 * $hour)
        {
            $rez = "acum o oră";
        }
        else if($interval < $day)
        {
            $rez = "acum ".floor($interval / $hour)." ore";
        }
        else if($interval < 2 * $day)
        {
            $rez = "acum o zi";
        }
        else if($interval < $week)
        {
            $rez = "acum ".floor($interval / $day). " zile";
        }
        else if($interval < 2 * $week)
        {
            $rez = "acum o săptămână";
        }
        else if($interval < $month)
        {
            $rez = "acum ".floor($interval / $week). " săptămâni";
        }
        else if($interval < 2 * $month)
        {
            $rez = "acum o lună";
        }
        else if($interval < $year)
        {
            $rez = "acum ".floor($interval / $month). " luni";
        }
        else if($interval < 2 * $year)
        {
            $rez = "acum un an";
        }
        else
        {
            $rez = "acum ".floor($interval / $year)." ani";
        }
        
        return $rez;
    }

    function TaskVisibilityToOutput($visibility)
    {
        $rez = "public";
        if($visibility == "registered")
            $rez = "utilizatorilor înregistrați";
        if($visibility == "private")
            $rez = "doar ție";
        
        return $rez;
    }

    function TaskVisibilityToIndexOutput($visibility)
    {
        $rez = "Postare publică";
        if($visibility == "registered")
            $rez = "";
        if($visibility == "private")
            $rez = "Postare privată";
        
        return $rez;
    }

    function ShowTaskLikes($taskid)
    {
        $likes = SELECT_GetTaskLikes($taskid);

        if($likes === [])
        {
            ?> Postarea nu are încă aprecieri! </p><?php
        }
        if($likes === false)
        {
            AddAlert("A apărut o eroare, te rugăm să încerci din nou mai târziu.", "danger");
            return;
        }
        ?><p class = "text-center text-md-start"> <?php
        foreach( $likes as $like)
        {
            ?><i style = "color: red" class="fas fa-heart"></i>&nbsp;
            <a title = "Profil <?=htmlspecialchars($like['username'])?>" style = "color: black;" href = "/php/user.php?u=<?=htmlspecialchars($like['username'])?>"><?=htmlspecialchars($like['username'])?></a><br><?php
        }
        ?> </p> <?php
    }

    function ShowTask($task, $showUser = true)
    {
        //var_dump($task);
        
        $loggedin = LoggedIn();
        $isPostUser = isUser($task['username']);

        $type = TaskTypeToOutput($task['type']);
        $state_icon = TaskStateToOutput($task, $loggedin, $isPostUser);
        $added_date = TaskDateToOutput($task['added_date']);
        $visibility = TaskVisibilityToIndexOutput($task['visibility']);

        

        ?><div id = "task-<?=$task['taskid']?>" style = "background-color: <?=($task['state'] == "done" ? "#e8ffed" : "#eeeeee")?>;"><div class = "row px-3 pt-3">
        <div class = "col-12 col-md-9">
        <p><?=$state_icon?>&nbsp;<strong><a style = "text-decoration: none; color: black;" href = "/php/task.php?task=<?=$task['taskid']?>"><?=htmlspecialchars($task['title'])?></a></strong> 
        <?php if($showUser) { ?>
            <small> - <a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: black;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><?=htmlspecialchars($task['username'])?></a></small>
        <?php } ?>
        <?=($task['state'] == "done" ? " - <strong>Finalizată!</strong>" : "")?>
        </p>
        <blockquote class = "text-break"><?=nl2br(htmlspecialchars(trim($task['description'])))?></blockquote>
        <p>
        <?php if($loggedin && $isPostUser)
        { 
            if($task['state'] == "todo") { ?> <span class = "d-none d-md-inline"><br><small><a style = "text-decoration: none;" href = "/php/update-task.php?id=<?=$task['taskid']?>&state=done" ><i class="far fa-check-square"></i> Finalizare </a> </small> </span><?php }
        }
        ?>
        </p>
        <span class = "d-md-none"> <hr> </span>
        </div>
        <div class = "col-12 col-md-3">
        <p class = "text-md-end">
        <span class = "d-none d-md-inline">
            <?php if($loggedin){?><a id = "like1-<?=$task['taskid']?>" class = "likebtn" onclick="liketask(<?=$task['taskid']?>)" style = "text-decoration: none; color: black;"><small id = "liketext1-<?=$task['taskid']?>"><?=($task['likedbyuser'] ? "Apreciezi" : 'Apreciază')?></small> 
                <i id = "likeicon1-<?=$task['taskid']?>" style = "color: <?=$task['likedbyuser']?"red":"black"?>" class="fa<?=$task['likedbyuser']?"s":"r"?> fa-heart"></i></a><br> <?php } ?>
            <small id = "likecount1-<?=$task['taskid']?>"><strong><?=$task['points']?></strong> punct<?=($task['points'] == 1 ? "" : "e")?></small><br></span>
            <span class = "d-none d-md-inline"><?php if($showUser) { ?><a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: #000;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><i class="fas fa-user-circle"></i></a><?php } ?>
            &nbsp;<a style = "text-decoration: none; color: black;" title = "Mai mult..." href = "/php/task.php?task=<?=$task['taskid']?>"><i class="fas fa-ellipsis-v"></i></a>&nbsp;<br></span>
            <small><strong>Tip: <span class = "<?=$type['style']?>"><?=$type['val']?></span> </strong><br>
            <?=($visibility?$visibility."<br>":"")?>
            Postată <?=$added_date?> <br> </small>
            <span class = "d-md-none">
            <?php if($loggedin){?><a id = "like2-<?=$task['taskid']?>" class = "likebtn" onclick="liketask(<?=$task['taskid']?>)" style = "text-decoration: none; color: black;"><small id = "liketext2-<?=$task['taskid']?>"><?=($task['likedbyuser'] ? "Apreciezi" : 'Apreciază')?></small> 
                <i id = "likeicon2-<?=$task['taskid']?>" style = "color: <?=$task['likedbyuser']?"red":"black"?>" class="fa<?=$task['likedbyuser']?"s":"r"?> fa-heart"></i></a><br> <?php } ?>
            <small id = "likecount2-<?=$task['taskid']?>"><strong><?=$task['points']?></strong> punct<?=($task['points'] == 1 ? "" : "e")?></small></span>
            <?php if($loggedin && $isPostUser)
        { 
            if($task['state'] == "todo") { ?> <span class = "d-md-none"><br><small><a style = "text-decoration: none;" href = "/php/update-task.php?id=<?=$task['taskid']?>&state=done"><i class="far fa-check-square"></i> Finalizare </a> </small> </span><?php }
        }
        ?>
        <br><span class = "d-md-none"><?php if($showUser) { ?><a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: #000;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><i class="fas fa-user-circle"></i></a><?php } ?>
            &nbsp;<a style = "text-decoration: none; color: black;" title = "Mai mult..." href = "/php/task.php?task=<?=$task['taskid']?>"><i class="fas fa-ellipsis-v"></i></a>&nbsp;<br></span>
            
            
        </p>
        </div>
        </div><hr></div> <?php
    }
    
    /*function ShowTaskExternal($task, $loggedin, $username, $showUser = true)
    {
        //var_dump($task);
        $isPostUser = ($username == $task['username']);
        $type = TaskTypeToOutput($task['type']);
        $state_icon = TaskStateToOutput($task, $loggedin, $isPostUser);
        $added_date = TaskDateToOutput($task['added_date']);

        

        ?><div style = "background-color: <?=($task['state'] == "done" ? "#e8ffed" : "#eeeeee")?>;"><div class = "row px-3 pt-3">
        <div class = "col-12 col-md-9">
        <p><?=$state_icon?>&nbsp;<strong><a style = "text-decoration: none; color: black;" href = "/php/task.php?task=<?=$task['taskid']?>"><?=htmlspecialchars($task['title'])?></a></strong> 
        <?php if($showUser) { ?>
            <small> - <a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: black;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><?=htmlspecialchars($task['username'])?></a></small>
        <?php } ?>
        <?=($task['state'] == "done" ? " - <strong>Finalizată!</strong>" : "")?>
        </p>
        <blockquote class = "text-break"><?=nl2br(htmlspecialchars(trim($task['description'])))?></blockquote>
        <p>
        <?php if($loggedin && $isPostUser)
        { 
            if($task['state'] == "todo") { ?> <span class = "d-none d-md-inline"><br><small><a style = "text-decoration: none;" href = "/php/update-task.php?id=<?=$task['taskid']?>&state=done"><i class="far fa-check-square"></i> Finalizare </a> </small> </span><?php }
        }
        ?>
        </p>
        <span class = "d-md-none"> <hr> </span>
        </div>
        <div class = "col-12 col-md-3">
        <p class = "text-md-end">
        <span class = "d-none d-md-inline">
            <?php if($loggedin){?><a style = "text-decoration: none; color: black;" href = "../php/like-task.php?id=<?=$task['taskid']?>"><small><?=($task['likedbyuser'] ? "Apreciezi" : 'Apreciază')?></small> 
                <i style = "color: <?=$task['likedbyuser']?"red":"black"?>" class="fa<?=$task['likedbyuser']?"s":"r"?> fa-heart"></i></a><br> <?php } ?>
            <small><strong><?=$task['points']?></strong> punct<?=($task['points'] == 1 ? "" : "e")?></small><br></span>
            <span class = "d-none d-md-inline"><?php if($showUser) { ?><a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: #000;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><i class="fas fa-user-circle"></i></a><?php } ?>
            &nbsp;<a style = "text-decoration: none; color: black;" title = "Mai mult..." href = "/php/task.php?task=<?=$task['taskid']?>"><i class="fas fa-ellipsis-v"></i></a>&nbsp;<br></span>
            <small><strong>Tip: <span class = "<?=$type['style']?>"><?=$type['val']?></span> </strong><br>
            Postată <?=$added_date?> </small><br>
            <span class = "d-md-none">
            <?php if($loggedin){?><a style = "text-decoration: none; color: black;" href = "../php/like-task.php?id=<?=$task['taskid']?>"><small><?=($task['likedbyuser'] ? "Apreciezi" : 'Apreciază')?></small> 
                <i style = "color: <?=$task['likedbyuser']?"red":"black"?>" class="fa<?=$task['likedbyuser']?"s":"r"?> fa-heart"></i></a><br> <?php } ?>
            <small><strong><?=$task['points']?></strong> punct<?=($task['points'] == 1 ? "" : "e")?></small></span>
            <?php if($loggedin && $isPostUser)
        { 
            if($task['state'] == "todo") { ?> <span class = "d-md-none"><br><small><a style = "text-decoration: none;" href = "/php/update-task.php?id=<?=$task['taskid']?>&state=done"><i class="far fa-check-square"></i> Finalizare </a> </small> </span><?php }
        }
        ?>
        <br><span class = "d-md-none"><?php if($showUser) { ?><a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: #000;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><i class="fas fa-user-circle"></i></a><?php } ?>
            &nbsp;<a style = "text-decoration: none; color: black;" title = "Mai mult..." href = "/php/task.php?task=<?=$task['taskid']?>"><i class="fas fa-ellipsis-v"></i></a>&nbsp;<br></span>
            
            
        </p>
        </div>
        </div><hr></div> <?php
    }
    
*/

    function ShowTaskFull($task)
    {
        $loggedin = LoggedIn();
        $isPostUser = isUser($task['username']);

        $added_date = TaskDateToOutput($task['added_date']);
        $state_icon = TaskStateToOutput($task, $loggedin, $isPostUser);
        $output_state = TaskStateToOutputTaskPage($task['state']);
        $type = TaskTypeToOutput($task['type']);
        $visibility = TaskVisibilityToOutput($task['visibility']);

        ?>
    <div class = "container">
    <div class = "row">
        <div class = "col-10 d-flex align-items-center" style = "background-color: #e0e0e0;">
            <h3 ><i style = "color: gray;" class="fas fa-chevron-right"></i> <?=htmlspecialchars($task['title'])?></h1>
        </div>
        <div class = "col-2 px-3 pt-2 d-flex justify-content-center" style = "background-color: #f5f5f5;">
            <p class = "text-center"><a title = "Profil <?=htmlspecialchars($task['username'])?>" style = "color: #000;" href = "/index.php?user=<?=htmlspecialchars($task['username'])?>"><strong><i  class="fs-1 fas fa-user-circle"></i><br><?=$task['username']?> </strong></a><br>
        <small>Vizibilă <?=$visibility?></small></p>
        </div>    
    </div>
    <div class = "row pt-2">
    <div class = "col-12 col-md-6">
        <p class = "text-center text-md-start">
        <?php if(LoggedIn()){?><a class = "fs-3" title = "<?=$task['likedbyuser']?"Apreciezi postarea":"Apreciază"?>" style = "text-decoration: none; color: black;" href = "../php/like-task.php?id=<?=$task['taskid']?>">
            
            <i style = "color: <?=$task['likedbyuser']?"red":"black"?>" class="fa<?=$task['likedbyuser']?"s":"r"?> fa-heart"></i></a> <?php } ?>


        </p>
    </div>
    <div class = "col-12 col-md-6">
        <p class = "text-center text-md-end fs-3"><strong>Tip: <span class = "<?=$type['style']?>"><?=$type['val']?></span></strong></p>
    </div>
    </div>
    <div class = "row">
    <div class = "col-12 col-md-10 p-2" style = "background-color: #f2f2f2;">
        <blockquote class = "text-break m-2 p-4" style = "background-color: <?=$output_state['color']?>; min-height: 60px;">
        <p><?=nl2br(htmlspecialchars(trim($task['description'])))?></p>
        <p class = "text-end"><strong><?=$output_state['text']?></strong></p>
        </blockquote>
    </div>

    <div class = "col-12 col-md-2 pt-2 ps-2"  style = "background-color: #f5f5f5;">
        <h4 class = "text-center d-block pe-2"> Apreciat de:</h4>
        <hr class = "d-none d-md-block">
        <?php ShowTaskLikes($task['taskid']);
        if($task['points'])
        {
            ?><hr><p class = "text-center"><strong><?=$task['points']?></strong> punct<?=($task['points'] == 1 ? "" : "e")?></p><?php 
        }
            ?>
        
    </div>
    <div class = "row pt-2">
    <p class = "text-center text-md-end"> Adăugat <?=$added_date?> (<?=date("Y-m-d, h:i:s", $task['added_date'])?>)</p>
    </div>

    </div>

    </div>
        <?php


    }





function ShowTaskOptions($task)
{
     if(isUser($task['username']))
    { 
        ?>
        <hr>
        <h4 class = "display-4 text-center" id = "opts"> Opțiuni </h4>
        <div class = "row mt-3">
        
        <div class = "col-12 mt-3">
        <?php if($task['state'] == 'todo'){ ?> <a role = "button" href = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=setdone" class = "btn btn-outline-primary d-block mt-2"> Finalizează</a> <?php } ?>
        <?php if($task['state'] != 'todo'){ ?> <a role = "button" href = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=settodo" class = "btn btn-outline-primary d-block mt-2"> 
        <?=($task['state'] == 'done' ? 'Revocă finalizarea' : 'Reia')?></a> <?php } ?>
        <?php if($task['state'] == 'todo'){ ?> <a role = "button" href = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=setaborted" class = "btn btn-outline-primary d-block mt-2"> Anulează</a> <?php } ?>
        <?php if($task['state'] != 'deleted'){ ?> <a role = "button" href = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=setdeleted" class = "btn btn-outline-danger d-block mt-2"> Șterge</a> <?php } ?>
        
        <?php if($task['state'] == 'deleted'){ ?>
        <form method = "post" action = "delete-task.php">
        <input type = "hidden" name = "t" value = "<?=$task['taskid']?>" >
        <button class = "btn btn-outline-danger d-block mt-2" type = "submit"> Șterge definitiv! </button>
        </form>
        <?php } 
        ?>
        </div>
        
        <?php
        if($task['state'] != 'deleted')
        {
            ?>
        <h4 class = "mt-3"> Modifică Titlu </h4>
        <form method = "POST" action = "modify-task-taskpage.php?t=<?=$task['taskid']?>&u=<?=$task['username']?>&action=title">
        <div class = "mt-2">
            <label for = "newTitle" class = "form-label"> Titlu Nou </label>
            <input type = "text" class = "form-control" name = "data" id = "newTitle" value = "<?=htmlspecialchars($task['title'])?>" required>
        </div>
        <div class = "mt-2">
            <button type = "submit" class = "btn btn-outline-primary d-block"> Modifică titlul </button>
        </div>
        </form>

        <h4 class = "mt-3"> Modifică Descriere </h4>
        <form method = "POST" action = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=description" required>
        <div class = "mt-2">
            <label for = "newDescription" class = "form-label"> Descriere Nouă </label>
            <textarea style = "height: 10em; resize: none;" class = "form-control" id = "newDescription" name = "data" required><?=htmlspecialchars($task['description'])?></textarea>
        </div>
        <div class = "mt-2">
            <button role = "button" type = "submit" class = "btn btn-outline-primary"> Modifică descrierea </button>
            <a role = "button" href = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=deletedescription"
            class = "btn btn-outline-primary"> Șterge descrierea </a>
        </div>
        </form>

        <h4 class = "mt-3"> Modifică Tip </h4>
        <form method = "POST" action = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=type">
        <div class = "mt-2">
        <p> Tipul nou: </p>
        </div>
        <div class = "form-check form-check-inline">
            <input type = "radio" id = "type1" name = "data" value = "day" <?=($task['type'] == 'day' ? "disabled" : "")?>>
            <label for = "type1" class = "form-label"> Urgent <small>(în cel mult o zi)</small> </label>
        </div>
        <div class = "form-check form-check-inline">
            <input type = "radio" id = "type2" name = "data" value = "week" <?=($task['type'] == 'week' ? "disabled" : "")?>>
            <label for = "type2" class = "form-label"> Termen Scurt <small>(în cel mult o săptămână)</small> </label>
        </div>
        <div class = "form-check form-check-inline">
            <input type = "radio" id = "type3" name = "data" value = "month" <?=($task['type'] == 'month' ? "disabled" : "")?>>
            <label for = "type3" class = "form-label"> Termen Lung <small>(mai mult de o săptămână)</small> </label>
        </div>
        <div class = "mt-2">
            <button type = "submit" class = "btn btn-outline-primary d-block"> Modifică Tipul </button>
        </div>
        </form>

        <h4 class = "mt-3"> Modifică Vizibilitate </h4>
        <form method = "POST" action = "modify-task-taskpage.php?t=<?=$task['taskid']?>&action=visibility">
        <div class = "mt-2">
        <p> Tipul nou: </p>
        </div>
        <div class = "form-check form-check-inline">
            <input type = "radio" id = "visibility1" name = "data" value = "all" <?=($task['visibility'] == 'all' ? "disabled" : "")?>>
            <label for = "visibility1" class = "form-label">Public <small>(toată lumea)</small> </label>
        </div>
        <div class = "form-check form-check-inline">
            <input type = "radio" id = "visibility2" name = "data" value = "registered" <?=($task['visibility'] == 'registered' ? "disabled" : "")?>>
            <label for = "visibility2" class = "form-label"> Utilizatori <small>(doar utilizatori înregistrați)</small> </label>
        </div>
        <div class = "form-check form-check-inline">
            <input type = "radio" id = "visibility3" name = "data" value = "private" <?=($task['visibility'] == 'private' ? "disabled" : "")?>>
            <label for = "visibility3" class = "form-label"> Privat <small> (Doar eu)</small> </label>
        </div>
        <div class = "mt-2">
            <button type = "submit" class = "btn btn-outline-primary d-block"> Modifică vizibilitatea </button>
        </div>
        </form>
        <?php
        }
        ?>
        </div>
        </div>


     <?php   
    }
    
}

function ShowTaskConfirmation($task)
{
    ;
}

?>