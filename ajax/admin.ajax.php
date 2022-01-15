<?php
    include '../includes/functions.inc.php';
    var_dump($_POST);
    $id = $_POST['id'];
    $value = $_POST['value'];
    $action = $_POST['action'];
    $sql = "UPDATE $action
            SET status = $value
            WHERE id = $id";
    myquery($sql);

    ?>