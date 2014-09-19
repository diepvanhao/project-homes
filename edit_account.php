<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "header.php";
$page = "edit_account";
$error = null;
$result = FALSE;
$notify = "";

if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}
if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
$house = new HOMEHouse();
$year=date('Y');
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
$house_city_search=$house->getNameCity($city_id);
$house_district_search=$house->getNameDistrict($district_id);
$house_street_search=$house->getNameStreet($street_id);
$house_ward_search=$house->getNameWard($ward_id);
$house_search=$house_city_search.$house_district_search.$house_street_search.$house_ward_search.$address;
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
if (isset($_POST['target_1'])) {
    $target_1 = $_POST['target_1'];
} elseif (isset($_GET['target_1'])) {
    $target_1 = $_GET['target_1'];
} else {
    $target_1 = "";
}
if (isset($_POST['target_2'])) {
    $target_2 = $_POST['target_2'];
} elseif (isset($_GET['target_2'])) {
    $target_2 = $_GET['target_2'];
} else {
    $target_2 = "";
}
if (isset($_POST['target_3'])) {
    $target_3 = $_POST['target_3'];
} elseif (isset($_GET['target_3'])) {
    $target_3 = $_GET['target_3'];
} else {
    $target_3 = "";
}
if (isset($_POST['target_4'])) {
    $target_4 = $_POST['target_4'];
} elseif (isset($_GET['target_4'])) {
    $target_4 = $_GET['target_4'];
} else {
    $target_4 = "";
}
if (isset($_POST['target_5'])) {
    $target_5 = $_POST['target_5'];
} elseif (isset($_GET['target_5'])) {
    $target_5 = $_GET['target_5'];
} else {
    $target_5 = "";
}
if (isset($_POST['target_6'])) {
    $target_6 = $_POST['target_6'];
} elseif (isset($_GET['target_6'])) {
    $target_6 = $_GET['target_6'];
} else {
    $target_6 = "";
}
if (isset($_POST['target_7'])) {
    $target_7 = $_POST['target_7'];
} elseif (isset($_GET['target_7'])) {
    $target_7 = $_GET['target_7'];
} else {
    $target_7 = "";
}
if (isset($_POST['target_8'])) {
    $target_8 = $_POST['target_8'];
} elseif (isset($_GET['target_8'])) {
    $target_8 = $_GET['target_8'];
} else {
    $target_8 = "";
}
if (isset($_POST['target_9'])) {
    $target_9 = $_POST['target_9'];
} elseif (isset($_GET['target_9'])) {
    $target_9 = $_GET['target_9'];
} else {
    $target_9 = "";
}
if (isset($_POST['target_10'])) {
    $target_10 = $_POST['target_10'];
} elseif (isset($_GET['target_10'])) {
    $target_10 = $_GET['target_10'];
} else {
    $target_10 = "";
}
if (isset($_POST['target_11'])) {
    $target_11 = $_POST['target_11'];
} elseif (isset($_GET['target_11'])) {
    $target_11 = $_GET['target_11'];
} else {
    $target_11 = "";
}
if (isset($_POST['target_12'])) {
    $target_12 = $_POST['target_12'];
} elseif (isset($_GET['target_12'])) {
    $target_12 = $_GET['target_12'];
} else {
    $target_12 = "";
}
//$target= array();
$target = array(
    "$year" . "_01_01" => $target_1,
    "$year" . "_02_01" => $target_2,
    "$year" . "_03_01" => $target_3,
    "$year" . "_04_01" => $target_4,
    "$year" . "_05_01" => $target_5,
    "$year" . "_06_01" => $target_6,
    "$year" . "_07_01" => $target_7,
    "$year" . "_08_01" => $target_8,
    "$year" . "_09_01" => $target_9,
    "$year" . "_10_01" => $target_10,
    "$year" . "_11_01" => $target_11,
    "$year" . "_12_01" => $target_12
);
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
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
} elseif (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    $user_id = "";
}

if (isset($_POST['path_photo'])) {
    $path_photo = $_POST['path_photo'];
} elseif (isset($_GET['path_photo'])) {
    $path_photo = $_GET['path_photo'];
} else {
    $path_photo = "";
}
if (isset($_POST['thumb_photo'])) {
    $thumb_photo = $_POST['thumb_photo'];
} elseif (isset($_GET['thumb_photo'])) {
    $thumb_photo = $_GET['thumb_photo'];
} else {
    $thumb_photo = "";
}

if (isset($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
} elseif (isset($_FILES['photo']['name'])) {
    $photo = $_FILES['photo']['name'];
} else {
    $photo = "";
}



if (isset($_POST['url'])) {
    $content = $_POST['url'];
} elseif (isset($_GET['url'])) {
    $content = $_GET['url'];
} else {
    $content = "";
}

$content = base64_decode($content);
$content = explode('&', $content);

//validate values input
$validate = array(
    'email' => array('email' => $email, 'user_id' => $user_id),
    'password' => array('pass' => $password, 'confirm_pass' => $confirm_password),
    'username' => array('username' => $username, 'user_id' => $user_id),
    'firstname' => $firstname,
    'lastname' => $lastname,
    'address' => $address
);

if (isset($_POST['submit'])) {
    $validator = new HOMEValidate();
    $error = $validator->validate($validate);
    if (empty($error)) {
        $account = new HOMEUser();
        $result = $account->update($username, $password, $firstname, $lastname, $house_address_serialize, $email, $phone, $gender, $birthday, $photo, $position, $level, $target, $agent, $user_id,$house_search,$group);
        if ($result) {
            $notify = "アップデート成功 !!!";
            $result = $account->getAccountById($user_id);
            if (!empty($result)) {
                $user_id = $result['id'];
                $agent = $result['agent_id'];
                $group=$result['group_id'];
                $username = $result['user_username'];
                $firstname = $result['user_fname'];
                $lastname = $result['user_lname'];
                if ($house->isSerialized($result['user_address'])) {
                    $house_address_serialize = unserialize($result['user_address']);
                    $city_id = $house_address_serialize['city_id'];
                    $district_id = $house_address_serialize['district_id'];
                    $street_id = $house_address_serialize['street_id'];
                    $ward_id = $house_address_serialize['ward_id'];
                    $address = $house_address_serialize['address'];
                } else {
                    $address = $result['user_address'];
                }
                $email = $result['user_email'];
                $phone = $result['user_phone'];
                $gender = $result['user_gender'];
                $birthday = $result['user_birthday'];
                $level = $result['user_authorities'];
                $position = $result['user_position'];
                $target = getTarget($result['id']);
                $path_photo = $result['user_path_photo'];
                $thumb_photo = $result['user_path_thumb'];
                $photo = $result['user_photo'];
            }
        }
    }
} elseif ($content[0] == 'edit') {
    $account = new HOMEUser();
    $result = $account->getAccountById($content[1]);
    if (!empty($result)) {
        $user_id = $result['id'];
        $agent = $result['agent_id'];
        $group=$result['group_id'];
        $username = $result['user_username'];
        $firstname = $result['user_fname'];
        $lastname = $result['user_lname'];
        $address = $result['user_address'];
        if ($house->isSerialized($result['user_address'])) {
            $house_address_serialize = unserialize($result['user_address']);
            $city_id = $house_address_serialize['city_id'];
            $district_id = $house_address_serialize['district_id'];
            $street_id = $house_address_serialize['street_id'];
            $ward_id = $house_address_serialize['ward_id'];
            $address = $house_address_serialize['address'];
        } else {
            $address = $result['user_address'];
        }
        $email = $result['user_email'];
        $phone = $result['user_phone'];
        $gender = $result['user_gender'];
        $birthday = $result['user_birthday'];
        $level = $result['user_authorities'];
        $position = $result['user_position'];
        $targetArr = getTargetAccount($result['id']);
        for($i=0;$i<count($targetArr);$i++){ 
            switch($i){
                case 0:
                    $target_1=$targetArr[$i]['target'];
                    break;
                case 1:
                    $target_2=$targetArr[$i]['target'];
                    break;
                case 2:
                    $target_3=$targetArr[$i]['target'];
                    break;
                case 3:
                    $target_4=$targetArr[$i]['target'];
                    break;
                case 4:
                    $target_5=$targetArr[$i]['target'];
                    break;
                case 5:
                    $target_6=$targetArr[$i]['target'];
                    break;
                case 6:
                    $target_7=$targetArr[$i]['target'];
                    break;
                case 7:
                    $target_8=$targetArr[$i]['target'];
                    break;
                case 8:
                    $target_9=$targetArr[$i]['target'];
                    break;
                case 9:
                    $target_10=$targetArr[$i]['target'];
                    break;
                case 10:
                    $target_11=$targetArr[$i]['target'];
                    break;
                case 11:
                    $target_12=$targetArr[$i]['target'];
                    break;
            }        
            
        }
        $path_photo = $result['user_path_photo'];
        $thumb_photo = $result['user_path_thumb'];
        $photo = $result['user_photo'];
    }
}
//get agents
$agentClass = new HOMEAgent();
$agents = $agentClass->getAgent();
//get groups
$groups=$house->getAllGroup();

$cities = $house->getAllCity();
$smarty->assign('year', $year);
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
$smarty->assign('target_1', $target_1);
$smarty->assign('target_2', $target_2);
$smarty->assign('target_3', $target_3);
$smarty->assign('target_4', $target_4);
$smarty->assign('target_5', $target_5);
$smarty->assign('target_6', $target_6);
$smarty->assign('target_7', $target_7);
$smarty->assign('target_8', $target_8);
$smarty->assign('target_9', $target_9);
$smarty->assign('target_10', $target_10);
$smarty->assign('target_11', $target_11);
$smarty->assign('target_12', $target_12);
$smarty->assign('level', $level);
$smarty->assign('agent', $agent);
$smarty->assign('photo', $photo);
$smarty->assign('path_photo', $path_photo);
$smarty->assign('thumb_photo', $thumb_photo);
$smarty->assign('user_id', $user_id);
$smarty->assign('agents', $agents);
$smarty->assign('group', $group);
$smarty->assign('groups', $groups);
$smarty->assign('error', $error);
$smarty->assign('notify', $notify);

include "footer.php";
