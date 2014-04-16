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
    $step = 1;
}

if (isset($_POST['broker_id'])) {
    $broker_id = $_POST['broker_id'];
} elseif (isset($_GET['broker_id'])) {
    $broker_id = $_GET['broker_id'];
} else {
    $broker_id = "";
}

if ($step == 1) {
    $broker = new HOMEBroker();
    $brokers = $broker->getAllBroker();
    $smarty->assign('brokers', $brokers);
} elseif ($step == 2) {


    if (empty($broker_id)) {
        $error[] = "Please choose Source to continue !!!";
        $step = 1;
        $broker = new HOMEBroker();
        $brokers = $broker->getAllBroker();
        $smarty->assign('brokers', $brokers);
    }
    $house = new HOMEHouse();
    $houses = $house->getHouses();
    $users = $user->getAllUsers();

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

    $broker = new HOMEBroker();
    $brokers = $broker->getBrokerById($broker_id);
    $house = new HOMEHouse();
    $houses = $house->getHouseById($house_id);
    $staff = new HOMEUser();
    $staffs = $staff->getAccountById($staff_id);

    $smarty->assign('order_name', $order_name);
    $smarty->assign('order_rent_cost', $order_rent_cost);
    $smarty->assign('order_comment', $order_comment);
    $smarty->assign('brokers', $brokers);
    $smarty->assign('houses', $houses);
    $smarty->assign('staffs', $staffs);
} elseif ($step == 'registry') {

    $errorHouseExist = "";

    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
    } elseif (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
    } else {
        $order_id = "";
    }
    ///create order
    if (isset($_POST['registry'])) {

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

        $order = new HOMEOrder();
        $result = $order->create_order($order_name, $order_rent_cost, $order_comment, $create_id, $house_id, $broker_id, $order_day_create);
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
    if (isset($_POST['log_date_appointment'])) {
        $log_date_appointment = $_POST['log_date_appointment'];
    } elseif (isset($_GET['log_date_appointment'])) {
        $log_date_appointment = $_GET['log_date_appointment'];
    } else {
        $log_date_appointment = "";
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
    if (isset($_POST['contract_plus_money'])) {
        $contract_plus_money = $_POST['contract_plus_money'];
    } elseif (isset($_GET['contract_plus_money'])) {
        $contract_plus_money = $_GET['contract_plus_money'];
    } else {
        $contract_plus_money = "";
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
    if (isset($_POST['contract_signature_date'])) {
        $contract_signature_date = $_POST['contract_signature_date'];
    } elseif (isset($_GET['contract_signature_date'])) {
        $contract_signature_date = $_GET['contract_signature_date'];
    } else {
        $contract_signature_date = "";
    }
    if (isset($_POST['contract_handover_date'])) {
        $contract_handover_date = $_POST['contract_handover_date'];
    } elseif (isset($_GET['contract_handover_date'])) {
        $contract_handover_date = $_GET['contract_handover_date'];
    } else {
        $contract_handover_date = "";
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

    //Introduce house
    $house = new HOMEHouse();
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
            $result = $customer->create_customer($client_name, $client_birthday, $client_email, $client_phone, $order_id, $client_id);
            if ($result) {
                $client_id = $result['id'];
                $exits = $result['exist'];
                $client_arr = $result['client_arr'];
                if (!empty($client_arr)) {
                    $client_name = $client_arr['client_name'];
                    $client_birthday = $client_arr['client_birthday'];
                    $client_email = $client_arr['client_email'];
                    $client_phone = $client_arr['client_phone'];
                    $gender = $client_arr['client_gender'];
                    $client_address = $client_arr['client_address'];
                    $client_occupation = $client_arr['client_occupation'];
                    $client_company = $client_arr['client_company'];
                    $client_income = $client_arr['client_income'];
                    $client_room_type = $client_arr['client_room_type'];
                    $client_rent = $client_arr['client_rent'];
                    $client_reason_change = $client_arr['client_reason_change'];
                    $client_time_change = $client_arr['client_time_change'];
                    $client_resident_name = $client_arr['client_resident_name'];
                    $client_resident_phone = $client_arr['client_resident_phone'];
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
    $smarty->assign('contract_name', $contract_name);
    $smarty->assign('contract_cost', $contract_cost);
    $smarty->assign('contract_plus_money', $contract_plus_money);
    $smarty->assign('contract_key_money', $contract_key_money);
    $smarty->assign('contract_condition', $contract_condition);
    $smarty->assign('contract_valuation', $contract_valuation);
    $smarty->assign('contract_signature_date', $contract_signature_date);
    $smarty->assign('contract_handover_date', $contract_handover_date);
    $smarty->assign('contract_period_from', $contract_period_from);
    $smarty->assign('contract_period_to', $contract_period_to);
    $smarty->assign('contract_deposit_1', $contract_deposit_1);
    $smarty->assign('contract_deposit_2', $contract_deposit_2);
    $smarty->assign('contract_cancel', $contract_cancel);
    $smarty->assign('contract_total', $contract_total);
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
    $smarty->assign('log_date_appointment', $log_date_appointment);
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
    $smarty->assign('gender', $gender);
    $smarty->assign('client_address', $client_address);
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
    $smarty->assign('houses', $houses);
    $smarty->assign('filter', $filter);
    $smarty->assign('client_id', $client_id);
    $smarty->assign('order_id', $order_id);
    $smarty->assign('page_number', $page_number);
    $smarty->assign('totalPage', $totalPage);
    $smarty->assign('customers', $customers);
    $smarty->assign('errorHouseExist', $errorHouseExist);
}


$smarty->assign('broker_id', $broker_id);
$smarty->assign('step', $step);
$smarty->assign('error', $error);

include "footer.php";
