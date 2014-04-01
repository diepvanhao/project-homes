<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "user_login";
$error = null;

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = "";
}if (isset($_POST['password'])) {
    $password = $_POST['password'];
} elseif (isset($_GET['password'])) {
    $password = $_GET['password'];
} else {
    $password = "";
}

if (isset($_POST['submit'])) {
    $userClass = new HOMEUser();
    $error = $userClass->user_login($email, $password);
}
if(empty($error['error'])&&$error['login']){
    $smarty->clearCache();
    header('Location: ./index.php');
    
}
$smarty->assign('error', $error['error']);

include "footer.php";
