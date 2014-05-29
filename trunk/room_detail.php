<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
include "include/class_detail.php";
$page = "room_detail";

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
    $id = $content[1];
} else {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "";
    }
}
$house = new HOMEHouse();
$detail = new HOMEDetail();
$room = $detail->getRoomDetail($id);
$smarty->assign('room', $room);    
$smarty->assign('house', $house->getHouseById($room['house_id']));    
$smarty->assign('brokers', $detail->getBrokers($id, $room['house_id']));    


include "footer.php";