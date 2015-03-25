<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_group';

$error = null;
$result = FALSE;

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('create-group')) {
    header('Location: ./restrict.php');
    exit();
}
if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['group_name'])) {
    $group_name = $_POST['group_name'];
} elseif (isset($_GET['group_name'])) {
    $group_name = $_GET['group_name'];
} else {
    $group_name = "";
}

if (isset($_POST['display'])) {
    $display = $_POST['display'];
} elseif (isset($_GET['display'])) {
    $display = $_GET['display'];
} else {
    $display = "0";
}

$validate = array(
    'group_name'=>$group_name   
);

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){
        $house=new HOMEHouse();
        $result=$house->create_group($group_name,$display);
        if ($result['error']) {
            $error[]=$result['error'];
        }elseif($result['flag']){
            header("Location: notify.php?content=媒体～は成功に作成されました。!!!&url_return=create_group.php");
        }else
            $error[]="Create group fail, maybe error of connect database !!!";
    }
}

$smarty->assign('group_name', $group_name);
$smarty->assign('error', $error);

include "footer.php";

