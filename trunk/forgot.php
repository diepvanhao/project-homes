<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "forgot";
$error = array();

if ($user->user_exists) {
    header('Location: ./home.php');
    exit();
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = "";
}

if (isset($_POST['submit'])) {
    include_once 'include/class_mail.php';
    $class = new Mail();
    $error = $class->forgot($email);
}

$smarty->assign('error', $error);
$smarty->assign('email', $email);

include "footer.php";
