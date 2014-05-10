<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "add_house_broker";
$error = null;
$result = FALSE;
$notify = "";

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['broker_company_name'])) {
    $broker_company_name = $_POST['broker_company_name'];
} elseif (isset($_GET['broker_company_name'])) {
    $broker_company_name = $_GET['broker_company_name'];
} else {
    $broker_company_name = "";
}

if (isset($_POST['broker_company_address'])) {
    $broker_company_address = $_POST['broker_company_address'];
} elseif (isset($_GET['broker_company_address'])) {
    $broker_company_address = $_GET['broker_company_address'];
} else {
    $broker_company_address = "";
}

if (isset($_POST['broker_company_phone'])) {
    $broker_company_phone = $_POST['broker_company_phone'];
} elseif (isset($_GET['broker_company_phone'])) {
    $broker_company_phone= $_GET['broker_company_phone'];
} else {
    $broker_company_phone = "";
}

if (isset($_POST['broker_company_email'])) {
    $broker_company_email = $_POST['broker_company_email'];
} elseif (isset($_GET['broker_company_email'])) {
    $broker_company_email = $_GET['broker_company_email'];
} else {
    $broker_company_email = "";
}

if (isset($_POST['broker_company_fax'])) {
    $broker_company_fax = $_POST['broker_company_fax'];
} elseif (isset($_GET['broker_company_fax'])) {
    $broker_company_fax = $_GET['broker_company_fax'];
} else {
    $broker_company_fax= "";
}

if (isset($_POST['broker_company_undertake'])) {
    $broker_company_undertake = $_POST['broker_company_undertake'];
} elseif (isset($_GET['broker_company_undertake'])) {
    $broker_company_undertake = $_GET['broker_company_undertake'];
} else {
    $broker_company_undertake= "";
}

if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
}

if (isset($_POST['house_id'])) {
    $house_id = $_POST['house_id'];
} elseif (isset($_GET['house_id'])) {
    $house_id = $_GET['house_id'];
} else {
    $house_id = "";
}

if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];
} elseif (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
} else {
    $room_id = "";
}

if (isset($_POST['house_description'])) {
    $house_description = $_POST['house_description'];
} elseif (isset($_GET['house_description'])) {
    $house_description = $_GET['house_description'];
} else {
    $house_description = "";
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


if (isset($_POST['submit'])) {

    $broker = new HOMEBroker();
    $result = $broker->assign($broker_id, $house_id,$room_id);
    if ($result) {
        $notify = "Add success !!!";
    }else{
        $error[]="This ".$room_id." room is added into ".$broker_company_name;
    }
} elseif ($content[0] == 'assign') {
    $broker = new HOMEBroker();

    $result = $broker->getBrokerById($content[1]);
    if (!empty($result)) {
        $broker_company_name = $result['broker_company_name'];
        $broker_company_address = $result['broker_company_address'];
        $broker_company_phone = $result['broker_company_phone'];
        $broker_company_email = $result['broker_company_email'];
        $broker_company_fax = $result['broker_company_fax'];
        $broker_company_undertake = $result['broker_company_undertake'];
        $broker_id = $result['id'];
    }
}
//get house
$houseClass = new HOMEHouse();
$houses = $houseClass->getHouses();

if(empty($houses))
    $error="All house are added. If you want to re_add, please go to edit room!!!";

$smarty->assign('houses', $houses);
$smarty->assign('house_id', $house_id);
$smarty->assign('room_id', $room_id);
$smarty->assign('house_description',$house_description);
$smarty->assign('broker_company_name', $broker_company_name);
$smarty->assign('broker_company_address', $broker_company_address);
$smarty->assign('broker_company_phone', $broker_company_phone);
$smarty->assign('broker_company_email', $broker_company_email);
$smarty->assign('broker_company_fax', $broker_company_fax);
$smarty->assign('broker_company_undertake', $broker_company_undertake);
$smarty->assign('broker_id', $broker_id);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
