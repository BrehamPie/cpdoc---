<?php 
include '../includes/functions.inc.php';
$comment = $_POST['comment'];
$blogid = $_POST['blog_id'];
$userid = $_POST['user_id'];
var_dump($_POST);
$sql = "INSERT INTO comment(user_id,blog_id,date_added,body)
        VALUES($userid,$blogid,NOW(),'$comment')";
myquery($sql);
echo "Your Comment is Posted Successfully.Please Refresh Page to See the Change";
?>