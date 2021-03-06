<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page = 'popup_create_broker';

$house = new HOMEHouse();
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

////////////////////////////////////////////////////////////////
$house_address_serialize['city_id'] = $city_id;
$house_address_serialize['district_id'] = $district_id;
$house_address_serialize['street_id'] = $street_id;
$house_address_serialize['ward_id'] = $ward_id;

$house_address_serialize['broker_company_address'] = $broker_company_address;

$house_address_serialize = serialize($house_address_serialize);
//get info search
$house_city_search = $house->getNameCity($city_id);
$house_district_search = $house->getNameDistrict($district_id);
$house_street_search = $house->getNameStreet($street_id);
$house_ward_search = $house->getNameWard($ward_id);
$house_search = $house_city_search . $house_district_search . $house_street_search . $house_ward_search . $broker_company_address;


if (isset($_POST['submit'])) {
    $validate = array(
        'broker_company_name' => $broker_company_name,
        'city_id' => $city_id,
        'district_id' => $district_id,
        'street_id' => $street_id,
        'ward_name' => $ward_id,
        'broker_company_phone' => $broker_company_phone,
    );
    $validator = new HOMEValidate();
    $message = $validator->validate($validate);
    if (empty($message)) {
        $status = 0;
        $broker = new HOMEBroker();
        $result = $broker->create($broker_company_name, $house_address_serialize, $broker_company_phone, $broker_company_email, $broker_company_fax, $broker_company_undertake,$house_search);
        if (!$result) {
            $message[]="$broker_company_name"." 存在している.";
            $status = 0;
        }else{
            $status = 1;
            $message = array(
                'id'    => $result,
                'name' => $broker_company_name,
            );
        }
    }
    print_r(json_encode(array(
        'status' => $status,
        'data'  => $message,
    )));
        exit();
}
$cities = $house->getAllCity();

$smarty->assign('cities', $cities);

include "footer.php";

