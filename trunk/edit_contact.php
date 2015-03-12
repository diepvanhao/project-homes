<?php

include "header.php";
include_once 'include/class_autologin.php';
$page = "edit_contact";
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

if (isset($_POST['id'])) {
    $id = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = "";
}
if (isset($_POST['name'])) {
    $name = $_POST['name'];
} elseif (isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    $name = "";
}
if (isset($_POST['username'])) {
    $username = $_POST['username'];
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
    $username = "";
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
} elseif (isset($_GET['password'])) {
    $password = $_GET['password'];
} else {
    $password = "";
}
if (isset($_POST['comment'])) {
    $comment = $_POST['comment'];
} elseif (isset($_GET['comment'])) {
    $comment = $_GET['comment'];
} else {
    $comment = "";
}
$autologin = new Autologin();

if (isset($_POST['submit'])) {

    $result = $autologin->update($name,$username, $password, $comment, $id);
    if ($result) {
        $notify = "アップデート成功 !!!";
    }
} else {
    $contact = $autologin->getContactById($id);
    $comment = $contact['comment'];
    $username = $contact['username'];
    $password = $contact['password'];
    $name=$contact['name'];
}
$smarty->assign('id', $id);
$smarty->assign('username', $username);
$smarty->assign('password', $password);
$smarty->assign('comment', $comment);
$smarty->assign('name', $name);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);
include "footer.php";
