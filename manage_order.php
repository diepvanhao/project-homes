<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "manage_order";
 //date_default_timezone_set("Asia/Bangkok");
//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('manage-order')) {
    header('Location: ./restrict.php');
    exit();
}

if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}
////////////for search advance/////////////////////////////////
if (isset($_POST['order_name'])) {
    $order_name = $_POST['order_name'];
} elseif (isset($_GET['order_name'])) {
    $order_name = $_GET['order_name'];
} else {
    $order_name = "";
}
if (isset($_POST['house_name'])) {
    $house_name = $_POST['house_name'];
} elseif (isset($_GET['house_name'])) {
    $house_name = $_GET['house_name'];
} else {
    $house_name = "";
}
if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];
} elseif (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
} else {
    $room_id = "";
}
if (isset($_POST['order_rent_cost'])) {
    $order_rent_cost = $_POST['order_rent_cost'];
} elseif (isset($_GET['order_rent_cost'])) {
    $order_rent_cost = $_GET['order_rent_cost'];
} else {
    $order_rent_cost = "";
}
if (isset($_POST['order_status'])) {
    $order_status = $_POST['order_status'];
} elseif (isset($_GET['order_status'])) {
    $order_status = $_GET['order_status'];
} else {
    $order_status = "";
}
if (isset($_POST['order_day_create'])) {
    $order_day_create = $_POST['order_day_create'];
} elseif (isset($_GET['order_day_create'])) {
    $order_day_create = $_GET['order_day_create'];
} else {
    $order_day_create = "";
}
if (isset($_POST['client_name'])) {
    $client_name = $_POST['client_name'];
} elseif (isset($_GET['client_name'])) {
    $client_name = $_GET['client_name'];
} else {
    $client_name = "";
}
if (isset($_POST['log_revisit'])) {
    $log_revisit = $_POST['log_revisit'];
} elseif (isset($_GET['log_revisit'])) {
    $log_revisit = $_GET['log_revisit'];
} else {
    $log_revisit = "";
}
if (isset($_POST['contract_application_date'])) {
    $contract_application_date = $_POST['contract_application_date'];
} elseif (isset($_GET['contract_application_date'])) {
    $contract_application_date = $_GET['contract_application_date'];
} else {
    $contract_application_date = "";
}
if (isset($_POST['money_payment'])) {
    $money_payment = $_POST['money_payment'];
} elseif (isset($_GET['money_payment'])) {
    $money_payment = $_GET['money_payment'];
} else {
    $money_payment = "";
}
if (isset($_POST['contract_signature_day'])) {
    $contract_signature_day = $_POST['contract_signature_day'];
} elseif (isset($_GET['contract_signature_day'])) {
    $contract_signature_day = $_GET['contract_signature_day'];
} else {
    $contract_signature_day = "";
}
if (isset($_POST['contract_payment_date_from'])) {
    $contract_payment_date_from = $_POST['contract_payment_date_from'];
} elseif (isset($_GET['contract_payment_date_from'])) {
    $contract_payment_date_from = $_GET['contract_payment_date_from'];
} else {
    $contract_payment_date_from = "";
}
if (isset($_POST['contract_payment_date_to'])) {
    $contract_payment_date_to = $_POST['contract_payment_date_to'];
} elseif (isset($_GET['contract_payment_date_to'])) {
    $contract_payment_date_to = $_GET['contract_payment_date_to'];
} else {
    $contract_payment_date_to = "";
}
if (isset($_POST['contract_handover_day'])) {
    $contract_handover_day = $_POST['contract_handover_day'];
} elseif (isset($_GET['contract_handover_day'])) {
    $contract_handover_day = $_GET['contract_handover_day'];
} else {
    $contract_handover_day = "";
}


///////////end search advance/////////////////////////////////
if (isset($_POST['page_number'])) {
    $page_number = $_POST['page_number'];
} elseif (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}
$smarty->assign('order_name', $order_name);
$smarty->assign('house_name', $house_name);
$smarty->assign('room_id', $room_id);
$smarty->assign('order_rent_cost', $order_rent_cost);
$smarty->assign('order_status', $order_status);
$smarty->assign('order_day_create', $order_day_create);
$smarty->assign('client_name', $client_name);
$smarty->assign('log_revisit', $log_revisit);
$smarty->assign('contract_application_date', $contract_application_date);
$smarty->assign('money_payment', $money_payment);
$smarty->assign('contract_signature_day', $contract_signature_day);
$smarty->assign('contract_payment_date_from', $contract_payment_date_from);
$smarty->assign('contract_payment_date_to', $contract_payment_date_to);
$smarty->assign('contract_handover_day', $contract_handover_day);
$search=array(
    'order_name'=>$order_name,
    'house_name'=>$house_name,
    'room_id'=>$room_id,
    'order_rent_cost'=>$order_rent_cost,
    'order_status'=>$order_status,
    'order_day_create'=>$order_day_create,
    'client_name'=>$client_name,
    'log_revisit'=>$log_revisit,
    'contract_application_date'=>$contract_application_date,
    'money_payment'=>$money_payment,
    'contract_signature_day'=>$contract_signature_day,
    'contract_payment_date_from'=>$contract_payment_date_from,
    'contract_payment_date_to'=>$contract_payment_date_to,
    'contract_handover_day'=>$contract_handover_day
);

$orderClass = new HOMEOrder();
//calculator paging
$max = 25;
$totalItem = $orderClass->getTotalItem($search);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;


$offset = $page_number * $max - $max;
$length = $max;

$orders = $orderClass->getOrder($search, $offset, $length);
//var_dump($orders);
//get user
//$user=new HOMEUser();
//$users=$user->getAllUsers();
//get id user current
$user_id=$user->user_info['id'];

$smarty->assign('order_name', $order_name);
$smarty->assign('house_name', $house_name);
$smarty->assign('room_id', $room_id);
$smarty->assign('order_rent_cost', $order_rent_cost);
$smarty->assign('order_status', $order_status);
$smarty->assign('order_day_create', $order_day_create);
$smarty->assign('client_name', $client_name);
$smarty->assign('log_revisit', $log_revisit);
$smarty->assign('contract_application_date', $contract_application_date);
$smarty->assign('money_payment', $money_payment);
$smarty->assign('contract_signature_day', $contract_signature_day);
$smarty->assign('contract_payment_date_from', $contract_payment_date_from);
$smarty->assign('contract_payment_date_to', $contract_payment_date_to);
$smarty->assign('contract_handover_day', $contract_handover_day);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('orders', $orders);
$smarty->assign('house', new HOMEHouse());
//$smarty->assign('users',$users);   
$smarty->assign('user_id',$user_id);
$smarty->assign('canEdit', @HOMEOrder::checkPermisson('create-order'));
include "footer.php";
