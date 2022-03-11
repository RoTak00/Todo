<?php




function AddAlert($text, $type)
    {
        if(! isset($_SESSION['alerts']))
            $_SESSION['alerts'] = [];
        
        $alert = array(
            "text" => $text,
            "type" => $type
        );
        $_SESSION['alerts'][] = $alert;

    }

    function ShowAlert()
    {
        //print_r ($_SESSION);
        if(! isset($_SESSION['alerts']))
        {
            return;
        }
        ?>
        <div class="container py-3">
            <?php
            foreach($_SESSION['alerts'] as $alert)
            {
                ?>
                    <div class="alert alert-<?=$alert['type']?>" role = "alert">
                        <?=htmlspecialchars($alert['text'])?>
                    </div>
                <?php
            }
            unset($_SESSION['alerts']);
            ?>
        </div>
        <?php
    }

    function LoggedIn()
    {
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
            return $_SESSION['userid'];
        else
            return 0;
    }
    function Admin()
    {
        if(LoggedIn() && $_SESSION["admin"] == true)
            return true;
        else
            return false;
    }
    function isUser($r_username)
    {
        if(LoggedIn() && ($_SESSION['username'] == $r_username))
            return true;
        else
            return false;
    }

    
    ?>