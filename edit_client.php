<?php

include "header.php";

$page = "edit_client";
$error = null;
$result = FALSE;
$notify="";
if (!$user->user_exists) {
    header('Location: ./user_login.php');

    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
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
include_once 'include/class_client.php';
$client = new Client();
$item = $client->getClientId($id);
if(empty($item)){
    $error[] = 'Client Not found';
}
$house = new HOMEHouse();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // do with post

    $data = $_POST;
    extract($_POST);

    if (empty($client_name)) {
        $error[] = 'Name is required';
    }
//    if (empty($client_address)) {
//        $error[] = 'Address is required';
//    }
    //Hao customize
    if (empty($city_id)) {
        $error[] = 'City is required';
    }
    if (empty($district_id)) {
        $error[] = 'District is required';
    }
    if (empty($street_id)) {
        $error[] = 'Street is required';
    }
    if (empty($ward_id)) {
        $error[] = 'Ward is required';
    }

    //end customize
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
        $data['client_search']=$house_search;
        $data['client_address']=$house_address_serialize;
        //End customize
        $result = $client->update($id,$data);
        if ($result) {
            $notify="アップデート成功 !!!";
            $house_address_serialize = unserialize($data['client_address']);
            $data['client_address']= $house_address_serialize['client_address'];
        }
    }
    @$smarty->assign('data', $data);
} else {
    if ($house->isSerialized($item['client_address'])) {
            $house_address_serialize = unserialize($item['client_address']);
            $city_id = $house_address_serialize['city_id'];
            $district_id = $house_address_serialize['district_id'];
            $street_id = $house_address_serialize['street_id'];
            $ward_id = $house_address_serialize['ward_id'];
            $item['client_address'] = $house_address_serialize['client_address'];
        } else {
           // $client_address = $item['client_address'];
        }
    //do with Edit
    @$smarty->assign('data', $item);
}
//Hao customize
$house = new HOMEHouse();
$cities = $house->getAllCity();
//get room type
$roomTypes=$house->getRoomType();

$smarty->assign('roomTypes', $roomTypes);
$smarty->assign('cities', $cities);
$smarty->assign('city_id', $city_id);
$smarty->assign('district_id', $district_id);
$smarty->assign('street_id', $street_id);
$smarty->assign('ward_id', $ward_id);
//End customize

$smarty->assign('notify', $notify);
$smarty->assign('error', $error);
include 'footer.php';
