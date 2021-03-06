<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
include "include/class_detail.php";
$page = "house_detail";

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
    $house_id = $content[1];
} else {
    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }
}
$house = new HOMEHouse();
$detail = new HOMEDetail();

$housedetail = $house->getHouseById($house_id);
if ($house->isSerialized($housedetail['house_address'])) {
    $house_address_serialize = unserialize($housedetail['house_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = $house_address_serialize['house_address'];
}

$smarty->assign('house', $housedetail);    
$smarty->assign('rooms', $detail->getRooms($house_id));    
$smarty->assign('address', $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address);    

include "footer.php";