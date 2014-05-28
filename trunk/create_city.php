<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_city';

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

if (isset($_POST['city_name'])) {
    $city_name = $_POST['city_name'];
} elseif (isset($_GET['city_name'])) {
    $city_name = $_GET['city_name'];
} else {
    $city_name = "";
}

$validate = array(
    'city_name'=>$city_name   
);

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){
        $house=new HOMEHouse();
        $result=$house->create_city($city_name);
        if ($result['error']) {
            $error[]=$result['error'];
        }elseif($result['flag']){
            header("Location: notify.php?content=Create City Success!!!&url_return=create_city.php");
        }else
            $error[]="Create city fail, maybe error of connect database !!!";
    }
}

$smarty->assign('city_name', $city_name);
$smarty->assign('error', $error);

include "footer.php";

