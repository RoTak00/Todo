
<?php
session_start();

include '../modules/mod-functions.php';  

if(LoggedIn())
{
    header("location: ../index.php");
    exit;
}
//display specific page variables
$current_page = "login";
$current_page_title = "Conectare";

//page specific script variables
$loginUsername = $loginPassword = "";
$usernameError = $passwordError = $loginError = $genericError = "";
?>

<?php require_once '../modules/db-config.php';  ?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $usernameError = $passwordError = $loginError = $genericError = "";
    //Check variables set
    if(!isset($_POST['loginUsername'])){
        $usernameError = "Introdu numele de utilizator sau e-mailul!";
        die();
    }
    if(!isset($_POST['loginPassword'])){
        $passwordError = "Introdu parola!";
        die();
    }
    
    // grab variables from thing
    
    //var_dump($_POST);
    $loginUsername = trim($_POST['loginUsername']);
    $loginPassword = trim($_POST['loginPassword']);

    //Check not blank
    if($loginUsername == "")
    {
        $usernameError = "Introdu numele de utilizator sau e-mailul!";
    }
    if($loginPassword == "")
    {
        $passwordError = "Introdu parola!";
    }

    //query database for username
    if($usernameError === "" && $passwordError === "")
    {
        $sqlq = "SELECT id, username, password, is_admin FROM usertable WHERE username = ? OR email = ?";

        if($stmt = $conn->prepare($sqlq))
        {
            $paramUsername = $loginUsername;
            $stmt->bind_param("ss", $paramUsername, $paramUsername);

            if($stmt->execute())
            {
                $stmt->store_result();

                if($stmt->num_rows == 1)
                {
                    $stmt->bind_result($id, $loginUsername, $hashPassword, $is_admin);
                    if($stmt->fetch())
                    {
                        if(password_verify($loginPassword, $hashPassword))
                        {

                            $_SESSION["loggedin"] = true;
                            $_SESSION["userid"] = $id;
                            $_SESSION["username"] = $loginUsername;
                            $_SESSION["admin"] = $is_admin;
                            AddAlert("Te-ai conectat cu succes, ".$loginUsername."!", "success");
                           
                            header("location: /index.php");
                            die();
                        }
                        else
                        {
                            $loginError = "Parola introdusă nu este corectă. Te rog încearcă din nou!";
                        }
                    }
                    else
                    {
                        $genericError = "A apărut o eroare! Te rog încearcă din nou mai târziu. (Preluare): ".$conn->error;
                    }
                }
                else
                {
                    $loginError = "Nu există numele de utilizator sau e-mailul. Dacă nu ai un cont încă, <a href = 'register.php'>Înregistrează-te</a>!";
                }
            }
            else
            {
                $genericError = "A apărut o eroare! Te rog încearcă din nou mai târziu. (Executare): ".$conn->error;
            }
        }
        else
        {
            $genericError = "A apărut o eroare! Te rog încearcă din nou mai târziu. (Preparare): ".$conn->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>

<?php require "../modules/mod-head.php"; ?>

</head>
<body>
 
<?php include "../modules/mod-navbar.php"; ?>

<div class = "container">
<form method = "post">
        <div class = "mt-2">
            <label for = "loginUsername" class = "form-label"> Nume de utilizator sau Mail </label>
            <input type = "text" class = "form-control" name = "loginUsername" id = "loginUsername" value = "<?=$loginUsername?>" required>
            <span> <?=$usernameError?> </span>
        </div>
        <div class = "mt-2">
            <label for = "loginPassword" class = "form-label"> Parolă </label>
            <input type = "password" class = "form-control" name = "loginPassword" id = "loginPassword" required>
            <span> <?=$passwordError?> </span>
        </div>
        <div class = "mt-2">
            <?=$loginError?>
        </div>
        <div class = "mt-2">
            <button type = "submit" class = "btn btn-outline-primary d-inline-block me-3"> Conectează-te </button>
            <a role = "button" href = "/php/register.php" class = "btn btn-outline-primary"> Nu ai cont? Înregistrează-te </a>
            <div> <?=$genericError?></div>
        </div>
        
    </form>
</div>


<?php require "../modules/mod-scripts.php"; ?>
</body>
</html>