<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "reset";
$error = array();

if ($user->user_exists) {
    header('Location: ./home.php');
    exit();
}

$id = $_GET['id'];
$code = $_GET['code'];

include_once 'include/class_mail.php';
$class = new Mail();
$check = $class->checkCode($id, $code);

if ($check['status'] && isset($_POST['submit'])) {
    $error = $class->reset($id, $_POST['password'],$_POST['cfpassword']);
}
$smarty->assign('error', $error);
$smarty->assign('check', $check);

include "footer.php";
