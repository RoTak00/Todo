
<div class = "container mt-5">
<form action = "/php/add-task.php" method = "post">
    <!-- ------------------ Title ------------------------- -->
    <div class = "mt-2">
        <label for = "taskTitle" class = "form-label"> Ce task ai de realizat? </label>
        <input type = "text" class = "form-control" id = "taskTitle" name = "taskTitle" required>
    </div>

    <!-- ------------------ Type ------------------------- -->
    <div class = "mt-2">
        <p> De completat: </p>
    </div>
    <div class = "form-check form-check-inline">
        <input type = "radio" id = "type1" name = "taskType" value = "day" checked>
        <label for = "type1" class = "form-label"> Urgent <small>(în cel mult o zi)</small> </label>
    </div>
    <div class = "form-check form-check-inline">
        <input type = "radio" id = "type2" name = "taskType" value = "week">
        <label for = "type2" class = "form-label"> Termen Scurt <small>(în cel mult o săptămână)</small> </label>
    </div>
    <div class = "form-check form-check-inline">
        <input type = "radio" id = "type3" name = "taskType" value = "month">
        <label for = "type3" class = "form-label"> Termen Lung <small>(mai mult de o săptămână)</small> </label>
    </div>

    <!-- ------------------ Privacy ------------------------- -->
    <div class = "mt-2">
        <p> Vizibilitate: </p>
    </div>
    <div class = "form-check form-check-inline">
        <input type = "radio" id = "visibility1" name = "taskVisibility" value = "all">
        <label for = "visibility1" class = "form-label"> Public <small>(toată lumea)</small> </label>
    </div>
    <div class = "form-check form-check-inline">
        <input type = "radio" id = "visibility2" name = "taskVisibility" value = "registered" checked>
        <label for = "visibility2" class = "form-label"> Utilizatori <small>(doar utilizatori înregistrați)</small> </label>
    </div>
    <div class = "form-check form-check-inline">
        <input type = "radio" id = "visibility3" name = "taskVisibility" value = "private">
        <label for = "visibility3" class = "form-label"> Privat <small> (doar eu)</small></label>
    </div>

    <!-- ------------------ Description ------------------------- -->
    <div class = "mt-2">
        <label for = "taskDescription" class = "form-label"> Scrie o descriere scurtă a task-ului tău! <small>(Opțional)</small> </label>
        <textarea style = "height: 10em; resize: none;" class = "form-control" id = "taskDescription" name = "taskDescription"></textarea>
    </div>

    <!-- ------------------ Submit ------------------------- -->
    <div class = "mt-2">
        <button type = "submit" class = "btn btn-outline-primary d-block"> Adaugă task </button>
    </div>

<?php
    if(isset($f_state))
    { 
    ?> <input type = "hidden" name = "prevpage_f_state" value = "<?=$f_state?>" > <?php
    }
?>
</form>
</div>
<hr>