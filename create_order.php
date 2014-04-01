<?php

include "header.php";
$page = "create_order";
$error = null;
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}


if (isset($_POST['step'])) {
    $step = $_POST['step'];
} elseif (isset($_GET['step'])) {
    $step = $_GET['step'];
} else {
    $step = 1;
}

if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
}

if ($step == 1) {
    $broker = new HOMEBroker();
    $brokers = $broker->getAllBroker();
    $smarty->assign('brokers', $brokers);
} elseif ($step == 2) {


    if (empty($broker_id)) {
        $error[] = "Please choose Source to continue !!!";
        $step = 1;
        $broker = new HOMEBroker();
        $brokers = $broker->getAllBroker();
        $smarty->assign('brokers', $brokers);
    }
    $house = new HOMEHouse();
    $houses = $house->getHouses();
    $users = $user->getAllUsers();

    $smarty->assign('houses', $houses);
    $smarty->assign('users', $users);
} elseif ($step == 'verify') {

    if (isset($_POST['staff_id'])) {
        $staff_id = $_POST['staff_id'];
    } elseif (isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];
    } else {
        $staff_id = "";
    }

    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }

    $broker = new HOMEBroker();
    $brokers = $broker->getBrokerById($broker_id);
    $house = new HOMEHouse();
    $houses = $house->getHouseById($house_id);
    $staff = new HOMEUser();
    $staffs = $staff->getAccountById($staff_id);

    $smarty->assign('brokers', $brokers);
    $smarty->assign('houses', $houses);
    $smarty->assign('staffs', $staffs);
} elseif ($step == 'registry') {

    if (isset($_POST['filter'])) {
        $filter = $_POST['filter'];
    } elseif (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    } else {
        $filter = "";
    }
    if (isset($_POST['page_number'])) {
        $page_number = $_POST['page_number'];
    } elseif (isset($_GET['page_number'])) {
        $page_number = $_GET['page_number'];
    } else {
        $page_number = 1;
    }
    $customer = new HOMECustomer();
    //paging
    $max = 25;
    $totalItem = $customer->getTotalItem($filter);

    $totalPage = floor($totalItem / $max);

    if ($totalItem % $max > 0)
        $totalPage = $totalPage + 1;
    if ($page_number > $totalPage)
        $page_number = 1;


    $offset = $page_number * $max - $max;
    $length = $max;

    $customers = $customer->getCustomers($filter, $offset, $length);

    $smarty->assign('filter', $filter);
    $smarty->assign('page_number', $page_number);
    $smarty->assign('totalPage', $totalPage);
    $smarty->assign('customers', $customers);
}


$smarty->assign('broker_id', $broker_id);
$smarty->assign('step', $step);
$smarty->assign('error', $error);

include "footer.php";
