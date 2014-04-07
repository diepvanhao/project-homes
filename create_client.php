<?php


include "header.php";

$page = "create_client";
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // do with post

    $data = $_POST;
    extract($_POST);
    $error = array();
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
        include 'include/class_client.php';
        $client = new Client();
        $result = $client->create($data);
        if ($result) {
            header('Location: ./manage_client.php');
        }
    }
} else {
    //do with Edit
}
@$smarty->assign('data', $data);
$smarty->assign('error', $error);
include 'footer.php';
