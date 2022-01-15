<?php
    include '../includes/functions.inc.php';
    session_start();
   // var_dump($_POST);
    $todo = $_POST['action'];
    if($todo == 'insert'){
        $title = mysqli_escape_string($connection,$_POST['title']);
        $body = mysqli_escape_string($connection,$_POST['body']);
        $type = $_POST['type'];
        $userid = $_SESSION['userid'];
        $res = insertPost($userid,$title,$body,$type);
        echo $res;
    }else{
        $title = mysqli_escape_string($connection,$_POST['title']);
        $body = mysqli_escape_string($connection,$_POST['body']);
        $type = $_POST['type'];
        $post_id = $_POST['post_id'];
        $res = editPost($post_id,$title,$body,$type);
        echo $res;
    }
    
?>