<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_source";
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

if (isset($_POST['source_name'])) {
    $source_name = $_POST['source_name'];
} elseif (isset($_GET['source_name'])) {
    $source_name = $_GET['source_name'];
} else {
    $source_name = "";
}


if (isset($_POST['source_id'])) {
    $source_id = $_POST['source_id'];
} elseif (isset($_GET['source_id'])) {
    $source_id = $_GET['source_id'];
} else {
    $source_id = "";
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
    'source_name' => $source_name
);

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $house = new HOMEHouse();
        $result = $house->update_source($source_id, $source_name);
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

    $result = $house->getSourceById($content[1]);
    if (!empty($result)) {
        $source_name = $result['source_name'];
        $source_id = $result['id'];
    }
}

$smarty->assign('source_name', $source_name);
$smarty->assign('source_id', $source_id);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
