<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "manage_house";

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

$houseClass = new HOMEHouse();
//calculator paging
$max = 25;
$totalItem = $houseClass->getTotalItem($search);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;


$offset = $page_number * $max - $max;
$length = $max;

$houses = $houseClass->getHouse($search, $offset, $length);

for ($i = 0; $i < count($houses); $i++) {
    if ($houseClass->isSerialized($houses[$i]['house_address'])) {
        $house_address_serialize = unserialize($houses[$i]['house_address']);
        $city_id_filter = $houseClass->getNameCity($house_address_serialize['city_id']);
        $district_id_filter = $houseClass->getNameDistrict($house_address_serialize['district_id']);
        $street_id_filter = $houseClass->getNameStreet($house_address_serialize['street_id']);
        $ward_id_filter = $houseClass->getNameWard($house_address_serialize['ward_id']);
        $house_address = $house_address_serialize['house_address'];
        $houses[$i]['house_address'] = $city_id_filter . $district_id_filter  . $street_id_filter  . $ward_id_filter. $house_address;
    } else {
        $houses[$i]['house_address'] = $houses[$i]['house_address'];
    }
}
for ($i = 0; $i < count($houses); $i++) {
    if($houses[$i]['house_type']){
        $houses[$i]['house_type']=$houseClass->getHouseTypeById($houses[$i]['house_type']);
    }
}
for ($i = 0; $i < count($houses); $i++) {
    if($houses[$i]['house_structure']){
        $houses[$i]['house_structure']=$houseClass->getHouseStructureById($houses[$i]['house_structure']);
    }
}


$smarty->assign('search', $search);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('houses', $houses);
include "footer.php";
