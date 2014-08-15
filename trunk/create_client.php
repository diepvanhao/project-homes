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
$house = new HOMEHouse();
//Hao customize
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
//End customize
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // do with post

    $data = $_POST;
    extract($_POST);
    $error = array();
    if (empty($client_name)) {
        $error[] = '名称～は成功に作成されました。';
    }
//    if (empty($client_address)) {
//        $error[] = 'Address is required';
//    }
    //Hao customize
    if (empty($city_id)) {
        $error[] = '都道府県は必須です。';
    }
    if (empty($district_id)) {
        $error[] = '市区町村は必須です。';
    }
    if (empty($street_id)) {
        $error[] = '大字・通称は必須です。';
    }
    if (empty($ward_id)) {
        $error[] = '字・丁目は必須です。';
    }

    //end customize
    if (empty($client_phone)) {
        $error[] = '電話番号～は成功に作成されました。';
    }
    if (!empty($client_income) && !filter_var($client_income, FILTER_VALIDATE_FLOAT)) {
        $error[] = 'Income must be a Float number';
    }
    if (!empty($client_rent) && !filter_var($client_rent, FILTER_VALIDATE_FLOAT)) {
        $error[] = 'Rent must be a Float number';
    }
    if (!empty($client_email) && !filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Eメールが有効ではありません。';
    }

    if (empty($error)) {
        include_once 'include/class_client.php';
        //Hao customize
        $house_address_serialize['city_id'] = $city_id;
        $house_address_serialize['district_id'] = $district_id;
        $house_address_serialize['street_id'] = $street_id;
        $house_address_serialize['ward_id'] = $ward_id;

        $house_address_serialize['client_address'] = $data['client_address'];

        $house_address_serialize = serialize($house_address_serialize);
        //get info search
        $house_city_search = $house->getNameCity($city_id);
        $house_district_search = $house->getNameDistrict($district_id);
        $house_street_search = $house->getNameStreet($street_id);
        $house_ward_search = $house->getNameWard($ward_id);
        $house_search = $house_city_search . $house_district_search . $house_street_search . $house_ward_search . $data['client_address'];
        $data['client_address'] = $house_address_serialize;
        $data['client_search']=$house_search;
        //End customize
        $client = new Client();
        $result = $client->create($data);
        if ($result) {
            //header('Location: ./manage_client.php');
            header("Location: notify.php?content=お客情報～は成功に作成されました。!!!&url_return=create_client.php");
        }
    }
} else {
    //do with Edit
}
//Hao customize

$cities = $house->getAllCity();
//get room type
$roomTypes = $house->getRoomType();

$smarty->assign('roomTypes', $roomTypes);
$smarty->assign('cities', $cities);
$smarty->assign('city_id', $city_id);
$smarty->assign('district_id', $district_id);
$smarty->assign('street_id', $street_id);
$smarty->assign('ward_id', $ward_id);
//End customize

@$smarty->assign('data', $data);
$smarty->assign('error', $error);
include 'footer.php';
