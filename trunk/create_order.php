<?php

include "header.php";
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
    $smarty->assign('order_name', $order_name);
    $smarty->assign('order_rent_cost', $order_rent_cost);
    $smarty->assign('order_comment', $order_comment);
    $smarty->assign('staff_id', $staff_id);
    $smarty->assign('room_id', $room_id);
    $smarty->assign('house_id', $house_id);
    $smarty->assign('broker_id', $broker_id);
    //end
    //$house = new HOMEHouse();
    $houses = $house->getHouses();
    $users = $user->getAllUsers();
    $broker = new HOMEBroker();
    $brokers = $broker->getAllBroker();
    $smarty->assign('brokers', $brokers);
    $smarty->assign('houses', $houses);
    $smarty->assign('users', $users);
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
    ///create order
    if (isset($_POST['registry'])) {

        if (isset($_POST['room_id'])) {
            $room_id = $_POST['room_id'];
        } elseif (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = "";
        }
        if (isset($_POST['order_name'])) {
            $order_name = $_POST['order_name'];
        } elseif (isset($_GET['order_name'])) {
            $order_name = $_GET['order_name'];
        } else {
            $order_name = "";
        }
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
        if (isset($_POST['broker_id'])) {
            $broker_id = $_POST['broker_id'];
        } elseif (isset($_GET['broker_id'])) {
            $broker_id = $_GET['broker_id'];
        } else {
            $broker_id = "";
        }

        //get create day
        $order_day_create = time();


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
///////////////////////////////////End Aspirations///////////////////////////////////
//////////////////////////////////Begin Introduce///////////////////////////////////
    if (isset($_POST['house_id'])) {
        $house_id = $_POST['house_id'];
    } elseif (isset($_GET['house_id'])) {
        $house_id = $_GET['house_id'];
    } else {
        $house_id = "";
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
    if (isset($_POST['contract_ads_fee'])) {
        $contract_ads_fee = $_POST['contract_ads_fee'];
    } elseif (isset($_GET['contract_ads_fee'])) {
        $contract_ads_fee = $_GET['contract_ads_fee'];
    } else {
        $contract_ads_fee = "";
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
            $client_address = $house_address_serialize['client_address'];
            $customers[$i]['client_address'] = $city_id_filter . ", " . $district_id_filter . ", " . $street_id_filter . ", " . $ward_id_filter . ", " . $client_address;
        } else {
            $customers[$i]['client_address'] = $customers[$i]['client_address'];
        }
    }
    //Introduce house
    //  $house = new HOMEHouse();
    $houses = $house->getHouses();
    //Store client's info
    if (isset($_POST['save'])) {

        if (isset($_POST['task'])) {
            $task = $_POST['task'];
        } elseif (isset($_GET['task'])) {
            $task = $_GET['task'];
        } else {
            $task = "";
        }
        if ($task == 'basic') {
            $result = $customer->create_customer($client_name, $client_birthday, $client_email, $client_phone, $client_fax, $order_id, $client_id);
            if ($result) {
                $client_id = $result['id'];
                $exist = $result['exist'];
                if ($exist)
                    $error[] = "This client existed and the system auto fill in information :)";
                else
                    $error[] = "";
                $client_arr = $result['client_arr'];

                if (!empty($client_arr)) {
                    $client_name = $client_arr['client_name'];
                    $client_birthday = $client_arr['client_birthday'];
                    $client_email = $client_arr['client_email'];
                    $client_phone = $client_arr['client_phone'];
                    $client_fax = $client_arr['client_fax'];
                    $gender = $client_arr['client_gender'];
                    if ($house->isSerialized($client_arr['client_address'])) {
                        $house_address_serialize = unserialize($client_arr['client_address']);
                        $city_id = $house_address_serialize['city_id'];
                        $district_id = $house_address_serialize['district_id'];
                        $street_id = $house_address_serialize['street_id'];
                        $ward_id = $house_address_serialize['ward_id'];
                        $client_address = $house_address_serialize['client_address'];
                    } else {
                        $client_address = $client_arr['client_address'];
                    }
                    // $client_address = $client_arr['client_address'];
                    $client_occupation = $client_arr['client_occupation'];
                    $client_company = $client_arr['client_company'];
                    $client_income = $client_arr['client_income'];
                    $client_room_type = $client_arr['client_room_type'];
                    $client_rent = $client_arr['client_rent'];
                    $client_reason_change = $client_arr['client_reason_change'];
                    $client_time_change = $client_arr['client_time_change'];
                    $client_resident_name = $client_arr['client_resident_name'];
                    $client_resident_phone = $client_arr['client_resident_phone'];

                    if ($user->user_info['id'] == $client_arr['user_id']) {
                        $result = $customer->getCustomersOrder($order_id, $client_id);
                        if ($result) {
                            $client_arr = $result['client_arr'];
                            if (!empty($client_arr)) {
                                $log_time_call = $client_arr['log_time_call'];
                                $log_time_arrive_company = $client_arr['log_time_arrive_company'];
                                $log_time_mail = $client_arr['log_time_mail'];
                                $log_comment = $client_arr['log_comment'];
                                $log_date_appointment_from = $client_arr['log_date_appointment_from'];
                                $log_date_appointment_to = $client_arr['log_date_appointment_to'];
                                $log_status_appointment = $client_arr['log_status_appointment'];
                                $log_tel = $client_arr['log_tel'];
                                $log_tel_status = $client_arr['log_tel_status'];
                                $log_mail = $client_arr['log_mail'];
                                $log_mail_status = $client_arr['log_mail_status'];
                                $log_contact_head_office = $client_arr['log_contact_head_office'];
                                $log_shop_sign = $client_arr['log_shop_sign'];
                                $log_local_sign = $client_arr['log_local_sign'];
                                $log_introduction = $client_arr['log_introduction'];
                                $log_flyer = $client_arr['log_flyer'];
                                $log_line = $client_arr['log_line'];
                                $log_revisit = $client_arr['log_revisit'];
                                $source_id = $client_arr['source_id'];

                                $aspirations_type_house = $client_arr['aspirations_type_house'];
                                $aspirations_type_room = $client_arr['aspirations_type_room'];
                                $aspirations_build_time = $client_arr['aspirations_build_time'];
                                $aspirations_area = $client_arr['aspirations_area'];
                                $aspirations_size = $client_arr['aspirations_size'];
                                $aspirations_rent_cost = $client_arr['aspirations_rent_cost'];
                                $aspirations_comment = $client_arr['aspirations_comment'];

                                $contract_name = $client_arr['contract_name'];
                                $contract_cost = $client_arr['contract_cost'];
                                $contract_key_money = $client_arr['contract_key_money'];
                                $contract_condition = $client_arr['contract_condition'];
                                $contract_valuation = $client_arr['contract_valuation'];
                                $contract_signature_day = $client_arr['contract_signature_day'];
                                $contract_handover_day = $client_arr['contract_handover_day'];
                                $contract_period_from = $client_arr['contract_period_from'];
                                $contract_period_to = $client_arr['contract_period_to'];
                                $contract_deposit_1 = $client_arr['contract_deposit_1'];
                                $contract_deposit_2 = $client_arr['contract_deposit_2'];
                                $contract_cancel = $client_arr['contract_cancel'];
                                $contract_total = $client_arr['contract_total'];
                                $contract_application = $client_arr['contract_application'];
                                $contract_application_date = $client_arr['contract_application_date'];
                                $contract_payment_date_from = $client_arr['contract_payment_date_from'];
                                $contract_payment_date_to = $client_arr['contract_payment_date_to'];
                                $contract_payment_status = $client_arr['contract_payment_status'];
                                $contract_payment_report = $client_arr['contract_payment_report'];
                                $contract_broker_fee = $client_arr['contract_broker_fee'];
                                $contract_ads_fee = $client_arr['contract_ads_fee'];
                                $contract_transaction_finish = $client_arr['contract_transaction_finish'];

                                $plus_money = $order->getPlusMoney($client_arr['contract_detail_id']);
                            }
                        }
                    }
                }
            }
        } elseif ($task == 'detail') {
            $result = $customer->update_customer($gender, $client_address, $client_occupation, $client_company, $client_income, $client_room_type, $client_rent, $client_reason_change, $client_time_change, $client_resident_name, $client_resident_phone, $client_id, $order_id);
            if ($result)
                $errorHouseExist = " success !!!";
        } elseif ($task == 'history') {
            
        } elseif ($task == 'aspirations') {
            
        } elseif ($task == 'introduce') {
            
        } elseif ($task == 'contract') {
            
        }
    }
    //get source
    // $house = new HOMEHouse();
    $sources = $house->getAllSource();
    $agent = new HOMEAgent();
    $agents = $agent->getAllAgent();
    $partners = $user->getAllUsers(true);

    $smarty->assign('agents', $agents);
    $smarty->assign('partners', $partners);
    $smarty->assign('partner_id', $partner_id);
    $smarty->assign('partner_percent', $partner_percent);
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
    $smarty->assign('contract_deposit_1', $contract_deposit_1);
    $smarty->assign('contract_deposit_2', $contract_deposit_2);
    $smarty->assign('contract_cancel', $contract_cancel);
    $smarty->assign('contract_total', $contract_total);
    $smarty->assign('contract_application', $contract_application);
    $smarty->assign('contract_application_date', $contract_application_date);

    $smarty->assign('contract_payment_date_from', $contract_payment_date_from);
    $smarty->assign('contract_payment_date_to', $contract_payment_date_to);
    $smarty->assign('contract_payment_status', $contract_payment_status);
    $smarty->assign('contract_payment_report', $contract_payment_report);
    $smarty->assign('contract_broker_fee', $contract_broker_fee);
    $smarty->assign('contract_ads_fee', $contract_ads_fee);
    $smarty->assign('contract_transaction_finish', $contract_transaction_finish);

    $smarty->assign('house_id', $house_id);
    $smarty->assign('introduce_house_content', $introduce_house_content);
    $smarty->assign('introduce_house_photo', $introduce_house_photo);
    $smarty->assign('aspirations_type_house', $aspirations_type_house);
    $smarty->assign('aspirations_type_room', $aspirations_type_room);
    $smarty->assign('aspirations_build_time', $aspirations_build_time);
    $smarty->assign('aspirations_area', $aspirations_area);
    $smarty->assign('aspirations_size', $aspirations_size);
    $smarty->assign('aspirations_rent_cost', $aspirations_rent_cost);
    $smarty->assign('aspirations_comment', $aspirations_comment);
    $smarty->assign('log_time_call', $log_time_call);
    $smarty->assign('log_time_arrive_company', $log_time_arrive_company);
    $smarty->assign('log_time_mail', $log_time_mail);
    $smarty->assign('log_comment', $log_comment);
    $smarty->assign('log_date_appointment_from', $log_date_appointment_from);
    $smarty->assign('log_date_appointment_to', $log_date_appointment_to);
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
    $smarty->assign('client_rent', $client_rent);
    $smarty->assign('client_reason_change', $client_reason_change);
    $smarty->assign('client_time_change', $client_time_change);
    $smarty->assign('client_resident_name', $client_resident_name);
    $smarty->assign('client_resident_phone', $client_resident_phone);
    $smarty->assign('client_name', $client_name);
    $smarty->assign('client_birthday', $client_birthday);
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
    $smarty->assign('errorHouseExist', $errorHouseExist);
}


$cities = $house->getAllCity();

$smarty->assign('cities', $cities);


$smarty->assign('broker_id', $broker_id);
$smarty->assign('step', $step);
$smarty->assign('error', $error);

include "footer.php";