<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "add_staff_agent";
$error = null;
$result = FALSE;
$notify = "";

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

if (isset($_POST['agent_id'])) {
    $agent_id = $_POST['agent_id'];
} elseif (isset($_GET['agent_id'])) {
    $agent_id = $_GET['agent_id'];
} else {
    $agent_id = "";
}

if (isset($_POST['staff_id'])) {
    $staff_id = $_POST['staff_id'];
} elseif (isset($_GET['staff_id'])) {
    $staff_id = $_GET['staff_id'];
} else {
    $staff_id = "";
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


if (isset($_POST['submit'])) {

    $agent = new HOMEAgent();
    $result = $agent->assign($agent_id, $staff_id);
    if ($result) {
        $notify = "Assign success !!!";
    }
} elseif ($content[0] == 'assign') {
    $agent = new HOMEAgent();

    $result = $agent->getAgentById($content[1]);
    if (!empty($result)) {
        $agent_name = $result['agent_name'];
        $agent_email = $result['agent_email'];
        $agent_address = $result['agent_address'];
        $agent_phone = $result['agent_phone'];
        $agent_fax = $result['agent_fax'];
        $agent_id = $result['id'];
    }
}
//get user
$users = $user->getUsers();

if(empty($users))
    $error="All staff are assigned. If you want to reassign, please go to edit staff !!!";


$smarty->assign('users', $users);
$smarty->assign('agent_name', $agent_name);
$smarty->assign('agent_email', $agent_email);
$smarty->assign('agent_address', $agent_address);
$smarty->assign('agent_phone', $agent_phone);
$smarty->assign('agent_fax', $agent_fax);
$smarty->assign('agent_id', $agent_id);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
