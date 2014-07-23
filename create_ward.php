<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page = 'create_ward';

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

if (isset($_POST['street_id'])) {
    $street_id = $_POST['street_id'];
} elseif (isset($_GET['street_id'])) {
    $street_id = $_GET['street_id'];
} else {
    $street_id = "";
}

if (isset($_POST['ward_name'])) {
    $ward_name = $_POST['ward_name'];
} elseif (isset($_GET['ward_name'])) {
    $ward_name = $_GET['ward_name'];
} else {
    $ward_name = "";
}

$validate = array(
    'ward_name' => $ward_name,
    'street_id' => $street_id
);
$house = new HOMEHouse();

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $result = $house->create_ward($street_id, $ward_name);
        if ($result['error']) {
            $error[] = $result['error'];
        } elseif ($result['flag']) {
            header("Location: notify.php?content=字・丁目～は成功に作成されました。!!!&url_return=create_ward.php");
        } else
            $error[] = "Create ward fail, maybe error of connect database !!!";
    }
}
//get cities
$streets = $house->getAllStreet();

$smarty->assign('ward_name', $ward_name);
$smarty->assign('street_id', $street_id);
$smarty->assign('streets', $streets);
$smarty->assign('error', $error);

include "footer.php";

