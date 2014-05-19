<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_source';

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

if (isset($_POST['source_name'])) {
    $source_name = $_POST['source_name'];
} elseif (isset($_GET['source_name'])) {
    $source_name = $_GET['source_name'];
} else {
    $source_name = "";
}

$validate = array(
    'source_name'=>$source_name   
);

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){
        $house=new HOMEHouse();
        $result=$house->create_source($source_name);
        if ($result['error']) {
            $error[]=$result['error'];
        }elseif($result['flag']){
            header("Location: notify.php?content=Create Source Success!!!&url_return=create_source.php");
        }else
            $error[]="Create source fail, maybe error of connect database !!!";
    }
}

$smarty->assign('source_name', $source_name);
$smarty->assign('error', $error);

include "footer.php";

