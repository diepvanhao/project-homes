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

if ($user->user_info['user_locked']) {
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
$housedetail = $house->getHouseById($room['house_id']);

$house_address_serialize = unserialize($housedetail['house_owner_address']);
$city_id_filter = $house->getNameCity($house_address_serialize['city_id_owner']);
$district_id_filter = $house->getNameDistrict($house_address_serialize['district_id_owner']);
$street_id_filter = $house->getNameStreet($house_address_serialize['street_id_owner']);
$ward_id_filter = $house->getNameWard($house_address_serialize['ward_id_owner']);
$house_address = $house_address_serialize['house_owner_address'];
$housedetail['house_owner_address'] = $city_id_filter . $district_id_filter . $street_id_filter . $ward_id_filter . $house_address;

$house_address_serialize = unserialize($housedetail['house_address']);
$city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
$district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
$street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
$ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
$house_address = $house_address_serialize['house_address'];
$address=$city_id_filter . $district_id_filter . $street_id_filter . $ward_id_filter . $house_address;

$brokers = $detail->getBrokers($id, $room['house_id']);
for ($i = 0; $i < count($brokers); $i++) {
    $house_address_serialize = unserialize($brokers[$i]['broker_company_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = $house_address_serialize['broker_company_address'];
    $brokers[$i]['broker_company_address'] = $city_id_filter . $district_id_filter . $street_id_filter . $ward_id_filter . $house_address;
}
var_dump($brokers);
$smarty->assign('room', $room);
$smarty->assign('house', $housedetail);
$smarty->assign('brokers', $brokers);
$smarty->assign('address',$address);


include "footer.php";
