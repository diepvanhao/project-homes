<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
include "include/class_detail.php";
$page = "order_detail";
 date_default_timezone_set("Asia/Bangkok");
//check user login
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
//if ($user->user_info['user_authorities'] > 2) {
//    header('Location: ./restrict.php');
//    exit();
//}

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
$house_detail = $house->getHouseById($order['house_id']);
$client = $detail->getClient($order['client_id']);
$broker = $detail->getBroker($order['broker_id']);

if ($house->isSerialized($house_detail['house_address'])) {
    $house_address_serialize = unserialize($house_detail['house_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = $house_address_serialize['house_address'];
    $house_detail['house_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
}

if ($house->isSerialized($house_detail['house_owner_address'])) {
    $house_address_serialize = unserialize($house_detail['house_owner_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = @$house_address_serialize['house_owner_address'];
    $house_detail['house_owner_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
}
if ($house->isSerialized($client['client_address'])) {
    $house_address_serialize = unserialize($client['client_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = $house_address_serialize['client_address'];
    $client['client_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
}
if ($house->isSerialized($broker['broker_company_address'])) {
    $house_address_serialize = unserialize($broker['broker_company_address']);
    $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
    $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
    $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
    $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
    $house_address = @$house_address_serialize['broker_company_address'];
    $broker['broker_company_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
}
$older = array();
//$old_room = null;
//$old_house = null;
//$old_broker = null;

if(!empty($order['change_house_array']) && $house->isSerialized($order['change_house_array'])){
    $house_array = unserialize($order['change_house_array']);
    if(isset($house_array[1]) && !empty($house_array[1])){
        unset($house_array[0]);
        $older = $house_array;
//        $arr = explode('_', $house_array[1]);
//        if(count($arr) == 3){
//            $old_room = $detail->getRoom($arr[0], $arr[1],$arr[2]);
//            $old_house = $house->getHouseById($arr[1]);
//            $old_broker = $detail->getBroker($arr[2]);
//        }
    }
}
$smarty->assign('order', $order);    
$smarty->assign('room', $detail->getRoom($order['room_id'],$order['house_id'],$order['broker_id']));
$smarty->assign('house', $house_detail);
$smarty->assign('client', $client);
$smarty->assign('broker', $broker);
$smarty->assign('history', $detail->getHistory($order['id']));
$smarty->assign('status', empty($order['order_status'])?'いいえ。':'はい。');
$smarty->assign('house_type',$house->getHouseTypeById($house_detail['house_type']));
//$smarty->assign('old_room', $old_room);
//$smarty->assign('old_house', $old_house);
//$smarty->assign('old_broker', $old_broker);
$smarty->assign('older', $older);
$smarty->assign('house_class', $house);
$smarty->assign('detail_class', $detail);

include "footer.php";