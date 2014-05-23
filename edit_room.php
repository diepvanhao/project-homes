<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_room";
$error = null;
$result = FALSE;
$notify = "";

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

if (isset($_POST['room_administrative_expense'])) {
    $room_administrative_expense = $_POST['room_administrative_expense'];
} elseif (isset($_GET['room_administrative_expense'])) {
    $room_administrative_expense = $_GET['room_administrative_expense'];
} else {
    $room_administrative_expense = "";
}


if (isset($_POST['room_deposit'])) {
    $room_deposit = $_POST['room_deposit'];
} elseif (isset($_GET['room_deposit'])) {
    $room_deposit = $_GET['room_deposit'];
} else {
    $room_deposit = "";
}

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
////////////////////////////////////////////////////////////////////////Room
if (isset($_POST['room_detail_id'])) {
    $room_detail_id = $_POST['room_detail_id'];
} elseif (isset($_GET['room_detail_id'])) {
    $room_detail_id = $_GET['room_detail_id'];
} else {
    $room_detail_id = "";
}
//////////////////////////////////////////////////////////////////////Backup
if (isset($_POST['house_id_bk'])) {
    $house_id_bk = $_POST['house_id_bk'];
} elseif (isset($_GET['house_id_bk'])) {
    $house_id_bk = $_GET['house_id_bk'];
} else {
    $house_id_bk = "";
}
if (isset($_POST['broker_id_bk'])) {
    $broker_id_bk = $_POST['broker_id_bk'];
} elseif (isset($_GET['broker_id_bk'])) {
    $broker_id_bk = $_GET['broker_id_bk'];
} else {
    $broker_id_bk = "";
}

if (isset($_POST['room_number_bk'])) {
    $room_number_bk = $_POST['room_number_bk'];
} elseif (isset($_GET['room_number_bk'])) {
    $room_number_bk = $_GET['room_number_bk'];
} else {
    $room_number_bk = "";
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
        
        $result = $house->update_room(
                $room_number, $room_type, $room_size, $room_status, $room_rent, $room_key_money, $room_administrative_expense, $room_deposit, $room_discount, $room_photo, $house_id, $broker_id, $room_detail_id, $house_id_bk, $broker_id_bk,$room_number_bk
        );
        if ($result['flag']) {
            $notify = "Update success !!!";
            $house_id_bk = $house_id;
            $broker_id_bk = $broker_id;
            $room_number_bk=$room_number;
        } elseif ($result['error']) {
            $error[] = $result['error'];
        } else {
            $error[] = "Update fail. Please try again!!!";
        }
    }
} elseif ($content[0] == 'edit') {
    $room_detail_id = $content[1];

    $broker_id_bk = $broker_id = $content[2];
    $house_id_bk = $house_id = $content[3];

    
    $room = $house->getRoomById($room_detail_id, $broker_id, $house_id);

    if ($room) {
        $room_number_bk=$room_number = $room['room_number'];
        $room_type = $room['room_type'];
        $room_size = $room['room_size'];      
        $room_status = $room['room_status'];
        $room_rent = $room['room_rent'];
        $room_key_money = $room['room_key_money'];
        $room_administrative_expense = $room['room_administrative_expense'];
        $room_deposit = $room['room_deposit'];
        $room_discount = $room['room_discount'];
        $room_photo = $room['room_photo'];
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
$smarty->assign('room_size', $room_size);
$smarty->assign('room_discount', $room_discount);
$smarty->assign('room_status', $room_status);
$smarty->assign('room_rent', $room_rent);
$smarty->assign('room_key_money', $room_key_money);
$smarty->assign('room_administrative_expense', $room_administrative_expense);
$smarty->assign('room_deposit', $room_deposit);
$smarty->assign('room_photo', $room_photo);
$smarty->assign('house_id', $house_id);
$smarty->assign('broker_id', $broker_id);
$smarty->assign('room_detail_id', $room_detail_id);
$smarty->assign('house_id_bk', $house_id_bk);
$smarty->assign('broker_id_bk', $broker_id_bk);
$smarty->assign('room_number_bk', $room_number_bk);
$smarty->assign('roomTypes', $roomTypes);

$smarty->assign('houses', $houses);
$smarty->assign('brokers', $brokers);


$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include 'footer.php';
