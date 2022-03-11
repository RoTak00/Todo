
<?php
// construct INDEX uri
$indexURI = "/index.php";
$querystringchar = "?";

if(isset($f_state) && $f_state)
{
    $indexURI .= $querystringchar."f_state=".$f_state;
    $querystringchar = "&";
}  

// construct PROFILE uri
$profileURI = "/index.php";
$querystringchar = "?";

if(isset($_SESSION['username']))
{
    $profileURI .= $querystringchar."user=".$_SESSION['username'];
    $querystringchar = "&";
}  
if(isset($f_state) && $f_state)
{
    $profileURI .= $querystringchar."f_state=".$f_state;
    $querystringchar = "&";
}  

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 pt-2">

            <li class="nav-item">
                <a class="nav-link <?=($page_type === "index" ? "active" : "")?>" href="<?=$indexURI?>">Acasă</a>
            </li>
        </ul>
    
        <ul class="navbar-nav ml-auto mb-2 mb-lg-0 pt-2">
            <?php
            if(!LoggedIn()){?>
            <li class="nav-item">
                <a class="nav-link <?=$page_type == "register" ? "active" : "" ?>" href="/php/register.php">Înregistrare</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?=$page_type == "login"?"active":""?>" href="/php/login.php">Autentificare</a>
            </li>
            <?php }else{ ?>
            <li class="nav-item">
                <a class="nav-link  <?=$page_type == "login"?"active":""?>" href="/php/disconnect.php">Deconectare</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?=$page_type == "user"?"active":""?>" href="<?=$profileURI?>">Profil</a>
            </li>
            <?php } ?>
        </ul>
    

    </div>
  </div>
</nav>
<div id = "error-div">
<?php
ShowAlert();
?>
</div>