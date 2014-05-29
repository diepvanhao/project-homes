<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
include "include/class_detail.php";
$page = "order_detail";

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
    $order_id = $content[1];
} else {
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
    } elseif (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    } else {
        $order_id = "";
    }
}
$house = new HOMEHouse();
$detail = new HOMEDetail();

$order = $detail->getOrder($order_id);
$smarty->assign('order', $order);    
$smarty->assign('room', $detail->getRoomDetail($order['room_id']));
$smarty->assign('house', $house->getHouseById($order['house_id']));
$smarty->assign('client', $detail->getClient($order['client_id']));
$smarty->assign('broker', $detail->getBroker($order['broker_id']));
$smarty->assign('history', $detail->getHistory($order['id']));


include "footer.php";