<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_street';

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

if (isset($_POST['district_id'])) {
    $district_id = $_POST['district_id'];
} elseif (isset($_GET['district_id'])) {
    $district_id = $_GET['district_id'];
} else {
    $district_id = "";
}

if (isset($_POST['street_name'])) {
    $street_name = $_POST['street_name'];
} elseif (isset($_GET['street_name'])) {
    $street_name = $_GET['street_name'];
} else {
    $street_name = "";
}

$validate = array(
    'street_name'=>$street_name,
    'district_id'=>$district_id
);
$house=new HOMEHouse();

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){        
        $result=$house->create_street($district_id,$street_name);
        if ($result['error']) {
            $error[]=$result['error'];
        }elseif($result['flag']){
            header("Location: notify.php?content=大字・通称～は成功に作成されました。!!!&url_return=create_street.php");
        }else
            $error[]="Create street fail, maybe error of connect database !!!";
    }
}
//get cities
$districts=$house->getAllDistrict();

$smarty->assign('street_name', $street_name);
$smarty->assign('district_id',$district_id);
$smarty->assign('districts',$districts);
$smarty->assign('error', $error);

include "footer.php";

