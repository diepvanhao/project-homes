<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "popup_create_house";

$house = new HOMEHouse();
if (isset($_POST['house_name'])) {
    $house_name = $_POST['house_name'];
} elseif (isset($_GET['house_name'])) {
    $house_name = $_GET['house_name'];
} else {
    $house_name = "";
}

if (isset($_POST['house_address'])) {
    $house_address = $_POST['house_address'];
} elseif (isset($_GET['house_address'])) {
    $house_address = $_GET['house_address'];
} else {
    $house_address = "";
}

if (isset($_POST['house_area'])) {
    $house_area = $_POST['house_area'];
} elseif (isset($_GET['house_area'])) {
    $house_area = $_GET['house_area'];
} else {
    $house_area = "";
}


if (isset($_POST['house_build_time'])) {
    $house_build_time = $_POST['house_build_time'];
} elseif (isset($_GET['house_build_time'])) {
    $house_build_time = $_GET['house_build_time'];
} else {
    $house_build_time = "";
}

if (isset($_POST['house_type'])) {
    $house_type = $_POST['house_type'];
} elseif (isset($_GET['house_type'])) {
    $house_type = $_GET['house_type'];
} else {
    $house_type = "";
}

if (isset($_POST['house_description'])) {
    $house_description = $_POST['house_description'];
} elseif (isset($_GET['house_description'])) {
    $house_description = $_GET['house_description'];
} else {
    $house_description = "";
}


if (isset($_POST['house_structure'])) {
    $house_structure = $_POST['house_structure'];
} elseif (isset($_GET['house_structure'])) {
    $house_structure = $_GET['house_structure'];
} else {
    $house_structure = "";
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
//////////for return create order ////////////////////
if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
}
if (isset($_POST['return_url'])) {
    $return_url = $_POST['return_url'];
} elseif (isset($_GET['return_url'])) {
    $return_url = $_GET['return_url'];
} else {
    $return_url = "";
}

if (isset($_POST['staff_id'])) {
    $staff_id = $_POST['staff_id'];
} elseif (isset($_GET['staff_id'])) {
    $staff_id = $_GET['staff_id'];
} else {
    $staff_id = "";
}

/////////////////////////////////////////////////////
$house_address_serialize['city_id'] = $city_id;
$house_address_serialize['district_id'] = $district_id;
$house_address_serialize['street_id'] = $street_id;
$house_address_serialize['ward_id'] = $ward_id;
$house_address_serialize['house_address'] = $house_address;

$house_address_serialize = serialize($house_address_serialize);
//get info search
$house_city_search=$house->getNameCity($city_id);
$house_district_search=$house->getNameDistrict($district_id);
$house_street_search=$house->getNameStreet($street_id);
$house_ward_search=$house->getNameWard($ward_id);
$house_search=$house_city_search.$house_district_search.$house_street_search.$house_ward_search.$house_address;
/////////////////////////////////////////////////////////////////////// Owner
if (isset($_POST['house_owner_name'])) {
    $house_owner_name = $_POST['house_owner_name'];
} elseif (isset($_GET['house_owner_name'])) {
    $house_owner_name = $_GET['house_owner_name'];
} else {
    $house_owner_name = "";
}

if (isset($_POST['house_owner_address'])) {
    $house_owner_address = $_POST['house_owner_address'];
} elseif (isset($_GET['house_owner_address'])) {
    $house_owner_address = $_GET['house_owner_address'];
} else {
    $house_owner_address = "";
}

if (isset($_POST['house_owner_phone'])) {
    $house_owner_phone = $_POST['house_owner_phone'];
} elseif (isset($_GET['house_owner_phone'])) {
    $house_owner_phone = $_GET['house_owner_phone'];
} else {
    $house_owner_phone = "";
}

if (isset($_POST['house_owner_fax'])) {
    $house_owner_fax = $_POST['house_owner_fax'];
} elseif (isset($_GET['house_owner_fax'])) {
    $house_owner_fax = $_GET['house_owner_fax'];
} else {
    $house_owner_fax = "";
}

if (isset($_POST['house_owner_email'])) {
    $house_owner_email = $_POST['house_owner_email'];
} elseif (isset($_GET['house_owner_email'])) {
    $house_owner_email = $_GET['house_owner_email'];
} else {
    $house_owner_email = "";
}

if (isset($_POST['owner'])) {
    $owner = 1;
} elseif (isset($_GET['owner'])) {
    $owner = 1;
} else {
    $owner = "";
}
if (isset($_POST['city_id_owner'])) {
    $city_id_owner = $_POST['city_id_owner'];
} elseif (isset($_GET['city_id_owner'])) {
    $city_id_owner = $_GET['city_id_owner'];
} else {
    $city_id_owner = "";
}
if (isset($_POST['district_id_owner'])) {
    $district_id_owner = $_POST['district_id_owner'];
} elseif (isset($_GET['district_id_owner'])) {
    $district_id_owner = $_GET['district_id_owner'];
} else {
    $district_id_owner = 0;
}
if (isset($_POST['street_id_owner'])) {
    $street_id_owner = $_POST['street_id_owner'];
} elseif (isset($_GET['street_id_owner'])) {
    $street_id_owner = $_GET['street_id_owner'];
} else {
    $street_id_owner = 0;
}
if (isset($_POST['ward_id_owner'])) {
    $ward_id_owner = $_POST['ward_id_owner'];
} elseif (isset($_GET['ward_id_owner'])) {
    $ward_id_owner = $_GET['ward_id_owner'];
} else {
    $ward_id_owner = 0;
}

$house_owner_address_serialize['city_id_owner'] = $city_id_owner;
$house_owner_address_serialize['district_id_owner'] = $district_id_owner;
$house_owner_address_serialize['street_id_owner'] = $street_id_owner;
$house_owner_address_serialize['ward_id_owner'] = $ward_id_owner;
$house_owner_address_serialize['house_owner_address'] = $house_owner_address;

$house_owner_address_serialize = serialize($house_owner_address_serialize);

//get info search
$house_city_search=$house->getNameCity($city_id_owner);
$house_district_search=$house->getNameDistrict($district_id_owner);
$house_street_search=$house->getNameStreet($street_id_owner);
$house_ward_search=$house->getNameWard($ward_id_owner);
$house_owner_search=$house_city_search.$house_district_search.$house_street_search.$house_ward_search.$house_owner_address;
$validate = array(
    'house_name' => $house_name,
    //'house_address' => $house_address,
    'city_id'=>$city_id,
    'district_id'=>$district_id,
    'street_id'=>$street_id,
    'ward_name'=>$ward_id
);

if ($owner)
    $validate['house_owner_name'] = $house_owner_name;

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $message = $validator->validate($validate);
    if (empty($message)) {
        $result = $house->create(
                $house_name, $house_address_serialize, $house_area, $house_build_time, $house_type, $house_description, $house_structure, $house_owner_name, $house_owner_address_serialize, $house_owner_phone, $house_owner_fax, $house_owner_email,$house_search,$house_owner_search
        );
    }
    if (!$result) {
        $status = 0;
    }else{
        $status = 1;
        $message = array(
            'id'    => $result,
            'name' => $house_name,
        );
    }
    print_r(json_encode(array(
        'status' => $status,
        'data'  => $message,
    )));
        exit();
}
//get house type

$houseTypes = $house->getHouseType();
//get house structure
$houseStructures = $house->getHouseStructures();

$cities = $house->getAllCity();

$smarty->assign('cities', $cities);

$smarty->assign('city_id', $city_id);
$smarty->assign('houseTypes', $houseTypes);
$smarty->assign('houseStructures', $houseStructures);

include 'footer.php';
