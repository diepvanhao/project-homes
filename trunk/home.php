<?php

$page="home";
include "header.php";
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
$orderClass = new HOMEOrder();

$smarty->assign('messages', $orderClass->getHomeMessages());

include "footer.php";
