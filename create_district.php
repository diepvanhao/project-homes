<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_district';

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

if (isset($_POST['city_id'])) {
    $city_id = $_POST['city_id'];
} elseif (isset($_GET['city_id'])) {
    $city_id = $_GET['city_id'];
} else {
    $city_id = "";
}

if (isset($_POST['district_name'])) {
    $district_name = $_POST['district_name'];
} elseif (isset($_GET['district_name'])) {
    $district_name = $_GET['district_name'];
} else {
    $district_name = "";
}

$validate = array(
    'district_name'=>$district_name,
    'city_id'=>$city_id
);
$house=new HOMEHouse();

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){        
        $result=$house->create_district($city_id,$district_name);
        if ($result['error']) {
            $error[]=$result['error'];
        }elseif($result['flag']){
            header("Location: notify.php?content=Create District Success!!!&url_return=create_district.php");
        }else
            $error[]="Create city fail, maybe error of connect database !!!";
    }
}
//get cities
$cities=$house->getAllCity();

$smarty->assign('district_name', $district_name);
$smarty->assign('city_id',$city_id);
$smarty->assign('cities',$cities);
$smarty->assign('error', $error);

include "footer.php";

