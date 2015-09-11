<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page = "general_calendar";
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
//schedule data
if (isset($_POST['signature_day'])) {
    $signature_day = $_POST['signature_day'];
} elseif (isset($_GET['signature_day'])) {
    $signature_day = $_GET['signature_day'];
} else {
    $signature_day = "";
}
if (isset($_POST['handover_day'])) {
    $handover_day = $_POST['handover_day'];
} elseif (isset($_GET['handover_day'])) {
    $handover_day= $_GET['handover_day'];
} else {
    $handover_day = "";
}
if (isset($_POST['payment_day'])) {
    $payment_day = $_POST['payment_day'];
} elseif (isset($_GET['payment_day'])) {
    $payment_day = $_GET['payment_day'];
} else {
    $payment_day = "";
}
if (isset($_POST['appointment_day'])) {
    $appointment_day = $_POST['appointment_day'];
} elseif (isset($_GET['appointment_day'])) {
    $appointment_day = $_GET['appointment_day'];
} else {
    $appointment_day = "";
}
if (isset($_POST['other'])) {
    $other = $_POST['other'];
} elseif (isset($_GET['other'])) {
    $other = $_GET['other'];
} else {
    $other = "";
}
if (isset($_POST['period'])) {
    $period = $_POST['period'];
} elseif (isset($_GET['period'])) {
    $period = $_GET['period'];
} else {
    $period = "";
}
if (isset($_POST['birthday'])) {
    $birthday = $_POST['birthday'];
} elseif (isset($_GET['birthday'])) {
    $birthday = $_GET['birthday'];
} else {
    $birthday = "";
}
if (isset($_POST['all_agent'])) {
    $all_agent = $_POST['all_agent'];
} elseif (isset($_GET['all_agent'])) {
    $all_agent = $_GET['all_agent'];
} else {
    $all_agent = "";
}
if (isset($_POST['agent_id'])) {
    $agent_id = $_POST['agent_id'];
} elseif (isset($_GET['agent_id'])) {
    $agent_id = $_GET['agent_id'];
} else {
    $agent_id = $user->user_info['agent_id'];
}
if (isset($_POST['position'])) {
    $position = $_POST['position'];
} elseif (isset($_GET['position'])) {
    $position = $_GET['position'];
} else {
    $position = "";
}
if (isset($_POST['assign_id'])) {
    $assign_id = $_POST['assign_id'];
} elseif (isset($_GET['assign_id'])) {
    $assign_id = $_GET['assign_id'];
} else {
    $assign_id = "";
}
if (isset($_POST['date_from'])) {
    $date_from = $_POST['date_from'];
} elseif (isset($_GET['date_from'])) {
    $date_from = $_GET['date_from'];
} else {
    $date_from = date('Y/m/d',time());
}
if (isset($_POST['date_to'])) {
    $date_to = $_POST['date_to'];
} elseif (isset($_GET['date_to'])) {
    $date_to = $_GET['date_to'];
} else {
    $date_to = "";
}

$event=new HOMEEvent();
$events=$event->getEvents(
        $signature_day,$handover_day,$payment_day,$appointment_day,
        $other,$period,$birthday,$all_agent,$agent_id,
        $assign_id,$position,$date_from,$date_to
        );

$agent = new HOMEAgent();
$agents = $agent->getAllAgent();
$staff=new HOMEUser();
$staffs=$staff->getAllUsers(true);
$smarty->assign('agents', $agents);
$smarty->assign('staffs',$staffs);
$smarty->assign('events',$events);
$smarty->assign('date_from',$date_from);
$smarty->assign('agent_id',$agent_id);
include "footer.php";
