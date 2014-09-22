<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "contact";
include_once 'include/class_autologin.php';
//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
//var_dump($user);die();
if ($user->user_info['user_authorities'] > 2) {
    header('Location: ./restrict.php');
    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}


$autologin = new Autologin();


$auto = $autologin->getBrokerLoginContact();
if ($auto)
    $brokers = $auto;
for ($i = 0; $i < count($brokers); $i++) {    
    $brokers[$i]['submitname'] = isset($brokers[$i]['submitname']) ? $brokers[$i]['submitname'] : "";
    $brokers[$i]['inputhidden'] = isset($brokers[$i]['inputhidden']) ? $brokers[$i]['inputhidden'] : "";
}

$smarty->assign('brokers', $brokers);

include "footer.php";
