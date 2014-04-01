<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_house";
$error = null;
$result = FALSE;
$notify="";

//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
//var_dump($user);die();
if ($user->user_info['user_authorities'] > 2) {
    header('Location: ./restrict.php');
    exit();
}

if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['house_name'])) {
    $house_name = $_POST['house_name'];
} elseif (isset($_GET['house_name'])) {
    $house_name = $_GET['house_name'];
} else {
    $house_name = "";
}

if (isset($_POST['house_address'])) {
    $house_address = $_POST['house_address'];
} elseif (isset($_GET['house_address'])) {
    $house_address = $_GET['house_address'];
} else {
    $house_address = "";
}

if (isset($_POST['house_size'])) {
    $house_size = $_POST['house_size'];
} elseif (isset($_GET['house_size'])) {
    $house_size = $_GET['house_size'];
} else {
    $house_size = "";
}

if (isset($_POST['house_area'])) {
    $house_area = $_POST['house_area'];
} elseif (isset($_GET['house_area'])) {
    $house_area = $_GET['house_area'];
} else {
    $house_area = "";
}

if (isset($_POST['house_original_price'])) {
    $house_original_price = $_POST['house_original_price'];
} elseif (isset($_GET['house_original_price'])) {
    $house_original_price = $_GET['house_original_price'];
} else {
    $house_original_price = "";
}

if (isset($_POST['house_build_time'])) {
    $house_build_time = $_POST['house_build_time'];
} elseif (isset($_GET['house_build_time'])) {
    $house_build_time = $_GET['house_build_time'];
} else {
    $house_build_time = "";
}

if (isset($_POST['house_type'])) {
    $house_type = $_POST['house_type'];
} elseif (isset($_GET['house_type'])) {
    $house_type = $_GET['house_type'];
} else {
    $house_type = "";
}

if (isset($_POST['house_description'])) {
    $house_description = $_POST['house_description'];
} elseif (isset($_GET['house_description'])) {
    $house_description = $_GET['house_description'];
} else {
    $house_description = "";
}

if (isset($_POST['house_room_type'])) {
    $house_room_type = $_POST['house_room_type'];
} elseif (isset($_GET['house_room_type'])) {
    $house_room_type = $_GET['house_room_type'];
} else {
    $house_room_type = "";
}

if (isset($_POST['house_administrative_expense'])) {
    $house_administrative_expense = $_POST['house_administrative_expense'];
} elseif (isset($_GET['house_administrative_expense'])) {
    $house_administrative_expense = $_GET['house_administrative_expense'];
} else {
    $house_administrative_expense = "";
}

if (isset($_POST['house_discount'])) {
    $house_discount = $_POST['house_discount'];
} elseif (isset($_GET['house_discount'])) {
    $house_discount = $_GET['house_discount'];
} else {
    $house_discount = "";
}

if (isset($_POST['house_structure'])) {
    $house_structure = $_POST['house_structure'];
} elseif (isset($_GET['house_structure'])) {
    $house_structure = $_GET['house_structure'];
} else {
    $house_structure = "";
}
/////////////////////////////////////////////////////////////////////// Owner
if (isset($_POST['house_owner_name'])) {
    $house_owner_name = $_POST['house_owner_name'];
} elseif (isset($_GET['house_owner_name'])) {
    $house_owner_name = $_GET['house_owner_name'];
} else {
    $house_owner_name = "";
}

if (isset($_POST['house_owner_address'])) {
    $house_owner_address = $_POST['house_owner_address'];
} elseif (isset($_GET['house_owner_address'])) {
    $house_owner_address = $_GET['house_owner_address'];
} else {
    $house_owner_address = "";
}

if (isset($_POST['house_owner_phone'])) {
    $house_owner_phone = $_POST['house_owner_phone'];
} elseif (isset($_GET['house_owner_phone'])) {
    $house_owner_phone = $_GET['house_owner_phone'];
} else {
    $house_owner_phone = "";
}

if (isset($_POST['house_owner_fax'])) {
    $house_owner_fax = $_POST['house_owner_fax'];
} elseif (isset($_GET['house_owner_fax'])) {
    $house_owner_fax = $_GET['house_owner_fax'];
} else {
    $house_owner_fax = "";
}

if (isset($_POST['house_owner_email'])) {
    $house_owner_email = $_POST['house_owner_email'];
} elseif (isset($_GET['house_owner_email'])) {
    $house_owner_email = $_GET['house_owner_email'];
} else {
    $house_owner_email = "";
}

if (isset($_POST['house_id'])) {
    $house_id = $_POST['house_id'];
} elseif (isset($_GET['house_id'])) {
    $house_id = $_GET['house_id'];
} else {
    $house_id = "";
}
if (isset($_POST['owner_id'])) {
    $owner_id = $_POST['owner_id'];
} elseif (isset($_GET['owner_id'])) {
    $owner_id = $_GET['owner_id'];
} else {
    $owner_id = "";
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
    'house_name' => $house_name,
    'house_address' => $house_address,
    'house_size' => $house_size,
    'house_area' => $house_area,
    'house_original_price' => $house_original_price,
    'house_owner_name'=>$house_owner_name    
);

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $house = new HOMEHouse();
        $result = $house->update(
                $house_name, 
                $house_address,
                $house_size, 
                $house_area,
                $house_original_price,
                $house_build_time,
                $house_type,
                $house_description,
                $house_room_type,
                $house_administrative_expense,
                $house_discount,
                $house_structure,
                $house_owner_name,
                $house_owner_address,
                $house_owner_phone,
                $house_owner_fax,
                $house_owner_email,
                $house_id,
                $owner_id
                
                );        
        if ($result) {
            $notify="Update success !!!";
        }
    }
} elseif ($content[0] == 'edit') {
    $house = new HOMEHouse();

    $result = $house->getHouseById($content[1]);
    if (!empty($result)) {        
        $house_name = $result['house_name'];
        $house_address = $result['house_address'];
        $house_size = $result['house_size'];
        $house_area = $result['house_area'];
        $house_original_price = $result['house_original_price'];
        $house_build_time = $result['house_build_time'];
        $house_type = $result['house_type'];
        $house_description = $result['house_description'];
        $house_room_type = $result['house_room_type'];
        $house_administrative_expense = $result['house_administrative_expense'];
        $house_discount = $result['house_discount'];
        $house_structure = $result['house_structure'];
        $house_owner_name = $result['house_owner_name'];
        $house_owner_address = $result['house_owner_address'];
        $house_owner_phone = $result['house_owner_phone'];
        $house_owner_fax = $result['house_owner_fax'];
        $house_owner_email = $result['house_owner_email'];
        $house_id = $result['id'];
        $owner_id=$result['owner_id'];
    }
}
$smarty->assign('house_id', $house_id);
$smarty->assign('owner_id', $owner_id);
$smarty->assign('house_name', $house_name);
$smarty->assign('house_address', $house_address);
$smarty->assign('house_size', $house_size);
$smarty->assign('house_area', $house_area);
$smarty->assign('house_original_price', $house_original_price);
$smarty->assign('house_build_time', $house_build_time);
$smarty->assign('house_type', $house_type);
$smarty->assign('house_description', $house_description);
$smarty->assign('house_room_type', $house_room_type);
$smarty->assign('house_administrative_expense', $house_administrative_expense);
$smarty->assign('house_discount', $house_discount);
$smarty->assign('house_structure', $house_structure);
$smarty->assign('house_owner_name', $house_owner_name);
$smarty->assign('house_owner_address', $house_owner_address);
$smarty->assign('house_owner_phone', $house_owner_phone);
$smarty->assign('house_owner_fax', $house_owner_fax);
$smarty->assign('house_owner_email', $house_owner_email);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
