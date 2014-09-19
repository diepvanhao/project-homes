<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_group";
$error = null;
$result = FALSE;
$notify = "";

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['group_name'])) {
    $group_name = $_POST['group_name'];
} elseif (isset($_GET['group_name'])) {
    $group_name = $_GET['group_name'];
} else {
    $group_name = "";
}


if (isset($_POST['group_id'])) {
    $group_id = $_POST['group_id'];
} elseif (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];
} else {
    $group_id= "";
}

if (isset($_POST['url'])) {
    $content = $_POST['url'];
} elseif (isset($_GET['url'])) {
    $content = $_GET['url'];
} else {
    $content = "";
}

$content = base64_decode($content);
$content = explode('&', $content);

$validate = array(
    'group_name' => $group_name
);

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $house = new HOMEHouse();
        $result = $house->update_group($group_id, $group_name);
        if ($result['flag']) {
            $notify = "アップデート成功 !!!";
        } elseif ($result['error']) {
            $error[] = $result['error'];
        } else {
            $error[] = "Update fail. Please try again!!!";
        }
    }
} elseif ($content[0] == 'edit') {
    $house = new HOMEHouse();

    $result = $house->getGroupById($content[1]);
    if (!empty($result)) {
        $group_name = $result['group_name'];
        $group_id = $result['id'];
    }
}

$smarty->assign('group_name', $group_name);
$smarty->assign('group_id', $group_id);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
