<?php

include "header.php";
include_once 'include/class_ajax.php';

$page = "create_order";
$error = null;
$exist = "";
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}

if (!@HOMEOrder::checkPermisson('create-order')) {
    header('Location: ./restrict.php');
    exit();
}
if (isset($_POST['step'])) {
    $step = $_POST['step'];
} elseif (isset($_GET['step'])) {
    $step = $_GET['step'];
} else {
    $step = 2;
}

if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
}
$house = new HOMEHouse();
if ($step == 1) {
    $broker = new HOMEBroker();
    $brokers = $broker->getAllBroker();
    $smarty->assign('brokers', $brokers);
} elseif ($step == 2) {

//    if (empty($broker_id)) {
//        $error[] = "Please choose Source to continue !!!";
//        $step = 1;
//        $broker = new HOMEBroker();
//        $brokers = $broker->getAllBroker();
//        $smarty->assign('brokers', $brokers);
//    }
    // valude for to back
    if (isset($_POST['brokerClick'])) {
        $brokerClick = $_POST['brokerClick'];
    } elseif (isset($_GET['brokerClick'])) {
        $brokerClick = $_GET['brokerClick'];
    } else {
        $brokerClick = "";
    }
    if (isset($_POST['houseClick'])) {
        $houseClick = $_POST['houseClick'];
    } elseif (isset($_GET['houseClick'])) {
        $houseClick = $_GET['houseClick'];
    } else {
        $houseClick = "";
    }
    if (isset($_POST['staff_id'])) {
        $staff_id = $_POST['staff_id'];
    } elseif (isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];
    } else {
        $staff_id = "";
    }

    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }
    //order part
    if (isset($_POST['order_name'])) {
        $order_name = $_POST['order_name'];
    } elseif (isset($_GET['order_name'])) {
        $order_name = $_GET['order_name'];
    } else {
        $order_name = "";
    }
    //Set order name auto
    $order = new HOMEOrder();
    $order_name = $order->generate_order_name();

    if (isset($_POST['order_rent_cost'])) {
        $order_rent_cost = $_POST['order_rent_cost'];
    } elseif (isset($_GET['order_rent_cost'])) {
        $order_rent_cost = $_GET['order_rent_cost'];
    } else {
        $order_rent_cost = "";
    }
    if (isset($_POST['order_comment'])) {
        $order_comment = $_POST['order_comment'];
    } elseif (isset($_GET['order_comment'])) {
        $order_comment = $_GET['order_comment'];
    } else {
        $order_comment = "";
    }
    if (isset($_POST['room_id'])) {
        $room_id = $_POST['room_id'];
    } elseif (isset($_GET['room_id'])) {
        $room_id = $_GET['room_id'];
    } else {
        $room_id = "";
    }
    if (isset($_POST['house_description'])) {
        $house_description = $_POST['house_description'];
    } elseif (isset($_GET['house_description'])) {
        $house_description = $_GET['house_description'];
    } else {
        $house_description = "";
    }
    $smarty->assign('house_description', $house_description);
    $smarty->assign('order_name', $order_name);
    $smarty->assign('order_rent_cost', $order_rent_cost);
    $smarty->assign('order_comment', $order_comment);
    $smarty->assign('staff_id', $staff_id);
    $smarty->assign('room_id', $room_id);
    $smarty->assign('house_id', $house_id);
    $smarty->assign('broker_id', $broker_id);
    $smarty->assign('brokerClick', $brokerClick);
    $smarty->assign('houseClick', $houseClick);
    //end
    //$house = new HOMEHouse();
    $houses = $house->getHouses();
    $users = $user->getAllUsers();
    $broker = new HOMEBroker();
    $brokers = $broker->getAllBroker();
    $smarty->assign('brokers', $brokers);
    $smarty->assign('houses', $houses);
    $smarty->assign('users', $users);
    $smarty->assign('loged_id', $user->user_info['id']);
} elseif ($step == 'verify') {

    if (isset($_POST['staff_id'])) {
        $staff_id = $_POST['staff_id'];
    } elseif (isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];
    } else {
        $staff_id = "";
    }

    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
    }
    //order part
    if (isset($_POST['order_name'])) {
        $order_name = $_POST['order_name'];
    } elseif (isset($_GET['order_name'])) {
        $order_name = $_GET['order_name'];
    } else {
        $order_name = "";
    }
    //Set order name auto
    $order = new HOMEOrder();
    $order_name = $order->generate_order_name();

    if (isset($_POST['order_rent_cost'])) {
        $order_rent_cost = $_POST['order_rent_cost'];
    } elseif (isset($_GET['order_rent_cost'])) {
        $order_rent_cost = $_GET['order_rent_cost'];
    } else {
        $order_rent_cost = "";
    }
    if (isset($_POST['order_comment'])) {
        $order_comment = $_POST['order_comment'];
    } elseif (isset($_GET['order_comment'])) {
        $order_comment = $_GET['order_comment'];
    } else {
        $order_comment = "";
    }
    if (isset($_POST['room_id'])) {
        $room_id = $_POST['room_id'];
    } elseif (isset($_GET['room_id'])) {
        $room_id = $_GET['room_id'];
    } else {
        $room_id = 0;
    }
    $broker = new HOMEBroker();
    $brokers = $broker->getBrokerById($broker_id);
    //$house = new HOMEHouse();
    $houses = $house->getHouseById($house_id);
    $staff = new HOMEUser();
    $staffs = $staff->getAccountById($staff_id);

    $smarty->assign('order_name', $order_name);
    $smarty->assign('order_rent_cost', $order_rent_cost);
    $smarty->assign('order_comment', $order_comment);
    $smarty->assign('brokers', $brokers);
    $smarty->assign('houses', $houses);
    $smarty->assign('staffs', $staffs);
    $smarty->assign('room_id', $room_id);
} elseif ($step == 'registry') {

    $errorHouseExist = "";
    $order = new HOMEOrder();
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
    } elseif (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    } else {
        $order_id = "";
    }
    if (isset($_POST['tab_room_id'])) {
        $tab_room_id = $_POST['tab_room_id'];
    } elseif (isset($_GET['tab_room_id'])) {
        $tab_room_id = $_GET['tab_room_id'];
    } else {
        $tab_room_id = "";
    }
    if (isset($_POST['tab_broker_id'])) {
        $tab_broker_id = $_POST['tab_broker_id'];
    } elseif (isset($_GET['tab_broker_id'])) {
        $tab_broker_id = $_GET['tab_broker_id'];
    } else {
        $tab_broker_id = "";
    }
    if (isset($_POST['tab_order_name'])) {
        $tab_order_name = $_POST['tab_order_name'];
    } elseif (isset($_GET['tab_order_name'])) {
        $tab_order_name = $_GET['tab_order_name'];
    } else {
        $tab_order_name = "";
    }
    if (isset($_POST['tab_order_rent_cost'])) {
        $tab_order_rent_cost = $_POST['tab_order_rent_cost'];
    } elseif (isset($_GET['tab_order_rent_cost'])) {
        $tab_order_rent_cost = $_GET['tab_order_rent_cost'];
    } else {
        $tab_order_rent_cost = "";
    }
    if (isset($_POST['tab_order_comment'])) {
        $tab_order_comment = $_POST['tab_order_comment'];
    } elseif (isset($_GET['tab_order_comment'])) {
        $tab_order_comment = $_GET['tab_order_comment'];
    } else {
        $tab_order_comment = "";
    }


    if (isset($_POST['tab_house_id'])) {
        $tab_house_id = $_POST['tab_house_id'];
    } elseif (isset($_GET['tab_house_id'])) {
        $tab_house_id = $_GET['tab_house_id'];
    } else {
        $tab_house_id = "";
    }
    if (isset($_POST['tab_house_description'])) {
        $tab_house_description = $_POST['tab_house_description'];
    } elseif (isset($_GET['tab_house_description'])) {
        $tab_house_description = $_GET['tab_house_description'];
    } else {
        $tab_house_description = "";
    }
    if (isset($_POST['change_house_array'])) {
        $change_house_array = $_POST['change_house_array'];
    } elseif (isset($_GET['change_house_array'])) {
        $change_house_array = $_GET['change_house_array'];
    } else {
        $change_house_array = "";
    }
    if (isset($_POST['house_id_bk'])) {
        $house_id_bk = $_POST['house_id_bk'];
    } elseif (isset($_GET['house_id_bk'])) {
        $house_id_bk = $_GET['house_id_bk'];
    } else {
        $house_id_bk = "";
    }
    if (isset($_POST['broker_id_bk'])) {
        $broker_id_bk = $_POST['broker_id_bk'];
    } elseif (isset($_GET['broker_id_bk'])) {
        $broker_id_bk = $_GET['broker_id_bk'];
    } else {
        $broker_id_bk = "";
    }
    if (isset($_POST['room_id_bk'])) {
        $room_id_bk = $_POST['room_id_bk'];
    } elseif (isset($_GET['room_id_bk'])) {
        $room_id_bk = $_GET['room_id_bk'];
    } else {
        $room_id_bk = "";
    }
    ///create order
    if (isset($_POST['registry'])) {

        //get create day
        $order_day_create = time();
        if (isset($_POST['room_id'])) {
            $room_id = $_POST['room_id'];
        } elseif (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = "";
        }
        $tab_room_id = $room_id;
        if (isset($_POST['order_name'])) {
            $order_name = $_POST['order_name'];
        } elseif (isset($_GET['order_name'])) {
            $order_name = $_GET['order_name'];
        } else {
            $order_name = "";
        }
        $tab_order_name = $order_name;
        if (isset($_POST['order_rent_cost'])) {
            $order_rent_cost = $_POST['order_rent_cost'];
        } elseif (isset($_GET['order_rent_cost'])) {
            $order_rent_cost = $_GET['order_rent_cost'];
        } else {
            $order_rent_cost = "";
        }
        $tab_order_rent_cost = $order_rent_cost;
        if (isset($_POST['order_comment'])) {
            $order_comment = $_POST['order_comment'];
        } elseif (isset($_GET['order_comment'])) {
            $order_comment = $_GET['order_comment'];
        } else {
            $order_comment = "";
        }
        $tab_order_comment = $order_comment;
        if (isset($_POST['create_id'])) {
            $create_id = $_POST['create_id'];
        } elseif (isset($_GET['create_id'])) {
            $create_id = $_GET['create_id'];
        } else {
            $create_id = "";
        }
        if (isset($_POST['house_id'])) {
            $house_id = $_POST['house_id'];
        } elseif (isset($_GET['house_id'])) {
            $house_id = $_GET['house_id'];
        } else {
            $house_id = "";
        }
        if (isset($_POST['house_description'])) {
            $house_description = $_POST['house_description'];
        } elseif (isset($_GET['house_description'])) {
            $house_description = $_GET['house_description'];
        } else {
            $house_description = "";
        }
        $tab_house_description = $house_description;

        $tab_house_id = $house_id;
        $tab_broker_id = $broker_id;
        $result = $order->create_order($room_id, $order_name, $order_rent_cost, $order_comment, $create_id, $house_id, $broker_id, $order_day_create);
        //print_r($result);die();
        if (isset($result['id'])) {
            $order_id = $result['id'];
        } elseif (isset($result['error'])) {
            $errorHouseExist = $order_id = $result['error'];
        }
    }


    if (isset($_POST['filter'])) {
        $filter = $_POST['filter'];
    } elseif (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    } else {
        $filter = "";
    }
    if (isset($_POST['page_number'])) {
        $page_number = $_POST['page_number'];
    } elseif (isset($_GET['page_number'])) {
        $page_number = $_GET['page_number'];
    } else {
        $page_number = 1;
    }

    if (isset($_POST['client_id'])) {
        $client_id = $_POST['client_id'];
    } elseif (isset($_GET['client_id'])) {
        $client_id = $_GET['client_id'];
    } else {
        $client_id = "";
    }
////////////////////////////////////////Basic/////////////////////////////////////////////////
    if (isset($_POST['client_name'])) {
        $client_name = $_POST['client_name'];
    } elseif (isset($_GET['client_name'])) {
        $client_name = $_GET['client_name'];
    } else {
        $client_name = "";
    }
    if (isset($_POST['client_birthday'])) {
        $client_birthday = $_POST['client_birthday'];
    } elseif (isset($_GET['client_birthday'])) {
        $client_birthday = $_GET['client_birthday'];
    } else {
        $client_birthday = "";
    }
    if (isset($_POST['client_read_way'])) {
        $client_read_way = $_POST['client_read_way'];
    } elseif (isset($_GET['client_read_way'])) {
        $client_read_way = $_GET['client_read_way'];
    } else {
        $client_read_way = "";
    }
    if (isset($_POST['client_email'])) {
        $client_email = $_POST['client_email'];
    } elseif (isset($_GET['client_email'])) {
        $client_email = $_GET['client_email'];
    } else {
        $client_email = "";
    }
    if (isset($_POST['client_phone'])) {
        $client_phone = $_POST['client_phone'];
    } elseif (isset($_GET['client_phone'])) {
        $client_phone = $_GET['client_phone'];
    } else {
        $client_phone = "";
    }
    if (isset($_POST['client_fax'])) {
        $client_fax = $_POST['client_fax'];
    } elseif (isset($_GET['client_fax'])) {
        $client_fax = $_GET['client_fax'];
    } else {
        $client_fax = "";
    }
///////////////////////////////////////End Basic////////////////////////////////////////////
//////////////////////////////////////Begin Detail/////////////////////////////////////////
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
    if (isset($_POST['client_room_type_number'])) {
        $client_room_type_number = $_POST['client_room_type_number'];
    } elseif (isset($_GET['client_room_type_number'])) {
        $client_room_type_number = $_GET['client_room_type_number'];
    } else {
        $client_room_type_number = "";
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

//////////////////////////////////////End Detail///////////////////////////////////////////
/////////////////////////////////////Begin History///////////////////////////////////////
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

    if (isset($_POST['log_time_call_date'])) {
        $log_time_call_date = $_POST['log_time_call_date'];
    } elseif (isset($_GET['log_time_call_date'])) {
        $log_time_call_date = $_GET['log_time_call_date'];
    } else {
        $log_time_call_date = "";
    }
    if (isset($_POST['log_time_arrive_company_date'])) {
        $log_time_arrive_company_date = $_POST['log_time_arrive_company_date'];
    } elseif (isset($_GET['log_time_arrive_company_date'])) {
        $log_time_arrive_company_date = $_GET['log_time_arrive_company_date'];
    } else {
        $log_time_arrive_company_date = "";
    }
    if (isset($_POST['log_time_mail_date'])) {
        $log_time_mail_date = $_POST['log_time_mail_date'];
    } elseif (isset($_GET['log_time_mail_date'])) {
        $log_time_mail_date = $_GET['log_time_mail_date'];
    } else {
        $log_time_mail_date = "";
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
    if (isset($_POST['log_date_appointment_from_date'])) {
        $log_date_appointment_from_date = $_POST['log_date_appointment_from_date'];
    } elseif (isset($_GET['log_date_appointment_from_date'])) {
        $log_date_appointment_from_date = $_GET['log_date_appointment_from_date'];
    } else {
        $log_date_appointment_from_date = "";
    }
    if (isset($_POST['log_date_appointment_to'])) {
        $log_date_appointment_to = $_POST['log_date_appointment_to'];
    } elseif (isset($_GET['log_date_appointment_to'])) {
        $log_date_appointment_to = $_GET['log_date_appointment_to'];
    } else {
        $log_date_appointment_to = "";
    }
    if (isset($_POST['log_date_appointment_to_date'])) {
        $log_date_appointment_to_date = $_POST['log_date_appointment_to_date'];
    } elseif (isset($_GET['log_date_appointment_to_date'])) {
        $log_date_appointment_to_date = $_GET['log_date_appointment_to_date'];
    } else {
        $log_date_appointment_to_date = "";
    }
    if (isset($_POST['log_status_appointment'])) {
        $log_status_appointment = $_POST['log_status_appointment'];
    } elseif (isset($_GET['log_status_appointment'])) {
        $log_status_appointment = $_GET['log_status_appointment'];
    } else {
        $log_status_appointment = "";
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
    if (isset($_POST['source_id'])) {
        $source_id = $_POST['source_id'];
    } elseif (isset($_GET['source_id'])) {
        $source_id = $_GET['source_id'];
    } else {
        $source_id = "";
    }
////////////////////////////////////End History//////////////////////////////////////////    
///////////////////////////////////Begin Aspirations//////////////////////////////////
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
    if (isset($_POST['aspirations_type_room_number'])) {
        $aspirations_type_room_number = $_POST['aspirations_type_room_number'];
    } elseif (isset($_GET['aspirations_type_room_number'])) {
        $aspirations_type_room_number = $_GET['aspirations_type_room_number'];
    } else {
        $aspirations_type_room_number = "";
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
    if (isset($_POST['aspirations_area2'])) {
        $aspirations_area2 = $_POST['aspirations_area2'];
    } elseif (isset($_GET['aspirations_area2'])) {
        $aspirations_area2 = $_GET['aspirations_area2'];
    } else {
        $aspirations_area2 = "";
    }
    if (isset($_POST['aspirations_area3'])) {
        $aspirations_area3 = $_POST['aspirations_area3'];
    } elseif (isset($_GET['aspirations_area3'])) {
        $aspirations_area3 = $_GET['aspirations_area3'];
    } else {
        $aspirations_area3 = "";
    }
    if (isset($_POST['aspirations_size'])) {
        $aspirations_size = $_POST['aspirations_size'];
    } elseif (isset($_GET['aspirations_size'])) {
        $aspirations_size = $_GET['aspirations_size'];
    } else {
        $aspirations_size = "";
    }
    if (isset($_POST['aspirations_size2'])) {
        $aspirations_size2 = $_POST['aspirations_size2'];
    } elseif (isset($_GET['aspirations_size2'])) {
        $aspirations_size2 = $_GET['aspirations_size2'];
    } else {
        $aspirations_size2 = "";
    }

    if (isset($_POST['aspirations_rent_cost'])) {
        $aspirations_rent_cost = $_POST['aspirations_rent_cost'];
    } elseif (isset($_GET['aspirations_rent_cost'])) {
        $aspirations_rent_cost = $_GET['aspirations_rent_cost'];
    } else {
        $aspirations_rent_cost = "";
    }
    if (isset($_POST['aspirations_rent_cost2'])) {
        $aspirations_rent_cost2 = $_POST['aspirations_rent_cost2'];
    } elseif (isset($_GET['aspirations_rent_cost2'])) {
        $aspirations_rent_cost2 = $_GET['aspirations_rent_cost2'];
    } else {
        $aspirations_rent_cost2 = "";
    }
    if (isset($_POST['aspirations_comment'])) {
        $aspirations_comment = $_POST['aspirations_comment'];
    } elseif (isset($_GET['aspirations_comment'])) {
        $aspirations_comment = $_GET['aspirations_comment'];
    } else {
        $aspirations_comment = "";
    }
///////////////////////////////////End Aspirations///////////////////////////////////
//////////////////////////////////Begin Introduce///////////////////////////////////
    if (isset($_POST['introduce_house_id'])) {
        $introduce_house_id = $_POST['introduce_house_id'];
    } elseif (isset($_GET['introduce_house_id'])) {
        $introduce_house_id = $_GET['introduce_house_id'];
    } else {
        $introduce_house_id = "";
    }
    if (isset($_POST['introduce_room_id'])) {
        $introduce_room_id = $_POST['introduce_room_id'];
    } elseif (isset($_GET['introduce_room_id'])) {
        $introduce_room_id = $_GET['introduce_room_id'];
    } else {
        $introduce_room_id = "";
    }
    if (isset($_POST['introduce_house_content'])) {
        $introduce_house_content = $_POST['introduce_house_content'];
    } elseif (isset($_GET['introduce_house_content'])) {
        $introduce_house_content = $_GET['introduce_house_content'];
    } else {
        $introduce_house_content = "";
    }
    if (isset($_POST['introduce_house_photo'])) {
        $introduce_house_photo = $_POST['introduce_house_photo'];
    } elseif (isset($_GET['introduce_house_photo'])) {
        $introduce_house_photo = $_GET['introduce_house_photo'];
    } else {
        $introduce_house_photo = "";
    }

//////////////////////////////////End Introduce/////////////////////////////////////    
/////////////////////////////////Begin Contract////////////////////////////////////
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
//    if (isset($_POST['contract_plus_money'])) {
//        $contract_plus_money = $_POST['contract_plus_money'];
//    } elseif (isset($_GET['contract_plus_money'])) {
//        $contract_plus_money = $_GET['contract_plus_money'];
//    } else {
//        $contract_plus_money = "";
//    }
    if (isset($_POST['contract_key_money'])) {
        $contract_key_money = $_POST['contract_key_money'];
    } elseif (isset($_GET['contract_key_money'])) {
        $contract_key_money = $_GET['contract_key_money'];
    } else {
        $contract_key_money = "";
    }
    if (isset($_POST['contract_key_money_unit'])) {
        $contract_key_money_unit = $_POST['contract_key_money_unit'];
    } elseif (isset($_GET['contract_key_money_unit'])) {
        $contract_key_money_unit = $_GET['contract_key_money_unit'];
    } else {
        $contract_key_money_unit = "";
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
    if (isset($_POST['contract_signature_day_date'])) {
        $contract_signature_day_date = $_POST['contract_signature_day_date'];
    } elseif (isset($_GET['contract_signature_day_date'])) {
        $contract_signature_day_date = $_GET['contract_signature_day_date'];
    } else {
        $contract_signature_day_date = "";
    }
    if (isset($_POST['contract_handover_day'])) {
        $contract_handover_day = $_POST['contract_handover_day'];
    } elseif (isset($_GET['contract_handover_day'])) {
        $contract_handover_day = $_GET['contract_handover_day'];
    } else {
        $contract_handover_day = "";
    }
    if (isset($_POST['contract_handover_day_date'])) {
        $contract_handover_day_date = $_POST['contract_handover_day_date'];
    } elseif (isset($_GET['contract_handover_day_date'])) {
        $contract_handover_day_date = $_GET['contract_handover_day_date'];
    } else {
        $contract_handover_day_date = "";
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
    if (isset($_POST['contract_period_from_date'])) {
        $contract_period_from_date = $_POST['contract_period_from_date'];
    } elseif (isset($_GET['contract_period_from_date'])) {
        $contract_period_from_date = $_GET['contract_period_from_date'];
    } else {
        $contract_period_from_date = "";
    }
    if (isset($_POST['contract_period_to_date'])) {
        $contract_period_to_date = $_POST['contract_period_to_date'];
    } elseif (isset($_GET['contract_period_to_date'])) {
        $contract_period_to_date = $_GET['contract_period_to_date'];
    } else {
        $contract_period_to_date = "";
    }

    if (isset($_POST['contract_deposit_1'])) {
        $contract_deposit_1 = $_POST['contract_deposit_1'];
    } elseif (isset($_GET['contract_deposit_1'])) {
        $contract_deposit_1 = $_GET['contract_deposit_1'];
    } else {
        $contract_deposit_1 = "";
    }
    if (isset($_POST['contract_deposit1_money_unit'])) {
        $contract_deposit1_money_unit = $_POST['contract_deposit1_money_unit'];
    } elseif (isset($_GET['contract_deposit1_money_unit'])) {
        $contract_deposit1_money_unit = $_GET['contract_deposit1_money_unit'];
    } else {
        $contract_deposit1_money_unit = "";
    }
    if (isset($_POST['contract_deposit_2'])) {
        $contract_deposit_2 = $_POST['contract_deposit_2'];
    } elseif (isset($_GET['contract_deposit_2'])) {
        $contract_deposit_2 = $_GET['contract_deposit_2'];
    } else {
        $contract_deposit_2 = "";
    }
    if (isset($_POST['contract_deposit2_money_unit'])) {
        $contract_deposit2_money_unit = $_POST['contract_deposit2_money_unit'];
    } elseif (isset($_GET['contract_deposit2_money_unit'])) {
        $contract_deposit2_money_unit = $_GET['contract_deposit2_money_unit'];
    } else {
        $contract_deposit2_money_unit = "";
    }
    if (isset($_POST['contract_cancel'])) {
        $contract_cancel = $_POST['contract_cancel'];
    } elseif (isset($_GET['contract_cancel'])) {
        $contract_cancel = $_GET['contract_cancel'];
    } else {
        $contract_cancel = "";
    }
    if (isset($_POST['contract_cancel_date'])) {
        $contract_cancel_date = $_POST['contract_cancel_date'];
    } elseif (isset($_GET['contract_cancel_date'])) {
        $contract_cancel_date = $_GET['contract_cancel_date'];
    } else {
        $contract_cancel_date = "";
    }
    if (isset($_POST['contract_total'])) {
        $contract_total = $_POST['contract_total'];
    } elseif (isset($_GET['contract_total'])) {
        $contract_total = $_GET['contract_total'];
    } else {
        $contract_total = "";
    }

    if (isset($_POST['contract_ambition'])) {
        $contract_ambition = $_POST['contract_ambition'];
    } elseif (isset($_GET['contract_ambition'])) {
        $contract_ambition = $_GET['contract_ambition'];
    } else {
        $contract_ambition = "";
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
    if (isset($_POST['partner_id'])) {
        $partner_id = $_POST['partner_id'];
    } elseif (isset($_GET['partner_id'])) {
        $partner_id = $_GET['partner_id'];
    } else {
        $partner_id = "";
    }
    if (isset($_POST['partner_percent'])) {
        $partner_percent = $_POST['partner_percent'];
    } elseif (isset($_GET['partner_percent'])) {
        $partner_percent = $_GET['partner_percent'];
    } else {
        $partner_percent = "";
    }
    if (isset($_POST['partner_ads'])) {
        $partner_ads = $_POST['partner_ads'];
    } elseif (isset($_GET['partner_ads'])) {
        $partner_ads = $_GET['partner_ads'];
    } else {
        $partner_ads = "";
    }

    if (isset($_POST['money_payment'])) {
        $money_payment = $_POST['money_payment'];
    } elseif (isset($_GET['money_payment'])) {
        $money_payment = $_GET['money_payment'];
    } else {
        $money_payment = "";
    }

    if (isset($_POST['room_rented'])) {
        $room_rented = $_POST['room_rented'];
    } elseif (isset($_GET['room_rented'])) {
        $room_rented = $_GET['room_rented'];
    } else {
        $room_rented = "";
    }
    if (isset($_POST['room_administrative_expense'])) {
        $room_administrative_expense = $_POST['room_administrative_expense'];
    } elseif (isset($_GET['room_administrative_expense'])) {
        $room_administrative_expense = $_GET['room_administrative_expense'];
    } else {
        $room_ad_ex = 0;
        if (!empty($room_id) && !empty($house_id) && !empty($broker_id)) {
            include "include/class_detail.php";
            $detail = @HOMEDetail::getRoom($room_id, $house_id, $broker_id);
            if (!empty($detail) && is_array($detail)) {
                $room_ad_ex = rtrim($detail['room_administrative_expense'], '円');
            }
        }
        $room_administrative_expense = $room_ad_ex;
        //change 万 into 円
        // $room_administrative_expense=  str_replace("円", "", $room_administrative_expense);
        if (strpos($room_administrative_expense, '万')) {
            $room_exp = explode("万", $room_administrative_expense);
            $room_administrative_expense = ((int) $room_exp[0] * 10000 + ($room_exp[1] != "" ? $room_exp[1] : 0));
        }
        $room_administrative_expense = $room_administrative_expense != "" ? number_format($room_administrative_expense, 0, '', ',') : $room_administrative_expense;
    }
    if (isset($_POST['contract_total'])) {
        $contract_total = $_POST['contract_total'];
    } elseif (isset($_GET['contract_total'])) {
        $contract_total = $_GET['contract_total'];
    } else {
        $contract_total = "";
    }
    //active field when submit
    if (isset($_POST['keep_active_tab'])) {
        $keep_active_tab = $_POST['keep_active_tab'];
    } elseif (isset($_GET['keep_active_tab'])) {
        $keep_active_tab = $_GET['keep_active_tab'];
    } else {
        $keep_active_tab = "";
    }
    //end active field
    $plus_money = array();
/////////////////////////////////End Contract//////////////////////////////////////
    $customer = new HOMECustomer();
    //paging
    $max = 10;
    $totalItem = $customer->getTotalItem($filter);

    $totalPage = floor($totalItem / $max);

    if ($totalItem % $max > 0)
        $totalPage = $totalPage + 1;
    if ($page_number > $totalPage)
        $page_number = 1;


    $offset = $page_number * $max - $max;
    $length = $max;

    $customers = $customer->getCustomers($filter, $offset, $length);

    for ($i = 0; $i < count($customers); $i++) {
        if ($house->isSerialized($customers[$i]['client_address'])) {
            $house_address_serialize = unserialize($customers[$i]['client_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = $house_address_serialize['client_address'];
            $customers[$i]['client_address'] = $city_id_filter . $district_id_filter . $street_id_filter . $ward_id_filter . $house_address;
        } else {
            $customers[$i]['client_address'] = $customers[$i]['client_address'];
        }
    }
    //Introduce house
    //  $house = new HOMEHouse();
    $houses = $house->getHouses();
    //Store client's info
    if (!empty($_POST['export'])) {
        if (!empty($order_id)) {
            include 'include/class_report.php';
            $report = new Report();
            switch ($_POST['export_option']) {
                case 1:
                    $report->exportPage1($order_id, $_POST['type']);
                    break;
                case 2:
                    $report->exportPage2($order_id, $_POST['type']);
                    break;
                case 3:
                    $report->exportPage3($order_id, $_POST['type']);
                    break;
                case 4:
                    $report->exportPage4($order_id, $_POST['type']);
                    break;
                case 5:
                    $report->exportPage5($order_id, $_POST['type']);
                    break;
                case 6:
                    $report->exportPage6($order_id, $_POST['type']);
                    break;
                case 7:
                    $report->exportPage7($order_id, $_POST['type']);
                    break;
                case 10:
                    $report->exportOrder($order_id, $_POST['type']);
                    break;
                default:
                    break;
            }
        }
    }
    if (isset($_POST['save'])) {
        //init ajax
        $ajax = new ajax();
        $result = $customer->create_customer($client_name, $client_birthday, $client_email, $client_phone, $client_fax, $order_id, $client_id, $client_read_way);
        if ($result) {
            include 'include/class_mail.php';
            $mail = new Mail();
            @$mail->createOrder($order_id);
            if ($result['id'])
                $client_id = $result['id'];
            else
                $error[] = "アップデートが失敗する";
//                $exist = $result['exist'];
//                if ($exist)
//                    $error[] = "";
//                else
//                    $error[] = "";
            $client_arr = $result['client_arr'];

            if (!empty($client_arr)) {
                $client_name = $client_name != "" ? $client_name : $client_arr['client_name'];
                $client_read_way = $client_read_way != "" ? $client_read_way : $client_arr['client_read_way'];
                $client_birthday = $client_birthday != "" ? $client_birthday : $client_arr['client_birthday'];
                $client_email = $client_email != "" ? $client_email : $client_arr['client_email'];
                $client_phone = $client_phone != "" ? $client_phone : $client_arr['client_phone'];
                $client_fax = $client_fax != "" ? $client_fax : $client_arr['client_fax'];
                $gender = $gender != "" ? $gender : $client_arr['client_gender'];
                if ($house->isSerialized($client_arr['client_address'])) {
                    $house_address_serialize = unserialize($client_arr['client_address']);
                    $city_id = $city_id != "" ? $city_id : $house_address_serialize['city_id'];
                    $district_id = $district_id != "" ? $district_id : $house_address_serialize['district_id'];
                    $street_id = $street_id != "" ? $street_id : $house_address_serialize['street_id'];
                    $ward_id = $ward_id != "" ? $ward_id : $house_address_serialize['ward_id'];
                    $client_address = $client_address != "" ? $client_address : $house_address_serialize['client_address'];
                } else {
                    $client_address = $client_address != "" ? $client_address : $client_arr['client_address'];
                }
                // $client_address = $client_arr['client_address'];
                $client_occupation = $client_occupation != "" ? $client_occupation : $client_arr['client_occupation'];
                $client_company = $client_company != "" ? $client_company : $client_arr['client_company'];
                $client_income = $client_income != "" ? str_replace(",", "", $client_income) : $client_arr['client_income'];
                $client_room_type = $client_room_type != "" ? $client_room_type : $client_arr['client_room_type'];
                $client_room_type_number = $client_room_type_number != "" ? $client_room_type_number : $client_arr['client_room_type_number'];
                $client_rent = $client_rent != "" ? str_replace(",", "", $client_rent) : $client_arr['client_rent'];
                $client_reason_change = $client_reason_change != "" ? $client_reason_change : $client_arr['client_reason_change'];
                $client_time_change = $client_time_change != "" ? $client_time_change : $client_arr['client_time_change'];
                $client_resident_name = $client_resident_name != "" ? $client_resident_name : $client_arr['client_resident_name'];
                $client_resident_phone = $client_resident_phone != "" ? $client_resident_phone : $client_arr['client_resident_phone'];
            }
            //update detail           
            $client_income = str_replace(",", "", $client_income);
            $client_rent = str_replace(",", "", $client_rent);

            $house_address_serialize['city_id'] = $city_id;
            $house_address_serialize['district_id'] = $district_id;
            $house_address_serialize['street_id'] = $street_id;
            $house_address_serialize['ward_id'] = $ward_id;

            $house_address_serialize['client_address'] = $client_address;

            $house_address_serialize = serialize($house_address_serialize);
            //get info search
            $house_city_search = $house->getNameCity($city_id);
            $house_district_search = $house->getNameDistrict($district_id);
            $house_street_search = $house->getNameStreet($street_id);
            $house_ward_search = $house->getNameWard($ward_id);
            $house_address = $client_address;
            $house_search = $house_city_search . $house_district_search . $house_street_search . $house_ward_search . $house_address;

            $ajax->update_customer($gender, $house_address_serialize, $client_occupation, $client_company, $client_income, $client_room_type, $client_room_type_number, $client_rent, $client_reason_change, $client_time_change, $client_resident_name, $client_resident_phone, $client_id, $order_id, $house_search);
            //update hisotry

            $log_time_call_temp = trim($log_time_call_date) != "" ? strtotime($log_time_call_date . " " . $log_time_call) : null;
            $log_time_arrive_company_temp = trim($log_time_arrive_company_date) != "" ? strtotime($log_time_arrive_company_date . " " . $log_time_arrive_company) : null;
            $log_time_mail_temp = trim($log_time_mail_date) != "" ? strtotime($log_time_mail_date . " " . $log_time_mail) : null;
            $log_date_appointment_to_temp = trim($log_date_appointment_to_date) != "" ? strtotime($log_date_appointment_to_date . " " . $log_date_appointment_to) : null;
            $log_date_appointment_from_temp = trim($log_date_appointment_from_date) != "" ? strtotime($log_date_appointment_from_date . " " . $log_date_appointment_from) : null;

            $ajax->update_history_create($log_time_call_temp, $log_time_arrive_company_temp, $log_time_mail_temp, $log_tel, $log_tel_status, $log_mail, $log_comment, $log_date_appointment_from_temp, $log_date_appointment_to_temp, $log_mail_status, $log_contact_head_office, $log_shop_sign, $log_local_sign, $log_introduction, $log_flyer, $log_line, $log_revisit, $source_id, $log_status_appointment, $client_id, $order_id);
            //update introduce            
            //if (($introduce_room_id != 0 && $introduce_house_id != 0) || ($introduce_room_id != null && $introduce_house_id != null))
            //    $ajax->update_introduce($introduce_house_id, $introduce_room_id, $introduce_house_content, $client_id, $order_id);
            if ($tab_broker_id && $tab_room_id && $tab_house_id) {
                $order_day_update = time();
                $ajax->edit_room($tab_room_id, $room_id_bk, $house_id_bk, $broker_id_bk, $tab_order_rent_cost, $tab_order_comment, $tab_house_id, $tab_broker_id, $change_house_array, $order_day_update, $client_id, $order_id);
            }
            //update aspirations                        
            //if ($aspirations_type_room_number != 0 && $aspirations_type_room_number != null)
            $ajax->update_aspirations($aspirations_type_house, $aspirations_type_room, $aspirations_type_room_number, $aspirations_build_time, $aspirations_area, $aspirations_size, $aspirations_rent_cost, $aspirations_comment, $client_id, $order_id, $aspirations_size2, $aspirations_rent_cost2, $aspirations_area2, $aspirations_area3);
            //update contract

            if (isset($_POST['contract_label_money'])) {
                $label = $_POST['contract_label_money'];
            } elseif (isset($_GET['contract_label_money'])) {
                $label = $_GET['contract_label_money'];
            } else {
                $label = null;
            }
            if (isset($_POST['contract_plus_money'])) {
                $contract_plus_money = $_POST['contract_plus_money'];
            } elseif (isset($_GET['contract_plus_money'])) {
                $contract_plus_money = $_GET['contract_plus_money'];
            } else {
                $contract_plus_money = NULL;
            }

            if (isset($_POST['contract_plus_money_unit'])) {
                $plus_money_unit = $_POST['contract_plus_money_unit'];
            } elseif (isset($_GET['contract_plus_money_unit'])) {
                $plus_money_unit = $_GET['contract_plus_money_unit'];
            } else {
                $plus_money_unit = NULL;
            }

            //update time

            $contract_signature_day_temp = trim($contract_signature_day_date) != "" ? strtotime($contract_signature_day_date . " " . $contract_signature_day) : null;
            $contract_handover_day_temp = trim($contract_handover_day_date) != "" ? strtotime($contract_handover_day_date . " " . $contract_handover_day) : null;
            $contract_period_from_temp = trim($contract_period_from) != "" ? strtotime($contract_period_from) : null;
            $contract_period_to_temp = trim($contract_period_to) != "" ? strtotime($contract_period_to) : null;
            $contract_application_date_temp = trim($contract_application_date) != "" ? strtotime($contract_application_date) : null;
            $contract_payment_date_from_temp = trim($contract_payment_date_from) != "" ? strtotime($contract_payment_date_from) : null;
            $contract_payment_date_to_temp = trim($contract_payment_date_to) != "" ? strtotime($contract_payment_date_to) : null;
            $contract_cancel_date_temp = trim($contract_cancel_date) != "" ? strtotime($contract_cancel_date) : null;
            //parse cost valid
            $contract_cost = str_replace(",", "", $contract_cost);
            $contract_key_money = str_replace(",", "", $contract_key_money);
            $contract_broker_fee = str_replace(",", "", $contract_broker_fee);
            $contract_ads_fee = str_replace(",", "", $contract_ads_fee);
            $contract_deposit_1 = str_replace(",", "", $contract_deposit_1);
            $contract_deposit_2 = str_replace(",", "", $contract_deposit_2);
            $money_payment = str_replace(",", "", $money_payment);
            $contract_total = str_replace(",", "", $contract_total);
            $room_administrative_expense = str_replace(",", "", $room_administrative_expense);
            //end parse cost valid
            $result_contract = $ajax->update_contract($contract_name, $contract_cost, $contract_key_money, $contract_condition, $contract_valuation, $contract_signature_day_temp, $contract_handover_day_temp, $contract_period_from_temp, $contract_period_to_temp, $contract_deposit_1, $contract_deposit_2, $contract_cancel,$contract_cancel_date_temp, $contract_total, $contract_application, $contract_application_date_temp, $contract_broker_fee, $contract_broker_fee_unit, $contract_ads_fee, $contract_ads_fee_unit, $contract_transaction_finish, $contract_payment_date_from_temp, $contract_payment_date_to_temp, $contract_payment_status, $contract_payment_report, $label, $contract_plus_money, $plus_money_unit, $contract_key_money_unit, $contract_deposit1_money_unit, $contract_deposit2_money_unit, $partner_id, $partner_percent,$partner_ads, $contract_ambition, $money_payment, $room_rented, $room_administrative_expense, $client_id, $order_id);

            //parse cost display
            $contract_cost = $contract_cost != "" ? number_format($contract_cost, 0, '', ',') : $contract_cost;
            $contract_key_money = $contract_key_money != "" ? number_format($contract_key_money, 0, '', ',') : $contract_key_money;
            $contract_broker_fee = $contract_broker_fee != "" ? number_format($contract_broker_fee, 0, '', ',') : $contract_broker_fee;
            $contract_ads_fee = $contract_ads_fee != "" ? number_format($contract_ads_fee, 0, '', ',') : $contract_ads_fee;
            $contract_deposit_1 = $contract_deposit_1 != "" ? number_format($contract_deposit_1, 0, '', ',') : $contract_deposit_1;
            $contract_deposit_2 = $contract_deposit_2 != "" ? number_format($contract_deposit_2, 0, '', ',') : $contract_deposit_2;
            $money_payment = $money_payment != "" ? number_format($money_payment, 0, '', ',') : $money_payment;
            $contract_total = $contract_total != "" ? number_format($contract_total, 0, '', ',') : $contract_total;
            $room_administrative_expense = $room_administrative_expense != "" ? number_format($room_administrative_expense, 0, '', ',') : $room_administrative_expense;
            $client_income = $client_income != "" ? number_format($client_income, 0, '', ',') : $client_income;
            $client_rent = $client_rent != "" ? number_format($client_rent, 0, '', ',') : $client_rent;
            //end parse cost display
            //
            //update plus money
            if ($result_contract) {
                //1. get contract detail id
                $contract_id = checkExistContract($user->user_info['id'], $order_id);
                $contract_detail_id = $order->getContractDetailId($contract_id);
                //1. get plus money
                $plus_money = $order->getPlusMoney($contract_detail_id);
            }
            //send mail
            if (!empty($contract_application_date) && (!isset($_SESSION['send_' . $order_id]) || !$_SESSION['send_' . $order_id] )) {
                $mail->order($order_id);
                $_SESSION['send_' . $order_id] = true;
            }
        }
    }
    $broker = new HOMEBroker();
    $brokers = $broker->getAllBroker();
    $smarty->assign('tab_order_name', $tab_order_name);
    $smarty->assign('tab_order_rent_cost', $tab_order_rent_cost);
    $smarty->assign('tab_order_comment', $tab_order_comment);
    $smarty->assign('brokers', $brokers);
    $smarty->assign('tab_house_id', $tab_house_id);
    $smarty->assign('tab_house_description', $tab_house_description);
    $smarty->assign('tab_room_id', $tab_room_id);
    $smarty->assign('tab_broker_id', $tab_broker_id);
    $smarty->assign('change_house_array', $change_house_array);
    //get source
    // $house = new HOMEHouse();
    $houseTypes = $house->getHouseType();
    $roomTypes = $house->getRoomType();
    $sources = $house->getAllSource();
    $agent = new HOMEAgent();
    $agents = $agent->getAllAgent();
    $partners = $user->getAllUsers(true);

    $smarty->assign('agents', $agents);
    $smarty->assign('partners', $partners);
    $smarty->assign('partner_id', $partner_id);
    $smarty->assign('partner_percent', $partner_percent);
    $smarty->assign('partner_ads', $partner_ads);
    $smarty->assign('plus_money', $plus_money);
    $smarty->assign('contract_name', $contract_name);
    $smarty->assign('contract_cost', $contract_cost);
    $smarty->assign('contract_key_money', $contract_key_money);
    $smarty->assign('contract_condition', $contract_condition);
    $smarty->assign('contract_valuation', $contract_valuation);
    $smarty->assign('contract_signature_day', $contract_signature_day);
    $smarty->assign('contract_handover_day', $contract_handover_day);
    $smarty->assign('contract_period_from', $contract_period_from);
    $smarty->assign('contract_period_to', $contract_period_to);
    $smarty->assign('contract_signature_day_date', $contract_signature_day_date);
    $smarty->assign('contract_handover_day_date', $contract_handover_day_date);
    $smarty->assign('contract_period_from_date', $contract_period_from_date);
    $smarty->assign('contract_period_to_date', $contract_period_to_date);
    $smarty->assign('contract_deposit_1', $contract_deposit_1);
    $smarty->assign('contract_deposit_2', $contract_deposit_2);
    $smarty->assign('contract_cancel', $contract_cancel);
    $smarty->assign('contract_cancel_date', $contract_cancel_date);
    $smarty->assign('contract_total', $contract_total);
    $smarty->assign('contract_application', $contract_application);
    $smarty->assign('contract_application_date', $contract_application_date);

    $smarty->assign('keep_active_tab', $keep_active_tab);

    $smarty->assign('contract_payment_date_from', $contract_payment_date_from);
    $smarty->assign('contract_payment_date_to', $contract_payment_date_to);
    $smarty->assign('contract_payment_status', $contract_payment_status);
    $smarty->assign('contract_payment_report', $contract_payment_report);
    $smarty->assign('contract_broker_fee', $contract_broker_fee);
    $smarty->assign('contract_ads_fee', $contract_ads_fee);
    $smarty->assign('contract_transaction_finish', $contract_transaction_finish);
    $smarty->assign('contract_ambition', $contract_ambition);
    $smarty->assign('money_payment', $money_payment);
    $smarty->assign('room_rented', $room_rented);
    $smarty->assign('room_administrative_expense', $room_administrative_expense);
    $smarty->assign('contract_total', $contract_total);
    $smarty->assign('contract_broker_fee_unit', $contract_broker_fee_unit);
    $smarty->assign('contract_deposit1_money_unit', $contract_deposit1_money_unit);
    $smarty->assign('contract_key_money_unit', $contract_key_money_unit);
    $smarty->assign('contract_ads_fee_unit', $contract_ads_fee_unit);
    $smarty->assign('contract_deposit2_money_unit', $contract_deposit2_money_unit);

    $smarty->assign('introduce_house_id', $introduce_house_id);
    $smarty->assign('introduce_room_id', $introduce_room_id);
    $smarty->assign('introduce_house_content', $introduce_house_content);
    $smarty->assign('introduce_house_photo', $introduce_house_photo);

    $smarty->assign('aspirations_type_house', $aspirations_type_house);
    $smarty->assign('aspirations_type_room', $aspirations_type_room);
    $smarty->assign('aspirations_type_room_number', $aspirations_type_room_number);
    $smarty->assign('aspirations_build_time', $aspirations_build_time);
    $smarty->assign('aspirations_area', $aspirations_area);
    $smarty->assign('aspirations_area2', $aspirations_area2);
    $smarty->assign('aspirations_area3', $aspirations_area3);
    $smarty->assign('aspirations_size', $aspirations_size);
    $smarty->assign('aspirations_size2', $aspirations_size2);
    $smarty->assign('aspirations_rent_cost', $aspirations_rent_cost);
    $smarty->assign('aspirations_rent_cost2', $aspirations_rent_cost2);
    $smarty->assign('aspirations_comment', $aspirations_comment);
    $smarty->assign('log_time_call', $log_time_call);
    $smarty->assign('log_time_arrive_company', $log_time_arrive_company);
    $smarty->assign('log_time_mail', $log_time_mail);

    $smarty->assign('log_time_call_date', $log_time_call_date);
    $smarty->assign('log_time_arrive_company_date', $log_time_arrive_company_date);
    $smarty->assign('log_time_mail_date', $log_time_mail_date);

    $smarty->assign('log_comment', $log_comment);
    $smarty->assign('log_date_appointment_from', $log_date_appointment_from);
    $smarty->assign('log_date_appointment_to', $log_date_appointment_to);
    $smarty->assign('log_date_appointment_from_date', $log_date_appointment_from_date);
    $smarty->assign('log_date_appointment_to_date', $log_date_appointment_to_date);
//    $smarty->assign('log_payment_date_appointment_from', $log_payment_date_appointment_from);
//    $smarty->assign('log_payment_date_appointment_to', $log_payment_date_appointment_to);
//    $smarty->assign('log_payment_appointment_status', $log_payment_appointment_status);
//    $smarty->assign('log_payment_appointment_report', $log_payment_appointment_report);
    $smarty->assign('log_status_appointment', $log_status_appointment);
    $smarty->assign('log_tel', $log_tel);
    $smarty->assign('log_tel_status', $log_tel_status);
    $smarty->assign('log_mail', $log_mail);
    $smarty->assign('log_mail_status', $log_mail_status);
    $smarty->assign('log_contact_head_office', $log_contact_head_office);
    $smarty->assign('log_shop_sign', $log_shop_sign);
    $smarty->assign('log_local_sign', $log_local_sign);
    $smarty->assign('log_introduction', $log_introduction);
    $smarty->assign('log_flyer', $log_flyer);
    $smarty->assign('log_line', $log_line);
    $smarty->assign('log_revisit', $log_revisit);
    $smarty->assign('arr', $log_revisit);
    $smarty->assign('source_id', $source_id);
    $smarty->assign('gender', $gender);
    $smarty->assign('client_address', $client_address);
    $smarty->assign('city_id', $city_id);
    $smarty->assign('district_id', $district_id);
    $smarty->assign('street_id', $street_id);
    $smarty->assign('ward_id', $ward_id);
    $smarty->assign('client_occupation', $client_occupation);
    $smarty->assign('client_company', $client_company);
    $smarty->assign('client_income', $client_income);
    $smarty->assign('client_room_type', $client_room_type);
    $smarty->assign('client_room_type_number', $client_room_type_number);
    $smarty->assign('client_rent', $client_rent);
    $smarty->assign('client_reason_change', $client_reason_change);
    $smarty->assign('client_time_change', $client_time_change);
    $smarty->assign('client_resident_name', $client_resident_name);
    $smarty->assign('client_resident_phone', $client_resident_phone);
    $smarty->assign('client_name', $client_name);
    $smarty->assign('client_birthday', $client_birthday);
    $smarty->assign('client_read_way', $client_read_way);
    $smarty->assign('client_email', $client_email);
    $smarty->assign('client_phone', $client_phone);
    $smarty->assign('client_fax', $client_fax);
    $smarty->assign('houses', $houses);
    $smarty->assign('filter', $filter);
    $smarty->assign('client_id', $client_id);
    $smarty->assign('order_id', $order_id);
    $smarty->assign('page_number', $page_number);
    $smarty->assign('totalPage', $totalPage);
    $smarty->assign('customers', $customers);
    $smarty->assign('sources', $sources);
    $smarty->assign('roomTypes', $roomTypes);
    $smarty->assign('houseTypes', $houseTypes);
    $smarty->assign('errorHouseExist', $errorHouseExist);
}


$cities = $house->getAllCity();

$smarty->assign('cities', $cities);

$smarty->assign('broker_id', $broker_id);
$smarty->assign('step', $step);
$smarty->assign('error', $error);
$smarty->assign('careers', $order->getCareers());
$smarty->assign('reasons', $order->getReasons());

include "footer.php";
