<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "create_house";
$error = null;
$result = FALSE;

if (!$user->user_exists) {

    header('Location: ./user_login.php');

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

if (isset($_POST['owner'])) {
    $owner = 1;
} elseif (isset($_GET['owner'])) {
    $owner = 1;
} else {
    $owner = "";
}

$validate = array(
    'house_name' => $house_name,
    'house_address' => $house_address,
    'house_size' => $house_size,
    'house_area' => $house_area    
);

if($owner)
    $validate['house_owner_name']=$house_owner_name;

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $house = new HOMEHouse();
        $result = $house->create(
                $house_name, 
                $house_address,
                $house_size, 
                $house_area,               
                $house_build_time,
                $house_type,
                $house_description,
                $house_discount,
                $house_structure,
                $house_owner_name,
                $house_owner_address,
                $house_owner_phone,
                $house_owner_fax,
                $house_owner_email
                
                );
        if ($result) {
            header("Location: notify.php?content=Create House Success!!!&url_return=create_house.php");
        }
    }
}


$smarty->assign('house_name', $house_name);
$smarty->assign('house_address', $house_address);
$smarty->assign('house_size', $house_size);
$smarty->assign('house_area', $house_area);
$smarty->assign('house_build_time', $house_build_time);
$smarty->assign('house_type', $house_type);
$smarty->assign('house_description', $house_description);
$smarty->assign('house_discount', $house_discount);
$smarty->assign('house_structure', $house_structure);
$smarty->assign('house_owner_name', $house_owner_name);
$smarty->assign('house_owner_address', $house_owner_address);
$smarty->assign('house_owner_phone', $house_owner_phone);
$smarty->assign('house_owner_fax', $house_owner_fax);
$smarty->assign('house_owner_email', $house_owner_email);
$smarty->assign('owner', $owner);

$smarty->assign('error', $error);

include 'footer.php';
