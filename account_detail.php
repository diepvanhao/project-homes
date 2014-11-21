<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
include "include/class_detail.php";
$page = "account_detail";
//date_default_timezone_set("Asia/Bangkok"); 

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
$house = new HOMEHouse();
$account = $user->getAccountById($account_id);
$agent_detail = $agent->getAgentById($account['agent_id']);

if ($house->isSerialized($agent_detail['agent_address'])) {
    $house_address_serialize = unserialize($agent_detail['agent_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = $house_address_serialize['agent_address'];
    $agent_detail['agent_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
}

$smarty->assign('account', $account);    
$smarty->assign('agent', $agent_detail);    
$smarty->assign('targets', $user->getUserTarget($account_id));    


include "footer.php";