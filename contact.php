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
//if ($user->user_info['user_authorities'] > 2) {
//    header('Location: ./restrict.php');
//    exit();
//}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['page_number'])) {
    $page_number = $_POST['page_number'];
} elseif (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}
if (isset($_POST['search'])) {
    $search = $_POST['search'];
} elseif (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = "";
}
$search = trim($search);
$autologin = new Autologin();

$auto = $autologin->getBrokerLoginContact();
if ($auto){
    $brokers = $auto;
}
for ($i = 0; $i < count($brokers); $i++) {    
    $brokers[$i]['submitname'] = isset($brokers[$i]['submitname']) ? $brokers[$i]['submitname'] : "";
    $brokers[$i]['inputhidden'] = isset($brokers[$i]['inputhidden']) ? $brokers[$i]['inputhidden'] : "";
}
if(!empty($search)){
    $result = array();
    foreach ($brokers as $key => $value) {
        if(is_array($value) && strpos(strtolower($value['name']),strtolower(trim($search))) !== false){
            $result[] = $value;
        }
    }
    $brokers = $result;
}
$totalItem = count($brokers);
$max =  10;

$totalPage = floor($totalItem / $max);
if ($totalItem % $max > 0){
    $totalPage = $totalPage + 1;
}
if ($page_number > $totalPage){
    $page_number = 1;
}

$smarty->assign('search', $search);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('minkey', $minkey = ($page_number - 1) * $max);
$smarty->assign('maxkey', $minkey + $max);
$smarty->assign('brokers', $brokers);

include "footer.php";
