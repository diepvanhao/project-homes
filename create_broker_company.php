<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_broker_company';

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

if (isset($_POST['broker_company_name'])) {
    $broker_company_name = $_POST['broker_company_name'];
} elseif (isset($_GET['broker_company_name'])) {
    $broker_company_name = $_GET['broker_company_name'];
} else {
    $broker_company_name = "";
}

if (isset($_POST['broker_company_address'])) {
    $broker_company_address = $_POST['broker_company_address'];
} elseif (isset($_GET['broker_company_address'])) {
    $broker_company_address = $_GET['broker_company_address'];
} else {
    $broker_company_address = "";
}

if (isset($_POST['broker_company_phone'])) {
    $broker_company_phone = $_POST['broker_company_phone'];
} elseif (isset($_GET['broker_company_phone'])) {
    $broker_company_phone= $_GET['broker_company_phone'];
} else {
    $broker_company_phone = "";
}

if (isset($_POST['broker_company_email'])) {
    $broker_company_email = $_POST['broker_company_email'];
} elseif (isset($_GET['broker_company_email'])) {
    $broker_company_email = $_GET['broker_company_email'];
} else {
    $broker_company_email = "";
}

if (isset($_POST['broker_company_fax'])) {
    $broker_company_fax = $_POST['broker_company_fax'];
} elseif (isset($_GET['broker_company_fax'])) {
    $broker_company_fax = $_GET['broker_company_fax'];
} else {
    $broker_company_fax= "";
}

if (isset($_POST['broker_company_undertake'])) {
    $broker_company_undertake = $_POST['broker_company_undertake'];
} elseif (isset($_GET['broker_company_undertake'])) {
    $broker_company_undertake = $_GET['broker_company_undertake'];
} else {
    $broker_company_undertake= "";
}

$validate = array(
    'broker_company_name'=>$broker_company_name,
    'broker_company_address' => $broker_company_address,
    'broker_company_phone' => $broker_company_phone,
    'broker_company_email' => array('broker_company_email'=>$broker_company_email) 
);

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){
        $broker=new HOMEBroker();
        $result=$broker->create($broker_company_name, $broker_company_address, $broker_company_phone, $broker_company_email, $broker_company_fax,$broker_company_undertake);
        if ($result) {
            header("Location: notify.php?content=Create Broker Company Success!!!&url_return=create_broker_company.php");
        }
    }
}


$smarty->assign('broker_company_name', $broker_company_name);
$smarty->assign('broker_company_address', $broker_company_address);
$smarty->assign('broker_company_phone', $broker_company_phone);
$smarty->assign('broker_company_email', $broker_company_email);
$smarty->assign('broker_company_fax', $broker_company_fax);
$smarty->assign('broker_company_undertake', $broker_company_undertake);
$smarty->assign('error', $error);

include "footer.php";

