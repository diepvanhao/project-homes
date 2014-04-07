<?php

include "header.php";

$page = "edit_client";
$error = null;
$result = FALSE;

if (!$user->user_exists) {
    header('Location: ./user_login.php');

    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
if (isset($_POST['url'])) {
    $content = $_POST['url'];
} elseif (isset($_GET['url'])) {
    $content = $_GET['url'];
} else {
    $content = "";
}
$error = array();
$content = base64_decode($content);
$content = explode('&', $content);
$id = $content['1'];
if (empty($id)) {
    $error[] = 'Client Not found';
}
include 'include/class_client.php';
$client = new Client();
$item = $client->getClientId($id);
if(empty($item)){
    $error[] = 'Client Not found';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // do with post

    $data = $_POST;
    extract($_POST);

    if (empty($client_name)) {
        $error[] = 'Name is required';
    }
    if (empty($client_address)) {
        $error[] = 'Address is required';
    }
    if (empty($client_phone)) {
        $error[] = 'Phone is required';
    }
    if (!empty($client_income) && !filter_var($client_income, FILTER_VALIDATE_FLOAT)) {
        $error[] = 'Income must be a Float number';
    }
    if (!empty($client_rent) && !filter_var($client_rent, FILTER_VALIDATE_FLOAT)) {
        $error[] = 'Rent must be a Float number';
    }
    if (!empty($client_email) && !filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Email is invalid';
    }
    if (empty($error)) {

        $result = $client->update($id,$data);
        if ($result) {
            $notify="Update success !!!";
        }
    }
    @$smarty->assign('data', $data);
} else {
    //do with Edit
    @$smarty->assign('data', $item);
}
$smarty->assign('notify', $notify);
$smarty->assign('error', $error);
include 'footer.php';
