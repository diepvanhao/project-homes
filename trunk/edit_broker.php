<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_broker";
$error = null;
$result = FALSE;
$notify="";

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}


if (isset($_POST['broker_company_name'])) {
    $broker_company_name = $_POST['broker_company_name'];
} elseif (isset($_GET['broker_company_name'])) {
    $broker_company_name = $_GET['broker_company_name'];
} else {
    $broker_company_name = "";
}

if (isset($_POST['broker_company_address'])) {
    $broker_company_address = $_POST['broker_company_address'];
} elseif (isset($_GET['broker_company_address'])) {
    $broker_company_address = $_GET['broker_company_address'];
} else {
    $broker_company_address = "";
}

if (isset($_POST['broker_company_phone'])) {
    $broker_company_phone = $_POST['broker_company_phone'];
} elseif (isset($_GET['broker_company_phone'])) {
    $broker_company_phone = $_GET['broker_company_phone'];
} else {
    $broker_company_phone = "";
}

if (isset($_POST['broker_company_email'])) {
    $broker_company_email = $_POST['broker_company_email'];
} elseif (isset($_GET['broker_company_email'])) {
    $broker_company_email = $_GET['broker_company_email'];
} else {
    $broker_company_email = "";
}

if (isset($_POST['broker_company_fax'])) {
    $broker_company_fax = $_POST['broker_company_fax'];
} elseif (isset($_GET['broker_company_fax'])) {
    $broker_company_fax = $_GET['broker_company_fax'];
} else {
    $broker_company_fax = "";
}

if (isset($_POST['broker_company_undertake'])) {
    $broker_company_undertake = $_POST['broker_company_undertake'];
} elseif (isset($_GET['broker_company_undertake'])) {
    $broker_company_undertake = $_GET['broker_company_undertake'];
} else {
    $broker_company_undertake = "";
}


if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
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
$house_address_serialize['city_id'] = $city_id;
$house_address_serialize['district_id'] = $district_id;
$house_address_serialize['street_id'] = $street_id;
$house_address_serialize['ward_id'] = $ward_id;

$house_address_serialize['broker_company_address'] = $broker_company_address;

$house_address_serialize = serialize($house_address_serialize);

if (isset($_POST['url'])) {
    $content = $_POST['url'];
} elseif (isset($_GET['url'])) {
    $content = $_GET['url'];
} else {
    $content = "";
}

$content = base64_decode($content);
$content = explode('&', $content);
$validate = array(
    'broker_company_name' => $broker_company_name,
    'broker_company_address' => $broker_company_address,
    'city_id' => $city_id,
    'district_id' => $district_id,
    'street_id' => $street_id,
    'ward_name' => $ward_id,
    'broker_company_phone' => $broker_company_phone,
    'broker_company_email' => array('broker_company_email'=>$broker_company_email,'broker_id'=>$broker_id) 
);
$house = new HOMEHouse();
if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $broker = new HOMEBroker();
        $result = $broker->update($broker_id, $broker_company_name, $house_address_serialize, $broker_company_phone, $broker_company_email, $broker_company_fax,$broker_company_undertake);        
        if ($result) {
            $notify="Update success !!!";
        }
    }
} elseif ($content[0] == 'edit') {
    $broker = new HOMEBroker();

    $result = $broker->getBrokerById($content[1]);
    if (!empty($result)) {
        $broker_company_name = $result['broker_company_name'];
        if ($house->isSerialized($result['broker_company_address'])) {
            $house_address_serialize = unserialize($result['broker_company_address']);
            $city_id = $house_address_serialize['city_id'];
            $district_id = $house_address_serialize['district_id'];
            $street_id = $house_address_serialize['street_id'];
            $ward_id = $house_address_serialize['ward_id'];
            $broker_company_address = $house_address_serialize['broker_company_address'];
        } else {
            $broker_company_address = $result['broker_company_address'];
        }
        //$broker_company_address = $result['broker_company_address'];
        $broker_company_phone = $result['broker_company_phone'];
        $broker_company_email = $result['broker_company_email'];
        $broker_company_fax = $result['broker_company_fax'];
        $broker_company_undertake = $result['broker_company_undertake'];
        $broker_id = $result['id'];
    }
}

$cities = $house->getAllCity();

$smarty->assign('cities', $cities);
$smarty->assign('city_id', $city_id);
$smarty->assign('district_id', $district_id);
$smarty->assign('street_id', $street_id);
$smarty->assign('ward_id', $ward_id);

$smarty->assign('broker_company_name', $broker_company_name);
$smarty->assign('broker_company_address', $broker_company_address);
$smarty->assign('broker_company_phone', $broker_company_phone);
$smarty->assign('broker_company_email', $broker_company_email);
$smarty->assign('broker_company_fax', $broker_company_fax);
$smarty->assign('broker_company_undertake', $broker_company_undertake);
$smarty->assign('broker_id', $broker_id);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
