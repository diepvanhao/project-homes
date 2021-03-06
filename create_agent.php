<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page = 'create_agent';

$error = null;
$result = FALSE;

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('create-agent')) {
    header('Location: ./restrict.php');
    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
$house = new HOMEHouse();
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

if (isset($_POST['city_id'])) {
    $city_id = $_POST['city_id'];
} elseif (isset($_GET['city_id'])) {
    $city_id = $_GET['city_id'];
} else {
    $city_id = "";
}
if (isset($_POST['district_id'])) {
    $district_id = $_POST['district_id'];
} elseif (isset($_GET['district_id'])) {
    $district_id = $_GET['district_id'];
} else {
    $district_id = 0;
}
if (isset($_POST['street_id'])) {
    $street_id = $_POST['street_id'];
} elseif (isset($_GET['street_id'])) {
    $street_id = $_GET['street_id'];
} else {
    $street_id = 0;
}
if (isset($_POST['ward_id'])) {
    $ward_id = $_POST['ward_id'];
} elseif (isset($_GET['ward_id'])) {
    $ward_id = $_GET['ward_id'];
} else {
    $ward_id = 0;
}
if (isset($_POST['bank_name'])) {
    $bank_name = $_POST['bank_name'];
} elseif (isset($_GET['bank_name'])) {
    $bank_name = $_GET['bank_name'];
} else {
    $bank_name = "";
}
if (isset($_POST['branch_name'])) {
    $branch_name = $_POST['branch_name'];
} elseif (isset($_GET['branch_name'])) {
    $branch_name = $_GET['branch_name'];
} else {
    $branch_name = "";
}
if (isset($_POST['account_number'])) {
    $account_number = $_POST['account_number'];
} elseif (isset($_GET['account_number'])) {
    $account_number = $_GET['account_number'];
} else {
    $account_number = "";
}
if (isset($_POST['payer_name'])) {
    $payer_name = $_POST['payer_name'];
} elseif (isset($_GET['payer_name'])) {
    $payer_name = $_GET['payer_name'];
} else {
    $payer_name = "";
}

$house_address_serialize['city_id'] = $city_id;
$house_address_serialize['district_id'] = $district_id;
$house_address_serialize['street_id'] = $street_id;
$house_address_serialize['ward_id'] = $ward_id;

$house_address_serialize['agent_address'] = $agent_address;

$house_address_serialize = serialize($house_address_serialize);
//get info search
$house_city_search=$house->getNameCity($city_id);
$house_district_search=$house->getNameDistrict($district_id);
$house_street_search=$house->getNameStreet($street_id);
$house_ward_search=$house->getNameWard($ward_id);
$house_search=$house_city_search.$house_district_search.$house_street_search.$house_ward_search.$agent_address;
$validate = array(
    'agent_name' => $agent_name,
    //'agent_address' => $agent_address,
    'agent_phone' => $agent_phone,
    'city_id' => $city_id,
    'district_id' => $district_id,
    'street_id' => $street_id,
    //'ward_name' => $ward_id,
    'agent_email' => array('agent_email' => $agent_email)
);

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $agent = new HOMEAgent();
        $result = $agent->create($agent_name, $agent_email, $house_address_serialize, $agent_phone, $agent_fax,$house_search,$bank_name,$branch_name,$account_number,$payer_name);
        if ($result) {
            header("Location: notify.php?content=店舗～は成功に作成されました。!!!&url_return=create_agent.php");
        }
    }
}


$cities = $house->getAllCity();

$smarty->assign('cities', $cities);
$smarty->assign('city_id', $city_id);
$smarty->assign('district_id', $district_id);
$smarty->assign('street_id', $street_id);
$smarty->assign('ward_id', $ward_id);

$smarty->assign('agent_name', $agent_name);
$smarty->assign('agent_email', $agent_email);
$smarty->assign('agent_address', $agent_address);
$smarty->assign('agent_phone', $agent_phone);
$smarty->assign('agent_fax', $agent_fax);
$smarty->assign('error', $error);

$smarty->assign('bank_name', $bank_name);
$smarty->assign('branch_name', $branch_name);
$smarty->assign('account_number', $account_number);
$smarty->assign('payer_name', $payer_name);

include "footer.php";

