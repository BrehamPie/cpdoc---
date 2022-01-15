<?php 
require_once '../includes/functions.inc.php';
session_start();
$ar = array();
$todo = $_POST['action'];
if($todo == 'login'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userData = getLoginData($username);
    if(is_null($userData)) {
        $ar['problem']='Username not found.';
        $jsonData=json_encode($ar);
        echo $jsonData;
    }
    else{
        $savedPass = $userData['password'];
        if(password_verify($password,$savedPass)){
            $_SESSION["userid"]=$userData['id'];
            $_SESSION["role"]=$userData['role'];
                  $ar['problem']='ok';
                  $ar['role'] = $userData['role'];
            }
        else{
            $ar['problem']='Wrong Password';
        }
        $jsonData=json_encode($ar);
        echo $jsonData;
    }
}
else{
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password2=$_POST['repassword'];
    $ar['problem']='';
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
    {
        $ar['problem'] = 'Username contains special Characters.';
        $jsonData=json_encode($ar);echo $jsonData;
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ar['problem'] = 'Invalid Email.';
        $jsonData=json_encode($ar);echo $jsonData;
        exit();
    }
    if(strlen($password)<6){
        $ar['problem'] = 'Password length less than 6.';
        $jsonData=json_encode($ar);echo $jsonData;
        exit();
    }
    if($password != $password2){
        $ar['problem'] = 'Passwords do not match.';
        $jsonData=json_encode($ar);echo $jsonData;
        exit();
    }
    $sql = "SELECT COUNT(*) as cnt FROM user WHERE email = '$email'";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    $cnt = $row['cnt'];
    if($cnt>0){
        $ar['problem'].='Email Already Exist.';
        $jsonData=json_encode($ar);echo $jsonData;
        exit();
    }
    $sql = "SELECT COUNT(*) as cnt FROM user WHERE username = '$username'";
    $res = mysqli_query($connection,$sql);
    $row = mysqli_fetch_assoc($res);
    $cnt = $row['cnt'];
    if($cnt>0){
        $ar['problem'].='Username Already Exist.';
        $jsonData=json_encode($ar);echo $jsonData;
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    insertUser($username,$email,$hashed_password);
    $ar['problem'] = 'reg-ok';
    $jsonData=json_encode($ar);
    echo $jsonData;
}