<?php
session_start();
include '../includes/functions.inc.php';
$oj = $_POST['oj'];
$name = $_POST['name'];
$link = $_POST['link'];
$userid = $_SESSION['userid'];
if (strlen($oj) == 0 || strlen($name) == 0 || strlen($link) == 0) {
    echo "Please Fill all the required Data";
} else {
    if ($_POST['action'] == 'insert') {
        $sql = "INSERT INTO problem(oj_id,name,url,user_id,status,date_created)
                    VALUES($oj,'$name','$link',$userid,0,NOW())";
        myquery($sql);
        if (isset($_POST['tags'])) {
            $tags = $_POST['tags'];
            $pid = getLastID('problem');
            foreach ($tags as $t) {
                $sql = "INSERT INTO problem_tag(problem_id,tag_id) VALUES($pid,$t)";
                myquery($sql);
            }
        }
        echo "Problem Submitted Successfully";
    } else {
        $probid = $_POST['prob_id'];
        $sql = "UPDATE problem
            SET oj_id = $oj,
            name = '$name',
            url = '$link',
            user_id = $userid,
            status = 0,
            date_updated = NOW()
            WHERE id = $probid";
         myquery($sql);
        $sql = "DELETE FROM problem_tag WHERE problem_id = $probid";
        myquery($sql);
        if (isset($_POST['tags'])) {
            $tags = $_POST['tags'];
            foreach ($tags as $t) {
                $sql = "INSERT INTO problem_tag(problem_id,tag_id) VALUES($probid,$t)";
                myquery($sql);
            }
        }
        echo "Problem Updated Successfully";
    }
}
