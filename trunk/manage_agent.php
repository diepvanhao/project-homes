<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "manage_agent";

//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('manage-agent')) {
    header('Location: ./restrict.php');
    exit();
}

if($user->user_info['user_locked']){
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

$agentClass = new HOMEAgent();
$house=new HOMEHouse();
//calculator paging
$max = 25;
$totalItem = $agentClass->getTotalItem($search);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;



$offset = $page_number * $max - $max;
$length = $max;



//$agentClass = new HOMEAgent();
$agents = $agentClass->getAgent($search, $offset, $length);
for ($i = 0; $i < count($agents); $i++) {
    if ($house->isSerialized($agents[$i]['agent_address'])) {
        $house_address_serialize = unserialize($agents[$i]['agent_address']);
        $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
        $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
        $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
        $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
        $agent_address = $house_address_serialize['agent_address'];
        $agents[$i]['agent_address'] = $city_id_filter . $district_id_filter . $street_id_filter . $ward_id_filter . $agent_address;
    } else {
        $agents[$i]['agent_address'] = $agents[$i]['agent_address'];
    }
}

$smarty->assign('search', $search);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('agents', $agents);
$smarty->assign('canEdit', @HOMEOrder::checkPermisson('create-agent'));

include "footer.php";
