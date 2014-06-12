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
        include 'include/class_client.php';
        //Hao customize
        $house_address_serialize['city_id'] = $city_id;
        $house_address_serialize['district_id'] = $district_id;
        $house_address_serialize['street_id'] = $street_id;
        $house_address_serialize['ward_id'] = $ward_id;

        $house_address_serialize['client_address'] = $data['client_address'];

        $house_address_serialize = serialize($house_address_serialize);
        
        $data['client_address']=$house_address_serialize;
        //End customize
        $client = new Client();
        $result = $client->create($data);
        if ($result) {
            header('Location: ./manage_client.php');
        }
    }
} else {
    //do with Edit
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

@$smarty->assign('data', $data);
$smarty->assign('error', $error);
include 'footer.php';
