<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "class_ajax.php";
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = "";
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
} elseif (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
    $username = "";
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

if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}

//init ajax
$ajax = new ajax();

if ($action == "check_email") {

    $error = $ajax->checkExists('email', array('email' => $email));
    echo $error;
} elseif ($action == "check_username") {

    $error = $ajax->checkExists('username', array('username' => $username));
    echo $error;
} elseif ($action == 'check_password') {

    $error = $ajax->checkExists('confirm_password', array('password' => $password, 'confirm_password' => $confirm_password));
    echo $error;
} elseif ($action == 'deleteAgent') {

    if (isset($_POST['agent_id'])) {
        $agent_id = $_POST['agent_id'];
    } elseif (isset($_GET['agent_id'])) {
        $agent_id = $_GET['agent_id'];
    } else {
        $agent_id = "";
    }

    $result = $ajax->deleteAgent($agent_id);
    echo $result;
} elseif ($action == 'deleteHouse') {

    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }
    $result = $ajax->deleteHouse($house_id);
    echo $result;
} elseif ($action == 'deleteBroker') {

    if (isset($_POST['broker_id'])) {
        $broker_id = $_POST['broker_id'];
    } elseif (isset($_GET['broker_id'])) {
        $broker_id = $_GET['broker_id'];
    } else {
        $broker_id = "";
    }
    $result = $ajax->deleteBroker($broker_id);
    echo $result;
} elseif ($action == 'deleteAccount') {

    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    } elseif (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
    } else {
        $user_id = "";
    }
    $result = $ajax->deleteAccount($user_id);
    echo $result;
} elseif ($action == 'edit_profile') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == "editName") {
        if (isset($_POST['fname'])) {
            $fname = $_POST['fname'];
        } elseif (isset($_GET['fname'])) {
            $fname = $_GET['fname'];
        } else {
            $fname = "";
        }
        if (isset($_POST['lname'])) {
            $lname = $_POST['lname'];
        } elseif (isset($_GET['lname'])) {
            $lname = $_GET['lname'];
        } else {
            $lname = "";
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }

        $result = $ajax->editName($fname, $lname, $password);
        echo $result;
    }
    if ($task == "editUsername") {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (isset($_GET['username'])) {
            $username = $_GET['username'];
        } else {
            $username = "";
        }
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }
        $result = $ajax->editUsername($username, $password);
        echo json_encode($result);
    }
    if ($task == 'editEmail') {
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
        $result = $ajax->editEmail($email, $password);
        echo json_encode($result);
    }
    if ($task == 'editPassword') {

        if (isset($_POST['current_password'])) {
            $current_password = $_POST['current_password'];
        } elseif (isset($_GET['current_password'])) {
            $current_password = $_GET['current_password'];
        } else {
            $current_password = "";
        }
        if (isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];
        } elseif (isset($_GET['new_password'])) {
            $new_password = $_GET['new_password'];
        } else {
            $new_password = "";
        }
        if (isset($_POST['re_new_password'])) {
            $re_new_password = $_POST['re_new_password'];
        } elseif (isset($_GET['re_new_password'])) {
            $re_new_password = $_GET['re_new_password'];
        } else {
            $re_new_password = "";
        }

        $result = $ajax->editPassword($current_password, $new_password, $re_new_password);
        echo json_encode($result);
    }
    if ($task == 'editPhoto') {

        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } elseif (isset($_GET['password'])) {
            $password = $_GET['password'];
        } else {
            $password = "";
        }
        if (isset($_POST['photoname'])) {
            $photoname = $_POST['photoname'];
        } elseif (isset($_GET['photoname'])) {
            $photoname = $_GET['photoname'];
        } else {
            $photoname = "";
        }
        $result = $ajax->editPhoto($photoname, $password);
        echo json_encode($result);
    }
} elseif ($action == 'create_order') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == "getHouseSearch") {
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
        } elseif (isset($_GET['search'])) {
            $search = $_GET['search'];
        } else {
            $search = "";
        }
        $result = $ajax->getHouseByKey($search);
        if ($result) {
            for ($i = 0; $i < count($result); $i++) {
                echo "<option value='{$result[$i]['id']}'>{$result[$i]['house_name']}</option>";
            }
        }
    }
    if ($task == "getBrokerFilter") {
        if (isset($_POST['filter'])) {
            $filter = $_POST['filter'];
        } elseif (isset($_GET['filter'])) {
            $filter = $_GET['filter'];
        } else {
            $filter = "";
        }
        $result = $ajax->getBrokerByKey($filter);
        if ($result) {
            for ($i = 0; $i < count($result); $i++) {
                echo "<option value='{$result[$i]['id']}'>{$result[$i]['broker_company_name']}</option>";
            }
        }
    }
    if ($task == 'getHouseList') {
        if (isset($_POST['broker_id'])) {
            $broker_id = $_POST['broker_id'];
        } elseif (isset($_GET['broker_id'])) {
            $broker_id = $_GET['broker_id'];
        } else {
            $broker_id = "";
        }
        $result = $ajax->getHouseListByBrokerId($broker_id);
        if ($result) {
            echo "<option value=''></option>";
            for ($i = 0; $i < count($result); $i++) {
                echo "<option value='{$result[$i]['id']}'>{$result[$i]['house_name']}</option>";
            }
        }
    }
    if ($task == 'getContentHouse') {

        if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
        $result = $ajax->getHouseContent($house_id);
        echo json_encode($result);
    }
    if ($task == 'getRoomContent') {
        if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
        if (isset($_POST['room_id'])) {
            $room_id = $_POST['room_id'];
        } elseif (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = "";
        }
        $result = $ajax->getRoomContentByHouseId($house_id, $room_id);
        if ($result) {
            echo "<option value''></option>";
            for ($i = 0; $i < count($result); $i++) {

                echo "<option value='{$result[$i]['id']}'";
                if ($result[$i]['id'] == $room_id)
                    echo "selected='selected'";echo ">";
                echo "{$result[$i]['id']}</option>";
            }
        }
    }
    if ($task == 'checkRoom') {
        if (isset($_POST['room_id'])) {
            $room_id = $_POST['room_id'];
        } elseif (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = "";
        }
        if (isset($_POST['broker_id'])) {
            $broker_id = $_POST['broker_id'];
        } elseif (isset($_GET['broker_id'])) {
            $broker_id = $_GET['broker_id'];
        } else {
            $broker_id = "";
        }
        if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
        $result = $ajax->checkRoom($house_id, $room_id, $broker_id);
        echo json_encode($result);
    }
} elseif ($action == 'deleteClient') {

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "";
    }
    include 'class_client.php';
    $client = new Client();
    echo $client->delete($id);
} elseif ($action == 'customer') {

    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == 'detail') {
        if (isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
        } elseif (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            $order_id = "";
        }
        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
        } elseif (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
        } else {
            $client_id = "";
        }
        if (isset($_POST['gender'])) {
            $gender = $_POST['gender'];
        } elseif (isset($_GET['gender'])) {
            $gender = $_GET['gender'];
        } else {
            $gender = "";
        }
        if (isset($_POST['client_address'])) {
            $client_address = $_POST['client_address'];
        } elseif (isset($_GET['client_address'])) {
            $client_address = $_GET['client_address'];
        } else {
            $client_address = "";
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

        $house_address_serialize['client_address'] = $client_address;

        $house_address_serialize = serialize($house_address_serialize);

        if (isset($_POST['client_occupation'])) {
            $client_occupation = $_POST['client_occupation'];
        } elseif (isset($_GET['client_occupation'])) {
            $client_occupation = $_GET['client_occupation'];
        } else {
            $client_occupation = "";
        }
        if (isset($_POST['client_company'])) {
            $client_company = $_POST['client_company'];
        } elseif (isset($_GET['client_company'])) {
            $client_company = $_GET['client_company'];
        } else {
            $client_company = "";
        }
        if (isset($_POST['client_income'])) {
            $client_income = $_POST['client_income'];
        } elseif (isset($_GET['client_income'])) {
            $client_income = $_GET['client_income'];
        } else {
            $client_income = "";
        }
        if (isset($_POST['client_room_type'])) {
            $client_room_type = $_POST['client_room_type'];
        } elseif (isset($_GET['client_room_type'])) {
            $client_room_type = $_GET['client_room_type'];
        } else {
            $client_room_type = "";
        }
        if (isset($_POST['client_rent'])) {
            $client_rent = $_POST['client_rent'];
        } elseif (isset($_GET['client_rent'])) {
            $client_rent = $_GET['client_rent'];
        } else {
            $client_rent = "";
        }
        if (isset($_POST['client_reason_change'])) {
            $client_reason_change = $_POST['client_reason_change'];
        } elseif (isset($_GET['client_reason_change'])) {
            $client_reason_change = $_GET['client_reason_change'];
        } else {
            $client_reason_change = "";
        }
        if (isset($_POST['client_time_change'])) {
            $client_time_change = $_POST['client_time_change'];
        } elseif (isset($_GET['client_time_change'])) {
            $client_time_change = $_GET['client_time_change'];
        } else {
            $client_time_change = "";
        }
        if (isset($_POST['client_resident_name'])) {
            $client_resident_name = $_POST['client_resident_name'];
        } elseif (isset($_GET['client_resident_name'])) {
            $client_resident_name = $_GET['client_resident_name'];
        } else {
            $client_resident_name = "";
        }
        if (isset($_POST['client_resident_phone'])) {
            $client_resident_phone = $_POST['client_resident_phone'];
        } elseif (isset($_GET['client_resident_phone'])) {
            $client_resident_phone = $_GET['client_resident_phone'];
        } else {
            $client_resident_phone = "";
        }

        $result = $ajax->update_customer($gender, $house_address_serialize, $client_occupation, $client_company, $client_income, $client_room_type, $client_rent, $client_reason_change, $client_time_change, $client_resident_name, $client_resident_phone, $client_id, $order_id);
        if ($result)
            echo "success";
        else
            echo "fail";
    }
    if ($task == 'history') {

        if (isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
        } elseif (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            $order_id = "";
        }
        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
        } elseif (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
        } else {
            $client_id = "";
        }
        if (isset($_POST['source_id'])) {
            $source_id = $_POST['source_id'];
        } elseif (isset($_GET['source_id'])) {
            $source_id = $_GET['source_id'];
        } else {
            $source_id = "";
        }

        if (isset($_POST['log_time_call'])) {
            $log_time_call = $_POST['log_time_call'];
        } elseif (isset($_GET['log_time_call'])) {
            $log_time_call = $_GET['log_time_call'];
        } else {
            $log_time_call = "";
        }
        if (isset($_POST['log_time_arrive_company'])) {
            $log_time_arrive_company = $_POST['log_time_arrive_company'];
        } elseif (isset($_GET['log_time_arrive_company'])) {
            $log_time_arrive_company = $_GET['log_time_arrive_company'];
        } else {
            $log_time_arrive_company = "";
        }
        if (isset($_POST['log_time_mail'])) {
            $log_time_mail = $_POST['log_time_mail'];
        } elseif (isset($_GET['log_time_mail'])) {
            $log_time_mail = $_GET['log_time_mail'];
        } else {
            $log_time_mail = "";
        }
        if (isset($_POST['log_tel'])) {
            $log_tel = $_POST['log_tel'];
        } elseif (isset($_GET['log_tel'])) {
            $log_tel = $_GET['log_tel'];
        } else {
            $log_tel = "";
        }
        if (isset($_POST['log_tel_status'])) {
            $log_tel_status = $_POST['log_tel_status'];
        } elseif (isset($_GET['log_tel_status'])) {
            $log_tel_status = $_GET['log_tel_status'];
        } else {
            $log_tel_status = "";
        }
        if (isset($_POST['log_mail'])) {
            $log_mail = $_POST['log_mail'];
        } elseif (isset($_GET['log_mail'])) {
            $log_mail = $_GET['log_mail'];
        } else {
            $log_mail = "";
        }
        if (isset($_POST['log_comment'])) {
            $log_comment = $_POST['log_comment'];
        } elseif (isset($_GET['log_comment'])) {
            $log_comment = $_GET['log_comment'];
        } else {
            $log_comment = "";
        }
        if (isset($_POST['log_date_appointment_from'])) {
            $log_date_appointment_from = $_POST['log_date_appointment_from'];
        } elseif (isset($_GET['log_date_appointment_from'])) {
            $log_date_appointment_from = $_GET['log_date_appointment_from'];
        } else {
            $log_date_appointment_from = "";
        }
        if (isset($_POST['log_date_appointment_to'])) {
            $log_date_appointment_to = $_POST['log_date_appointment_to'];
        } elseif (isset($_GET['log_date_appointment_to'])) {
            $log_date_appointment_to = $_GET['log_date_appointment_to'];
        } else {
            $log_date_appointment_to = "";
        }


        if (isset($_POST['log_mail_status'])) {
            $log_mail_status = $_POST['log_mail_status'];
        } elseif (isset($_GET['log_mail_status'])) {
            $log_mail_status = $_GET['log_mail_status'];
        } else {
            $log_mail_status = "";
        }
        if (isset($_POST['log_contact_head_office'])) {
            $log_contact_head_office = $_POST['log_contact_head_office'];
        } elseif (isset($_GET['log_contact_head_office'])) {
            $log_contact_head_office = $_GET['log_contact_head_office'];
        } else {
            $log_contact_head_office = "";
        }
        if (isset($_POST['log_shop_sign'])) {
            $log_shop_sign = $_POST['log_shop_sign'];
        } elseif (isset($_GET['log_shop_sign'])) {
            $log_shop_sign = $_GET['log_shop_sign'];
        } else {
            $log_shop_sign = "";
        }
        if (isset($_POST['log_local_sign'])) {
            $log_local_sign = $_POST['log_local_sign'];
        } elseif (isset($_GET['log_local_sign'])) {
            $log_local_sign = $_GET['log_local_sign'];
        } else {
            $log_local_sign = "";
        }
        if (isset($_POST['log_introduction'])) {
            $log_introduction = $_POST['log_introduction'];
        } elseif (isset($_GET['log_introduction'])) {
            $log_introduction = $_GET['log_introduction'];
        } else {
            $log_introduction = "";
        }
        if (isset($_POST['log_flyer'])) {
            $log_flyer = $_POST['log_flyer'];
        } elseif (isset($_GET['log_flyer'])) {
            $log_flyer = $_GET['log_flyer'];
        } else {
            $log_flyer = "";
        }
        if (isset($_POST['log_line'])) {
            $log_line = $_POST['log_line'];
        } elseif (isset($_GET['log_line'])) {
            $log_line = $_GET['log_line'];
        } else {
            $log_line = "";
        }
        if (isset($_POST['log_revisit'])) {
            $log_revisit = $_POST['log_revisit'];
        } elseif (isset($_GET['log_revisit'])) {
            $log_revisit = $_GET['log_revisit'];
        } else {
            $log_revisit = "";
        }
        if (isset($_POST['log_status_appointment'])) {
            $log_status_appointment = $_POST['log_status_appointment'];
        } elseif (isset($_GET['log_status_appointment'])) {
            $log_status_appointment = $_GET['log_status_appointment'];
        } else {
            $log_status_appointment = "";
        }

        $result = $ajax->update_history($log_time_call, $log_time_arrive_company, $log_time_mail, $log_tel, $log_tel_status, $log_mail, $log_comment, $log_date_appointment_from, $log_date_appointment_to, $log_mail_status, $log_contact_head_office, $log_shop_sign, $log_local_sign, $log_introduction, $log_flyer, $log_line, $log_revisit, $source_id, $log_status_appointment, $client_id, $order_id);
        echo json_encode($result);
    }
    if ($task == 'aspirations') {

        if (isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
        } elseif (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            $order_id = "";
        }
        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
        } elseif (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
        } else {
            $client_id = "";
        }


        if (isset($_POST['aspirations_type_house'])) {
            $aspirations_type_house = $_POST['aspirations_type_house'];
        } elseif (isset($_GET['aspirations_type_house'])) {
            $aspirations_type_house = $_GET['aspirations_type_house'];
        } else {
            $aspirations_type_house = "";
        }
        if (isset($_POST['aspirations_type_room'])) {
            $aspirations_type_room = $_POST['aspirations_type_room'];
        } elseif (isset($_GET['aspirations_type_room'])) {
            $aspirations_type_room = $_GET['aspirations_type_room'];
        } else {
            $aspirations_type_room = "";
        }
        if (isset($_POST['aspirations_build_time'])) {
            $aspirations_build_time = $_POST['aspirations_build_time'];
        } elseif (isset($_GET['aspirations_build_time'])) {
            $aspirations_build_time = $_GET['aspirations_build_time'];
        } else {
            $aspirations_build_time = "";
        }
        if (isset($_POST['aspirations_area'])) {
            $aspirations_area = $_POST['aspirations_area'];
        } elseif (isset($_GET['aspirations_area'])) {
            $aspirations_area = $_GET['aspirations_area'];
        } else {
            $aspirations_area = "";
        }
        if (isset($_POST['aspirations_size'])) {
            $aspirations_size = $_POST['aspirations_size'];
        } elseif (isset($_GET['aspirations_size'])) {
            $aspirations_size = $_GET['aspirations_size'];
        } else {
            $aspirations_size = "";
        }
        if (isset($_POST['aspirations_rent_cost'])) {
            $aspirations_rent_cost = $_POST['aspirations_rent_cost'];
        } elseif (isset($_GET['aspirations_rent_cost'])) {
            $aspirations_rent_cost = $_GET['aspirations_rent_cost'];
        } else {
            $aspirations_rent_cost = "";
        }
        if (isset($_POST['aspirations_comment'])) {
            $aspirations_comment = $_POST['aspirations_comment'];
        } elseif (isset($_GET['aspirations_comment'])) {
            $aspirations_comment = $_GET['aspirations_comment'];
        } else {
            $aspirations_comment = "";
        }


        $result = $ajax->update_aspirations($aspirations_type_house, $aspirations_type_room, $aspirations_build_time, $aspirations_area, $aspirations_size, $aspirations_rent_cost, $aspirations_comment, $client_id, $order_id);
        echo json_encode($result);
    }
    if ($task == 'introduce') {

        if (isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
        } elseif (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            $order_id = "";
        }
        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
        } elseif (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
        } else {
            $client_id = "";
        }
        if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
        if (isset($_POST['room_id'])) {
            $room_id = $_POST['room_id'];
        } elseif (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = "";
        }
        if (isset($_POST['introduce_house_content'])) {
            $introduce_house_content = $_POST['introduce_house_content'];
        } elseif (isset($_GET['introduce_house_content'])) {
            $introduce_house_content = $_GET['introduce_house_content'];
        } else {
            $introduce_house_content = "";
        }
        $result = $ajax->update_introduce($house_id, $room_id, $introduce_house_content, $client_id, $order_id);
        echo json_encode($result);
    }
    if ($task == 'contract') {
        if (isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];
        } elseif (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            $order_id = "";
        }
        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
        } elseif (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
        } else {
            $client_id = "";
        }
        if (isset($_POST['contract_name'])) {
            $contract_name = $_POST['contract_name'];
        } elseif (isset($_GET['contract_name'])) {
            $contract_name = $_GET['contract_name'];
        } else {
            $contract_name = "";
        }
        if (isset($_POST['contract_cost'])) {
            $contract_cost = $_POST['contract_cost'];
        } elseif (isset($_GET['contract_cost'])) {
            $contract_cost = $_GET['contract_cost'];
        } else {
            $contract_cost = "";
        }

        if (isset($_POST['contract_key_money'])) {
            $contract_key_money = $_POST['contract_key_money'];
        } elseif (isset($_GET['contract_key_money'])) {
            $contract_key_money = $_GET['contract_key_money'];
        } else {
            $contract_key_money = "";
        }
        if (isset($_POST['contract_condition'])) {
            $contract_condition = $_POST['contract_condition'];
        } elseif (isset($_GET['contract_condition'])) {
            $contract_condition = $_GET['contract_condition'];
        } else {
            $contract_condition = "";
        }
        if (isset($_POST['contract_valuation'])) {
            $contract_valuation = $_POST['contract_valuation'];
        } elseif (isset($_GET['contract_valuation'])) {
            $contract_valuation = $_GET['contract_valuation'];
        } else {
            $contract_valuation = "";
        }
        if (isset($_POST['contract_signature_day'])) {
            $contract_signature_day = $_POST['contract_signature_day'];
        } elseif (isset($_GET['contract_signature_day'])) {
            $contract_signature_day = $_GET['contract_signature_day'];
        } else {
            $contract_signature_day = "";
        }
        if (isset($_POST['contract_handover_day'])) {
            $contract_handover_day = $_POST['contract_handover_day'];
        } elseif (isset($_GET['contract_handover_day'])) {
            $contract_handover_day = $_GET['contract_handover_day'];
        } else {
            $contract_handover_day = "";
        }
        if (isset($_POST['contract_period_from'])) {
            $contract_period_from = $_POST['contract_period_from'];
        } elseif (isset($_GET['contract_period_from'])) {
            $contract_period_from = $_GET['contract_period_from'];
        } else {
            $contract_period_from = "";
        }
        if (isset($_POST['contract_period_to'])) {
            $contract_period_to = $_POST['contract_period_to'];
        } elseif (isset($_GET['contract_period_to'])) {
            $contract_period_to = $_GET['contract_period_to'];
        } else {
            $contract_period_to = "";
        }
        if (isset($_POST['contract_deposit_1'])) {
            $contract_deposit_1 = $_POST['contract_deposit_1'];
        } elseif (isset($_GET['contract_deposit_1'])) {
            $contract_deposit_1 = $_GET['contract_deposit_1'];
        } else {
            $contract_deposit_1 = "";
        }
        if (isset($_POST['contract_deposit_2'])) {
            $contract_deposit_2 = $_POST['contract_deposit_2'];
        } elseif (isset($_GET['contract_deposit_2'])) {
            $contract_deposit_2 = $_GET['contract_deposit_2'];
        } else {
            $contract_deposit_2 = "";
        }
        if (isset($_POST['contract_cancel'])) {
            $contract_cancel = $_POST['contract_cancel'];
        } elseif (isset($_GET['contract_cancel'])) {
            $contract_cancel = $_GET['contract_cancel'];
        } else {
            $contract_cancel = "";
        }
        if (isset($_POST['contract_total'])) {
            $contract_total = $_POST['contract_total'];
        } elseif (isset($_GET['contract_total'])) {
            $contract_total = $_GET['contract_total'];
        } else {
            $contract_total = "";
        }
        if (isset($_POST['contract_application'])) {
            $contract_application = $_POST['contract_application'];
        } elseif (isset($_GET['contract_application'])) {
            $contract_application = $_GET['contract_application'];
        } else {
            $contract_application = "";
        }
        if (isset($_POST['contract_application_date'])) {
            $contract_application_date = $_POST['contract_application_date'];
        } elseif (isset($_GET['contract_application_date'])) {
            $contract_application_date = $_GET['contract_application_date'];
        } else {
            $contract_application_date = "";
        }
        if (isset($_POST['contract_payment_date_from'])) {
            $contract_payment_date_from = $_POST['contract_payment_date_from'];
        } elseif (isset($_GET['contract_payment_date_from'])) {
            $contract_payment_date_from = $_GET['contract_payment_date_from'];
        } else {
            $contract_payment_date_from = "";
        }
        if (isset($_POST['contract_payment_date_to'])) {
            $contract_payment_date_to = $_POST['contract_payment_date_to'];
        } elseif (isset($_GET['contract_payment_date_to'])) {
            $contract_payment_date_to = $_GET['contract_payment_date_to'];
        } else {
            $contract_payment_date_to = "";
        }
        if (isset($_POST['contract_payment_status'])) {
            $contract_payment_status = $_POST['contract_payment_status'];
        } elseif (isset($_GET['contract_payment_status'])) {
            $contract_payment_status = $_GET['contract_payment_status'];
        } else {
            $contract_payment_status = "";
        }
        if (isset($_POST['contract_payment_report'])) {
            $contract_payment_report = $_POST['contract_payment_report'];
        } elseif (isset($_GET['contract_payment_report'])) {
            $contract_payment_report = $_GET['contract_payment_report'];
        } else {
            $contract_payment_report = "";
        }
        if (isset($_POST['contract_broker_fee'])) {
            $contract_broker_fee = $_POST['contract_broker_fee'];
        } elseif (isset($_GET['contract_broker_fee'])) {
            $contract_broker_fee = $_GET['contract_broker_fee'];
        } else {
            $contract_broker_fee = "";
        }
        if (isset($_POST['contract_broker_fee_unit'])) {
            $contract_broker_fee_unit = $_POST['contract_broker_fee_unit'];
        } elseif (isset($_GET['contract_broker_fee_unit'])) {
            $contract_broker_fee_unit = $_GET['contract_broker_fee_unit'];
        } else {
            $contract_broker_fee_unit = "";
        }
        if (isset($_POST['contract_ads_fee'])) {
            $contract_ads_fee = $_POST['contract_ads_fee'];
        } elseif (isset($_GET['contract_ads_fee'])) {
            $contract_ads_fee = $_GET['contract_ads_fee'];
        } else {
            $contract_ads_fee = "";
        }
        if (isset($_POST['contract_ads_fee_unit'])) {
            $contract_ads_fee_unit = $_POST['contract_ads_fee_unit'];
        } elseif (isset($_GET['contract_ads_fee_unit'])) {
            $contract_ads_fee_unit = $_GET['contract_ads_fee_unit'];
        } else {
            $contract_ads_fee_unit = "";
        }
        if (isset($_POST['contract_transaction_finish'])) {
            $contract_transaction_finish = $_POST['contract_transaction_finish'];
        } elseif (isset($_GET['contract_transaction_finish'])) {
            $contract_transaction_finish = $_GET['contract_transaction_finish'];
        } else {
            $contract_transaction_finish = "";
        }
        if (isset($_POST['label'])) {
            $label = $_POST['label'];
        } elseif (isset($_GET['label'])) {
            $label = $_GET['label'];
        } else {
            $label = "";
        }
        if (isset($_POST['plus_money'])) {
            $plus_money = $_POST['plus_money'];
        } elseif (isset($_GET['plus_money'])) {
            $plus_money = $_GET['plus_money'];
        } else {
            $plus_money = NULL;
        }

        if (isset($_POST['plus_money_unit'])) {
            $plus_money_unit = $_POST['plus_money_unit'];
        } elseif (isset($_GET['plus_money_unit'])) {
            $plus_money_unit = $_GET['plus_money_unit'];
        } else {
            $plus_money_unit = NULL;
        }
        if (isset($_POST['contract_key_money_unit'])) {
            $contract_key_money_unit = $_POST['contract_key_money_unit'];
        } elseif (isset($_GET['contract_key_money_unit'])) {
            $contract_key_money_unit = $_GET['contract_key_money_unit'];
        } else {
            $contract_key_money_unit = "";
        }
        if (isset($_POST['contract_deposit1_money_unit'])) {
            $contract_deposit1_money_unit = $_POST['contract_deposit1_money_unit'];
        } elseif (isset($_GET['contract_deposit1_money_unit'])) {
            $contract_deposit1_money_unit = $_GET['contract_deposit1_money_unit'];
        } else {
            $contract_deposit1_money_unit = "";
        }
        if (isset($_POST['contract_deposit2_money_unit'])) {
            $contract_deposit2_money_unit = $_POST['contract_deposit2_money_unit'];
        } elseif (isset($_GET['contract_deposit2_money_unit'])) {
            $contract_deposit2_money_unit = $_GET['contract_deposit2_money_unit'];
        } else {
            $contract_deposit2_money_unit = "";
        }

        $result = $ajax->update_contract($contract_name, $contract_cost, $contract_key_money, $contract_condition, $contract_valuation, $contract_signature_day, $contract_handover_day, $contract_period_from, $contract_period_to, $contract_deposit_1, $contract_deposit_2, $contract_cancel, $contract_total, $contract_application, $contract_application_date,$contract_broker_fee,$contract_broker_fee_unit,$contract_ads_fee,$contract_ads_fee_unit,$contract_transaction_finish,$contract_payment_date_from,$contract_payment_date_to,$contract_payment_status,$contract_payment_report, $label, $plus_money, $plus_money_unit, $contract_key_money_unit, $contract_deposit1_money_unit, $contract_deposit2_money_unit, $client_id, $order_id);
        echo json_encode($result);
    }
    if ($task == 'selectCustomer') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } elseif (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $id = "";
        }
        $result = $ajax->getCustomerSelected($id);
        echo json_encode($result);
    }
} elseif ($action == 'add_room') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == 'checkRoomExist') {
        if (isset($_POST['room_id'])) {
            $room_id = $_POST['room_id'];
        } elseif (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = "";
        }
        if (isset($_POST['broker_id'])) {
            $broker_id = $_POST['broker_id'];
        } elseif (isset($_GET['broker_id'])) {
            $broker_id = $_GET['broker_id'];
        } else {
            $broker_id = "";
        }
        if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
        $result = $ajax->checkRoomExist($house_id, $room_id, $broker_id);
        echo json_encode($result);
    }
} elseif ($action == 'deleteRoom') {
    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } elseif (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "";
    }
    if (isset($_POST['broker_id'])) {
        $broker_id = $_POST['broker_id'];
    } elseif (isset($_GET['broker_id'])) {
        $broker_id = $_GET['broker_id'];
    } else {
        $broker_id = "";
    }
    $result = $ajax->deleteRoom($id, $broker_id, $house_id);
    echo $result;
} elseif ($action == 'deleteSource') {
    if (isset($_POST['source_id'])) {
        $source_id = $_POST['source_id'];
    } elseif (isset($_GET['source_id'])) {
        $source_id = $_GET['source_id'];
    } else {
        $source_id = "";
    }

    $result = $ajax->deleteSource($source_id);
    echo $result;
} elseif ($action == 'create_house') {
    if (isset($_POST['task'])) {
        $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
        $task = $_GET['task'];
    } else {
        $task = "";
    }
    if ($task == 'getDistrictList') {
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
            $district_id = "";
        }
        $result = $ajax->getDistrictListByCityID($city_id);

        if ($result) {
            echo "<option value''></option>";
            for ($i = 0; $i < count($result); $i++) {

                echo "<option value='{$result[$i]['id']}'";
                if ($result[$i]['id'] == $district_id)
                    echo "selected='selected'";
                echo ">";
                echo "{$result[$i]['district_name']}</option>";
            }
        }
    }
    if ($task == 'getStreetList') {
        if (isset($_POST['district_id'])) {
            $district_id = $_POST['district_id'];
        } elseif (isset($_GET['district_id'])) {
            $district_id = $_GET['district_id'];
        } else {
            $district_id = "";
        }
        if (isset($_POST['street_id'])) {
            $street_id = $_POST['street_id'];
        } elseif (isset($_GET['street_id'])) {
            $street_id = $_GET['street_id'];
        } else {
            $street_id = "";
        }
        $result = $ajax->getStreetListByDistrictID($district_id);

        if ($result) {
            echo "<option value''></option>";
            for ($i = 0; $i < count($result); $i++) {

                echo "<option value='{$result[$i]['id']}'";
                if ($result[$i]['id'] == $street_id)
                    echo "selected='selected'";
                echo ">";
                echo "{$result[$i]['street_name']}</option>";
            }
        }
    }
    if ($task == 'getWardList') {
        if (isset($_POST['street_id'])) {
            $street_id = $_POST['street_id'];
        } elseif (isset($_GET['street_id'])) {
            $street_id = $_GET['street_id'];
        } else {
            $street_id = "";
        }
        if (isset($_POST['ward_id'])) {
            $ward_id = $_POST['ward_id'];
        } elseif (isset($_GET['ward_id'])) {
            $ward_id = $_GET['ward_id'];
        } else {
            $ward_id = "";
        }
        $result = $ajax->getWardListByStreetID($street_id);

        if ($result) {
            echo "<option value''></option>";
            for ($i = 0; $i < count($result); $i++) {

                echo "<option value='{$result[$i]['id']}'";
                if ($result[$i]['id'] == $ward_id)
                    echo "selected='selected'";
                echo ">";
                echo "{$result[$i]['ward_name']}</option>";
            }
        }
    }
}


