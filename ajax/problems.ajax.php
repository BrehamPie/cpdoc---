<?php
    include '../includes/functions.inc.php';
    var_dump($_POST);
    toggleSolve($_POST['uid'],$_POST['id']);
?>