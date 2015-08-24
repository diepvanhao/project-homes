<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";
$page = "user_account";
$error = null;
$result = FALSE;
//var_dump($user);die();
//check user login

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if (!@HOMEOrder::checkPermisson('create-account')) {
    header('Location: ./restrict.php');
    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
$house = new HOMEHouse();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = "";
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
} elseif (isset($_GET['password'])) {
    $password = $_GET['password'];
} else {
    $password = "";
}
if (isset($_POST['confirm_password'])) {
    $confirm_password = $_POST['confirm_password'];
} elseif (isset($_GET['confirm_password'])) {
    $confirm_password = $_GET['confirm_password'];
} else {
    $confirm_password = "";
}
if (isset($_POST['username'])) {
    $username = $_POST['username'];
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
    $username = "";
}
if (isset($_POST['firstname'])) {
    $firstname = $_POST['firstname'];
} elseif (isset($_GET['firstname'])) {
    $firstname = $_GET['firstname'];
} else {
    $firstname = "";
}
if (isset($_POST['lastname'])) {
    $lastname = $_POST['lastname'];
} elseif (isset($_GET['lastname'])) {
    $lastname = $_GET['lastname'];
} else {
    $lastname = "";
}
if (isset($_POST['address'])) {
    $address = $_POST['address'];
} elseif (isset($_GET['address'])) {
    $address = $_GET['address'];
} else {
    $address = "";
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

$house_address_serialize['address'] = $address;

$house_address_serialize = serialize($house_address_serialize);
//get info search
$house_city_search = $house->getNameCity($city_id);
$house_district_search = $house->getNameDistrict($district_id);
$house_street_search = $house->getNameStreet($street_id);
$house_ward_search = $house->getNameWard($ward_id);
$house_search = $house_city_search . $house_district_search . $house_street_search . $house_ward_search . $address;
if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
} elseif (isset($_GET['phone'])) {
    $phone = $_GET['phone'];
} else {
    $phone = "";
}
if (isset($_POST['gender'])) {
    $gender = $_POST['gender'];
} elseif (isset($_GET['gender'])) {
    $gender = $_GET['gender'];
} else {
    $gender = "";
}
if (isset($_POST['birthday'])) {
    $birthday = $_POST['birthday'];
} elseif (isset($_GET['birthday'])) {
    $birthday = $_GET['birthday'];
} else {
    $birthday = "";
}
if (isset($_POST['position'])) {
    $position = $_POST['position'];
} elseif (isset($_GET['position'])) {
    $position = $_GET['position'];
} else {
    $position = "";
}
if (isset($_POST['target'])) {
    $target = $_POST['target'];
} elseif (isset($_GET['target'])) {
    $target = $_GET['target'];
} else {
    $target = array();
}
if (isset($_POST['target_create_date'])) {
    $target_create_date = $_POST['target_create_date'];
} elseif (isset($_GET['target_create_date'])) {
    $target_create_date = $_GET['target_create_date'];
} else {
    $target_create_date = array();
}

//$target= array();
for ($i = 0; $i < count($target); $i++) {
    $target_temp[$target_create_date[$i]] = $target[$i];        
}
if(isset($target_temp))
    $target=$target_temp;
//var_dump($target);die();
if (isset($_POST['level'])) {
    $level = $_POST['level'];
} elseif (isset($_GET['level'])) {
    $level = $_GET['level'];
} else {
    $level = "";
}
if (isset($_POST['agent'])) {
    $agent = $_POST['agent'];
} elseif (isset($_GET['agent'])) {
    $agent = $_GET['agent'];
} else {
    $agent = "";
}
if (isset($_POST['group'])) {
    $group = $_POST['group'];
} elseif (isset($_GET['group'])) {
    $group = $_GET['group'];
} else {
    $group = "";
}
if (isset($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
} elseif (isset($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
} else {
    $photo = "";
}

//validate values input
$validate = array(
    'email' => array('email' => $email),
    'password' => array('pass' => $password, 'confirm_pass' => $confirm_password),
    'username' => array('username' => $username),
    'firstname' => $firstname,
    'lastname' => $lastname,
    'city_id' => $city_id,
    'district_id' => $district_id,
    'street_id' => $street_id
        //'ward_name' => $ward_id
);
if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $userClass = new HOMEUser();
        $result = $userClass->user_create($agent, $username, $password, $confirm_password, $firstname, $lastname, $house_address_serialize, $email, $phone, $gender, $birthday, $photo, $position, $level, $target, $target_create_date, $house_search, $group);
        if ($result) {
            header("Location: notify.php?content=登録完了致しましたは成功に作成されました。!!!&url_return=user_account.php");
        }
    }
}

//get agents
$agentClass = new HOMEAgent();
$agents = $agentClass->getAgent();

//get groups
$groups = $house->getAllGroup();
//$smarty->clearCache("$page.tpl");

$cities = $house->getAllCity();
//get year


$smarty->assign('cities', $cities);
$smarty->assign('city_id', $city_id);
$smarty->assign('district_id', $district_id);
$smarty->assign('street_id', $street_id);
$smarty->assign('ward_id', $ward_id);
$smarty->assign('email', $email);
$smarty->assign('password', $password);
$smarty->assign('confirm_password', $confirm_password);
$smarty->assign('username', $username);
$smarty->assign('firstname', $firstname);
$smarty->assign('lastname', $lastname);
$smarty->assign('address', $address);
$smarty->assign('phone', $phone);
$smarty->assign('gender', $gender);
$smarty->assign('birthday', $birthday);
$smarty->assign('position', $position);
$smarty->assign('target', $target);
$smarty->assign('target_create_date', $target_create_date);

$smarty->assign('level', $level);
$smarty->assign('agent', $agent);
$smarty->assign('agents', $agents);
$smarty->assign('group', $group);
$smarty->assign('groups', $groups);
$smarty->assign('photo', $photo);
$smarty->assign('error', $error);

include "footer.php";
