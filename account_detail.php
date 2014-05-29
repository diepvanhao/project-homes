<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
include "include/class_detail.php";
$page = "account_detail";

//check user login
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
if ($user->user_info['user_authorities'] > 2) {
    header('Location: ./restrict.php');
    exit();
}

if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
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
if (isset($content[1])) {
    $account_id = $content[1];
} else {
    if (isset($_POST['account_id'])) {
        $account_id = $_POST['account_id'];
    } elseif (isset($_GET['account_id'])) {
        $account_id = $_GET['account_id'];
    } else {
        $account_id = "";
    }
}
$user = new HOMEUser();
$agent = new HOMEAgent();
$account = $user->getAccountById($account_id);


$smarty->assign('account', $account);    
$smarty->assign('agent', $agent->getAgentById($account['agent_id']));    

include "footer.php";