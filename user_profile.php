<?php

include "header.php";

$page = "user_profile";

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}

$user_path_photo = $user->user_info['user_path_photo'];
$user_photo = $user->user_info['user_photo'];

if (isset($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
} elseif (isset($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
} else {
    $photo = "";
}
if (isset($_POST['submit'])) {
    if ($photo) {
        $user->user_photo_upload('photo', false, $user->user_info['id']);
        $error = $user->is_error;
        
        if (!$error) {
           // $user_path_photo = $user->user_info['user_path_photo'];
            //$user_photo = $user->user_info['user_photo'];
            header("Location: ./user_profile.php");
        }
    }
}

$smarty->assign('user_path_photo', $user_path_photo);
$smarty->assign('user_photo', $user_photo);

include "footer.php";
