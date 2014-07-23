<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "create_room";
$error = null;
$result = FALSE;

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['room_number'])) {
    $room_number = $_POST['room_number'];
} elseif (isset($_GET['room_number'])) {
    $room_number = $_GET['room_number'];
} else {
    $room_number = "";
}

if (isset($_POST['room_type'])) {
    $room_type = $_POST['room_type'];
} elseif (isset($_GET['room_type'])) {
    $room_type = $_GET['room_type'];
} else {
    $room_type = "";
}

if (isset($_POST['room_type_number'])) {
    $room_type_number = $_POST['room_type_number'];
} elseif (isset($_GET['room_type_number'])) {
    $room_type_number = $_GET['room_type_number'];
} else {
    $room_type_number = "";
}

if (isset($_POST['room_size'])) {
    $room_size = $_POST['room_size'];
} elseif (isset($_GET['room_size'])) {
    $room_size = $_GET['room_size'];
} else {
    $room_size = "";
}

if (isset($_POST['room_discount'])) {
    $room_discount = $_POST['room_discount'];
} elseif (isset($_GET['room_discount'])) {
    $room_discount = $_GET['room_discount'];
} else {
    $room_discount = "";
}

if (isset($_POST['room_status'])) {
    $room_status = $_POST['room_status'];
} elseif (isset($_GET['room_status'])) {
    $room_status = $_GET['room_status'];
} else {
    $room_status = "";
}


if (isset($_POST['room_rent'])) {
    $room_rent = $_POST['room_rent'];
} elseif (isset($_GET['room_rent'])) {
    $room_rent = $_GET['room_rent'];
} else {
    $room_rent = "";
}

if (isset($_POST['room_key_money'])) {
    $room_key_money = $_POST['room_key_money'];
} elseif (isset($_GET['room_key_money'])) {
    $room_key_money = $_GET['room_key_money'];
} else {
    $room_key_money = "";
}
if (isset($_POST['room_key_money_unit'])) {
    $room_key_money_unit = $_POST['room_key_money_unit'];
} elseif (isset($_GET['room_key_money_unit'])) {
    $room_key_money_unit = $_GET['room_key_money_unit'];
} else {
    $room_key_money_unit = "";
}

//$room_key_money=$room_key_money.$room_key_money_unit;

if (isset($_POST['room_administrative_expense'])) {
    $room_administrative_expense = $_POST['room_administrative_expense'];
} elseif (isset($_GET['room_administrative_expense'])) {
    $room_administrative_expense = $_GET['room_administrative_expense'];
} else {
    $room_administrative_expense = "";
}
if (isset($_POST['room_administrative_expense_unit'])) {
    $room_administrative_expense_unit = $_POST['room_administrative_expense_unit'];
} elseif (isset($_GET['room_administrative_expense_unit'])) {
    $room_administrative_expense_unit = $_GET['room_administrative_expense_unit'];
} else {
    $room_administrative_expense_unit = "";
}

if (isset($_POST['room_deposit'])) {
    $room_deposit = $_POST['room_deposit'];
} elseif (isset($_GET['room_deposit'])) {
    $room_deposit = $_GET['room_deposit'];
} else {
    $room_deposit = "";
}
if (isset($_POST['room_deposit_unit'])) {
    $room_deposit_unit = $_POST['room_deposit_unit'];
} elseif (isset($_GET['room_deposit_unit'])) {
    $room_deposit_unit = $_GET['room_deposit_unit'];
} else {
    $room_deposit_unit = "";
}
//$room_deposit=$room_deposit.$room_deposit_unit;

if (isset($_FILES['room_photo']['name'])) {
    $room_photo = $_FILES['room_photo']['name'];
} elseif (isset($_FILES['room_photo']['name'])) {
    $room_photo = $_FILES['room_photo']['name'];
} else {
    $room_photo = "";
}
/////////////////////////////////////////////////////////////////////// House
if (isset($_POST['house_id'])) {
    $house_id = $_POST['house_id'];
} elseif (isset($_GET['house_id'])) {
    $house_id = $_GET['house_id'];
} else {
    $house_id = "";
}
/////////////////////////////////////////////////////////////////////////Broker
if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
}


$validate = array(
    'room_number' => $room_number,
    'room_type' => $room_type,
    'room_size' => $room_size,
    'room_rent' => $room_rent,
    'house_id' => $house_id,
    'broker_id' => $broker_id
);
$house = new HOMEHouse();

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {        
        $result = $house->create_room(
                $room_number,$room_type,$room_type_number,$room_size,$room_status,$room_rent,$room_key_money.$room_key_money_unit,$room_administrative_expense.$room_administrative_expense_unit,$room_deposit.$room_deposit_unit,$room_discount,$room_photo,$house_id,$broker_id
        );
        if ($result['flag']) {
            header("Location: notify.php?content=部屋情報～は成功に作成されました。!!!&url_return=create_room.php");
        }elseif($result['error']){
            $error[]=$result['error'];            
        }else{
            $error[]="Create fail. Please try again!!!";
        }
    }
}
//get houses
$houses = $house->getAllHouses();
//get brokers
$brokerClass = new HOMEBroker();
$brokers = $brokerClass->getAllBroker();
//get room type
$roomTypes=$house->getRoomType();

$smarty->assign('room_number', $room_number);
$smarty->assign('room_type', $room_type);
$smarty->assign('room_type_number', $room_type_number);
$smarty->assign('room_size', $room_size);
$smarty->assign('room_discount', $room_discount);
$smarty->assign('room_status', $room_status);
$smarty->assign('room_rent', $room_rent);
$smarty->assign('room_key_money', $room_key_money);
$smarty->assign('room_key_money_unit', $room_key_money_unit);
$smarty->assign('room_administrative_expense', $room_administrative_expense);
$smarty->assign('room_administrative_expense_unit', $room_administrative_expense_unit);
$smarty->assign('room_deposit', $room_deposit);
$smarty->assign('room_deposit_unit', $room_deposit_unit);
$smarty->assign('room_photo', $room_photo);
$smarty->assign('house_id', $house_id);
$smarty->assign('broker_id', $broker_id);
$smarty->assign('houses', $houses);
$smarty->assign('brokers', $brokers);
$smarty->assign('roomTypes', $roomTypes);

$smarty->assign('error', $error);

include 'footer.php';
