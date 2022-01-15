<?php
session_start();
require_once '../includes/functions.inc.php';
$uid = $_SESSION['userid'];
var_dump($_POST);

$userData = getUserData($uid);
if ($_POST['action'] == 'general') {
    $curFullName = $_POST['fullname'] ?? $userData['fullname'];
    $country = $_POST['country'] ?? $userData['country'];
    $organization = $_POST['organization'] ?? $userData['organization'];
    $curAbt = $_POST['about'] ?? $userData['about'];
    $basename = $userData['img'];
    if (!file_exists($_FILES['filetoupload']['tmp_name']) || !is_uploaded_file($_FILES['filetoupload']['tmp_name'])) {
        //echo 'No upload';
    } else {
        $target_dir = "../assets/img/users/";
        $uploadOk = 1;
        $target_file = $target_dir . basename($_FILES["filetoupload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $filename   = $uid;
        $basename   = $filename . "." . $imageFileType;
        $source       = $_FILES["filetoupload"]["tmp_name"];
        $destination  = "{$target_dir}/{$basename}";
        $check = getimagesize($_FILES["filetoupload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["filetoupload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
            echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            move_uploaded_file($source, $destination);
        }
    }
    updateUser($uid, $curFullName, $country, $organization, $curAbt, $basename);
} else {
    $oldpass = $_POST['old_pass'];
    $newpas1 = $_POST['new_pass'];
    $newpas2 = $_POST['new_pass2'];
    $savedPass = $userData['password'];
    if (!password_verify($oldpass, $savedPass)) {
        echo "Please enter your current password correctly.";
    } else {
        if (strlen($newpas1) < 6) {
            echo "Password length must be at least 6.";
        } else if ($newpas1 != $newpas2) {
            echo "Please type your newpassword correctly";
        } else {
            $hashed_password = password_hash($newpas1, PASSWORD_DEFAULT);
            $sql = "UPDATE user
                    SET password = '$hashed_password'
                    WHERE id = $uid";
            $res = myquery($sql);
            echo "Password Update Successful";
        }
    }
}
