<?php
session_start();

require_once '../modules/mod-functions.php';  

if(LoggedIn())
{
    header("location: ../index.php");
    exit;
}
//display specific page variables
$current_page = "register";
$current_page_title = "Înregistrare";

//page specific script variables
$usernameError = $passwordError = $passwordRepeatError = $mailError = $genericError = "";
$registerUsername = "";
$registerMail = "";
?>

<?php require_once '../modules/db-config.php'; ?>

<?php
//Submit Script

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $usernameError = $passwordError = $passwordRepeatError = $genericError = "";
    //Check variables set
    if(!isset($_POST['registerUsername'])){
        $usernameError = "Adaugă un nume de utilizator!";
        die();
    }
    if(!isset($_POST['registerMail'])){
        $mailError = "Adaugă un e-mail!";
        die();
    }
    if(!isset($_POST['registerPassword'])){
        $passwordError = "Adaugă o parolă!";
        die();
    }
    if(!isset($_POST['registerPasswordRepeat'])){
        $passwordRepeatError = "Repetă parola pe care ai introdus-o!";
        die();
    }
    // grab variables from thing
    
    //var_dump($_POST);
    $registerUsername = trim($_POST['registerUsername']);
    $registerMail = trim($_POST['registerMail']);
    $registerPassword = trim($_POST['registerPassword']);
    $registerPasswordRepeat = trim($_POST['registerPasswordRepeat']);

    //Check not blank
    if($registerUsername == "")
    {
        $usernameError = "Adaugă un nume de utilizator!";
    }
    if($registerUsername == "")
    {
        $mailError = "Adaugă un e-mail!";
    }
    if($registerPassword == "")
    {
        $passwordError = "Adaugă o parolă!";
    }
    if($registerPasswordRepeat == "")
    {
        $passwordRepeatError = "Repetă parola pe care ai introdus-o!";
    }

    //Check username ok
    if(strlen($registerUsername) < 3)
    {
        if($usernameError == "")
            $usernameError = "Numele de utilizator trebuie să conțină cel puțin 3 caractere.";
        else
            $usernameError = $usernameError." Numele de utilizator trebuie să conțină cel puțin 3 caractere.";
        
    }
    if(!preg_match('/^[a-zA-Z0-9-_]+$/', $registerUsername))
    {
        if($usernameError == "")
            $usernameError = "Numele de utilizator trebuie să conțină doar caractere alfanumerice (aA-zZ-0-9).";
        else
            $usernameError = $usernameError." Numele de utilizator trebuie să conțină doar caractere alfanumerice (aA-zZ-0-9).";
        $registerUsername = "";
    }
    if(strlen($registerUsername) > 25)
    {
        if($usernameError == "")
            $usernameError = "Numele de utilizator poate conține maximum 25 de caractere.";
        else
            $usernameError = $usernameError." Numele de utilizator poate conține maximum 25 de caractere.";
        $registerUsername = "";
    }

    // CHECK USERNAME EXISTS
    $sqlq = "SELECT id FROM usertable WHERE username = ?";
    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("s", $registerUsername);

        if($stmt->execute())
        {
            $stmt->store_result();

            if($stmt->num_rows == 1)
            {
                if($usernameError == "")
                    $usernameError = "Acest nume de utilizator este folosit deja.";
                else
                    $usernameError = $usernameError." Acest nume de utilizator este folosit deja.";
                $registerUsername = "";
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

    // CHECK EMAIL EXISTS
    $sqlq = "SELECT id FROM usertable WHERE email = ?";
    if($stmt = $conn->prepare($sqlq))
    {
        $stmt->bind_param("s", $registerMail);

        if($stmt->execute())
        {
            $stmt->store_result();

            if($stmt->num_rows == 1)
            {
                if($mailError == "")
                    $mailError = "Acest e-mail este folosit deja.";
                else
                    $mailError = $mailError." Acest e-mail este folosit deja.";
                $registerMail = "";
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

    //Check password ok
    if(strlen($registerPassword) < 6)
    {
        if($passwordError == "")
            $passwordError = "Parola trebuie să conțină cel puțin 6 caractere pentru a fi una puternică.";
        else
            $passwordError = $passwordError." Parola trebuie să conțină cel puțin 6 caractere pentru a fi una puternică.";
    }

    if($registerPassword !== $registerPasswordRepeat)
    {
        if($passwordRepeatError == "")
            $passwordRepeatError = "Parolele pe care le-ai introdus nu sunt identice!";
        else
            $passwordRepeatError = $passwordRepeatError." Parolele pe care le-ai introdus nu sunt identice!";
    }
    // Data OK, send to database
    if($usernameError === "" && $mailError === "" && $passwordError === "" && $passwordRepeatError === "")
    {
        $sqlq = "INSERT INTO usertable (username, email, password) VALUES (?, ?, ?)";

        if($stmt = $conn->prepare($sqlq))
        {
            
            $stmt->bind_param("sss", $registerUsername, $registerMail, $passwordHashed);
            $passwordHashed = password_hash($registerPassword, PASSWORD_DEFAULT);

            if($stmt->execute())
            {
                AddAlert("Te-ai înregistrat cu succes. Acum te poți conecta!", "success");
                header("location: /php/login.php");
                die();
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
            <label for = "registerUsername" class = "form-label"> Nume de utilizator </label>
            <input type = "text" class = "form-control" name = "registerUsername" id = "registerUsername" value = "<?=$registerUsername?>" required>
            <span> <?=$usernameError?> </span>
        </div>
        <div class = "mt-2">
            <label for = "registerMail" class = "form-label"> E-mail </label>
            <input type = "email" class = "form-control" name = "registerMail" id = "registerMail" value = "<?=$registerMail?>" required>
            <span> <?=$mailError?> </span>
        </div>
        <div class = "mt-2">
            <label for = "registerPassword" class = "form-label"> Parolă </label>
            <input type = "password" class = "form-control" name = "registerPassword" id = "registerPassword" required>
            <span> <?=$passwordError?> </span>
        </div>
        <div class = "mt-2">
            <label for = "registerPasswordRepeat" class = "form-label"> Introdu parola din nou </label>
            <input type = "password" class = "form-control" name = "registerPasswordRepeat" id = "registerPasswordRepeat" required>
            <span> <?=$passwordRepeatError?> </span>
        </div>
        <div class = "mt-2">
            <button type = "submit" class = "btn btn-outline-primary d-inline-block me-3"> Înregistrează-te </button>
            <a role = "button" href = "/php/login.php" class = "btn btn-outline-primary"> Ai cont? Conectează-te </a>
            <div> <?=$genericError?></div>
        </div>
        
    </form>
</div>




<?php require "../modules/mod-scripts.php"; ?>
</body>
</html>