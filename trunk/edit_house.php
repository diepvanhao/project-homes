<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_house";
$error = null;
$result = FALSE;
$notify = "";

//check user login
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('create-house')) {
    header('Location: ./restrict.php');
    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
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
    $district_id = "0";
}
if (isset($_POST['street_id'])) {
    $street_id = $_POST['street_id'];
} elseif (isset($_GET['street_id'])) {
    $street_id = $_GET['street_id'];
} else {
    $street_id = "0";
}
if (isset($_POST['ward_id'])) {
    $ward_id = $_POST['ward_id'];
} elseif (isset($_GET['ward_id'])) {
    $ward_id = $_GET['ward_id'];
} else {
    $ward_id = "0";
}
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

if (isset($_POST['house_id'])) {
    $house_id = $_POST['house_id'];
} elseif (isset($_GET['house_id'])) {
    $house_id = $_GET['house_id'];
} else {
    $house_id = "";
}
if (isset($_POST['owner_id'])) {
    $owner_id = $_POST['owner_id'];
} elseif (isset($_GET['owner_id'])) {
    $owner_id = $_GET['owner_id'];
} else {
    $owner_id = "";
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
    $district_id_owner = "0";
}
if (isset($_POST['street_id_owner'])) {
    $street_id_owner = $_POST['street_id_owner'];
} elseif (isset($_GET['street_id_owner'])) {
    $street_id_owner = $_GET['street_id_owner'];
} else {
    $street_id_owner = "0";
}
if (isset($_POST['ward_id_owner'])) {
    $ward_id_owner = $_POST['ward_id_owner'];
} elseif (isset($_GET['ward_id_owner'])) {
    $ward_id_owner = $_GET['ward_id_owner'];
} else {
    $ward_id_owner = "0";
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
    'house_name' => $house_name,
    //'house_address' => $house_address,
    'city_id' => $city_id,
    'district_id' => $district_id,
    'street_id' => $street_id
    //'ward_name' => $ward_id
);

if ($owner)
    $validate['house_owner_name'] = $house_owner_name;


if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {

        $result = $house->update_house(
                $house_name, $house_address_serialize, $house_area, $house_build_time, $house_type, $house_description, $house_structure, $house_owner_name, $house_owner_address_serialize, $house_owner_phone, $house_owner_fax, $house_owner_email, $house_id, $owner_id,$house_search,$house_owner_search
        );
        if ($result) {
            $notify = "アップデート成功 !!!";
        }
    }
} elseif ($content[0] == 'edit') {


    $result = $house->getHouseById($content[1]);
    if (!empty($result)) {
        $house_name = $result['house_name'];

        if ($house->isSerialized($result['house_address'])) {
            $house_address_serialize = unserialize($result['house_address']);
            $city_id = $house_address_serialize['city_id'];
            $district_id = $house_address_serialize['district_id'];
            $street_id = $house_address_serialize['street_id'];
            $ward_id = $house_address_serialize['ward_id'];
            $house_address = $house_address_serialize['house_address'];
        } else {
            $house_address = $result['house_address'];
        }
        $house_area = $result['house_area'];
        $house_build_time = $result['house_build_time'];
        $house_type = $result['house_type'];
        $house_description = $result['house_description'];
        $house_structure = $result['house_structure'];
        if ($result['house_owner_name'])
            $owner = 1;
        $house_owner_name = $result['house_owner_name'];

        if ($house->isSerialized($result['house_owner_address'])) {
            $house_owner_address_serialize = unserialize($result['house_owner_address']);
            $city_id_owner = $house_owner_address_serialize['city_id_owner'];
            $district_id_owner = $house_owner_address_serialize['district_id_owner'];
            $street_id_owner = $house_owner_address_serialize['street_id_owner'];
            $ward_id_owner = $house_owner_address_serialize['ward_id_owner'];
            $house_owner_address = $house_owner_address_serialize['house_owner_address'];
        } else {
            $house_owner_address = $result['house_owner_address'];
        }


        $house_owner_phone = $result['house_owner_phone'];
        $house_owner_fax = $result['house_owner_fax'];
        $house_owner_email = $result['house_owner_email'];
        $house_id = $result['id'];
        $owner_id = $result['house_owner_id'];
    }
}
//get house type

$houseTypes = $house->getHouseType();
//get house structure
$houseStructures = $house->getHouseStructures();

$cities = $house->getAllCity();

$smarty->assign('cities', $cities);

$smarty->assign('city_id', $city_id);
$smarty->assign('district_id', $district_id);
$smarty->assign('street_id', $street_id);
$smarty->assign('ward_id', $ward_id);

$smarty->assign('city_id_owner', $city_id_owner);
$smarty->assign('district_id_owner', $district_id_owner);
$smarty->assign('street_id_owner', $street_id_owner);
$smarty->assign('ward_id_owner', $ward_id_owner);

$smarty->assign('house_id', $house_id);
$smarty->assign('owner_id', $owner_id);
$smarty->assign('house_name', $house_name);
$smarty->assign('house_address', $house_address);
$smarty->assign('house_area', $house_area);

$smarty->assign('house_build_time', $house_build_time);
$smarty->assign('house_type', $house_type);
$smarty->assign('house_description', $house_description);

$smarty->assign('house_structure', $house_structure);
$smarty->assign('house_owner_name', $house_owner_name);
$smarty->assign('house_owner_address', $house_owner_address);
$smarty->assign('house_owner_phone', $house_owner_phone);
$smarty->assign('house_owner_fax', $house_owner_fax);
$smarty->assign('house_owner_email', $house_owner_email);
$smarty->assign('houseTypes', $houseTypes);
$smarty->assign('houseStructures', $houseStructures);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);
$smarty->assign('owner', $owner);

include "footer.php";
