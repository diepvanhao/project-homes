<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page='create_agent';

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

if (isset($_POST['agent_name'])) {
    $agent_name = $_POST['agent_name'];
} elseif (isset($_GET['agent_name'])) {
    $agent_name = $_GET['agent_name'];
} else {
    $agent_name = "";
}

if (isset($_POST['agent_email'])) {
    $agent_email = $_POST['agent_email'];
} elseif (isset($_GET['agent_email'])) {
    $agent_email = $_GET['agent_email'];
} else {
    $agent_email = "";
}

if (isset($_POST['agent_address'])) {
    $agent_address = $_POST['agent_address'];
} elseif (isset($_GET['agent_address'])) {
    $agent_address = $_GET['agent_address'];
} else {
    $agent_address = "";
}

if (isset($_POST['agent_phone'])) {
    $agent_phone = $_POST['agent_phone'];
} elseif (isset($_GET['agent_phone'])) {
    $agent_phone = $_GET['agent_phone'];
} else {
    $agent_phone = "";
}

if (isset($_POST['agent_fax'])) {
    $agent_fax = $_POST['agent_fax'];
} elseif (isset($_GET['agent_fax'])) {
    $agent_fax = $_GET['agent_fax'];
} else {
    $agent_fax = "";
}

$validate = array(
    'agent_name'=>$agent_name,
    'agent_address' => $agent_address,
    'agent_phone' => $agent_phone,
    'agent_email' => array('agent_email'=>$agent_email) 
);

if(isset($_POST['submit'])){
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if(empty($error)){
        $agent=new HOMEAgent();
        $result=$agent->create($agent_name, $agent_email, $agent_address, $agent_phone, $agent_fax);
        if ($result) {
            header("Location: notify.php?content=Create Agent Success!!!&url_return=create_agent.php");
        }
    }
}



$smarty->assign('agent_name', $agent_name);
$smarty->assign('agent_email', $agent_email);
$smarty->assign('agent_address', $agent_address);
$smarty->assign('agent_phone', $agent_phone);
$smarty->assign('agent_fax', $agent_fax);
$smarty->assign('error', $error);

include "footer.php";

