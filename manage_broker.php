<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "manage_broker";
include_once 'include/class_autologin.php';
//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('manage-broker')) {
    header('Location: ./restrict.php');
    exit();
}

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

$brokerClass = new HOMEBroker();
$house = new HOMEHouse();
//calculator paging
$max = 25;
$totalItem = $brokerClass->getTotalItem($search);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;



$offset = $page_number * $max - $max;
$length = $max;
$autologin = new Autologin();
$brokers = $brokerClass->getBroker($search, $offset, $length);

for ($i = 0; $i < count($brokers); $i++) {
    if ($house->isSerialized($brokers[$i]['broker_company_address'])) {
        $house_address_serialize = unserialize($brokers[$i]['broker_company_address']);
        $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
        $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
        $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
        $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
        $broker_company_address = $house_address_serialize['broker_company_address'];
        $brokers[$i]['broker_company_address'] = $city_id_filter . $district_id_filter . $street_id_filter . $ward_id_filter . $broker_company_address;
    } else {
        $brokers[$i]['broker_company_address'] = $brokers[$i]['broker_company_address'];
    }
    //

    $auto = $autologin->getBrokerLogin($brokers[$i]['broker_company_name']);
    if ($auto) {
        $brokers[$i]['name'] = $auto['name'];
        $brokers[$i]['action'] = $auto['action'];
        $brokers[$i]['username'] = $auto['username'];
        $brokers[$i]['password'] = $auto['password'];
        $brokers[$i]['idlogname'] = $auto['idlogname'];
        $brokers[$i]['passlogname'] = $auto['passlogname'];
        $brokers[$i]['submitname'] = isset($auto['submitname'])?$auto['submitname']:"";
        $brokers[$i]['inputhidden'] = isset($auto['inputhidden'])?$auto['inputhidden']:"";
    } else {
        $brokers[$i]['name'] = "";
        $brokers[$i]['action'] = "";
        $brokers[$i]['username'] = "";
        $brokers[$i]['password'] = "";
        $brokers[$i]['idlogname'] = "";
        $brokers[$i]['passlogname'] = "";
        $brokers[$i]['submitname'] = "";
        $brokers[$i]['inputhidden'] = "";
    }
}

$smarty->assign('search', $search);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('brokers', $brokers);

include "footer.php";
