<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "manage_account";

//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if (!@HOMEOrder::checkPermisson('manage-account')) {
    header('Location: ./restrict.php');
    exit();
}

if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}

if (isset($_POST['page_number'])) {
    $page_number = $_POST['page_number'];
} elseif (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}
if (isset($_POST['search'])) {
    $search = $_POST['search'];
} elseif (isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = "";
}


//calculator paging
$max = 25;
$totalItem = $user->getTotalItem($search);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;



$offset = $page_number * $max - $max;
$length = $max;

$users = $user->getAccount($search, $offset, $length);

$smarty->assign('search', $search);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('users', $users);
$smarty->assign('canEdit', ($user->user_info['user_authorities'] <=2));//@HOMEOrder::checkPermisson('create-account'));

include "footer.php";
