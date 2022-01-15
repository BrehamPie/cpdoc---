<?php
    include '../includes/functions.inc.php';
    $userid = $_POST['userid'];
    $type = $_POST['action'];
    $blogid = $_POST['blogid'];
    $sql = "SELECT * FROM vote WHERE user_id = $userid AND blog_id = $blogid";
    $res = myquery($sql);
    if(mysqli_num_rows($res)){
        $row = mysqli_fetch_assoc($res);
        if($row['type']==$type){
            echo "You already Voted.";
        }else{
            $sql = "UPDATE vote SET type = $type WHERE user_id = $userid AND blog_id = $blogid";
            myquery($sql);
            echo "Your Vote has been changed";
        }
    }else{
        $sql = "INSERT INTO vote(user_id,blog_id,type,date_added) 
        VALUES($userid,$blogid,$type,NOW())";
        myquery($sql);
        echo "Your vote has been submitted";
    }
?>