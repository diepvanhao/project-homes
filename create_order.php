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
    
//////////////////////////////////////End Detail///////////////////////////////////////////

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
                if(!empty($client_arr)){
                    $client_name=$client_arr['client_name'];
                    $client_birthday=$client_arr['client_birthday'];
                    $client_email=$client_arr['client_email'];
                    $client_phone=$client_arr['client_phone'];
                    $gender=$client_arr['client_gender'];
                    $client_address=$client_arr['client_address'];
                    $client_occupation=$client_arr['client_occupation'];
                    $client_company=$client_arr['client_company'];
                    $client_income=$client_arr['client_income'];
                    $client_room_type=$client_arr['client_room_type'];
                    $client_rent=$client_arr['client_rent'];
                    $client_reason_change=$client_arr['client_reason_change'];
                    $client_time_change=$client_arr['client_time_change'];
                }
            }
        } elseif ($task == 'detail') {
            $result=$customer->update_customer($gender,$client_address,$client_occupation,$client_company,$client_income,$client_room_type,$client_rent,$client_reason_change,$client_time_change,$client_id,$order_id);
            if($result)
                $errorHouseExist=" success !!!";
        } elseif ($task == 'history') {
            
        } elseif ($task == 'aspirations') {
            
        } elseif ($task == 'introduce') {
            
        } elseif ($task == 'contract') {
            
        }
    }
    $smarty->assign('gender', $gender);
    $smarty->assign('client_address', $client_address);
    $smarty->assign('client_occupation', $client_occupation);
    $smarty->assign('client_company', $client_company);
    $smarty->assign('client_income', $client_income);
    $smarty->assign('client_room_type', $client_room_type);
    $smarty->assign('client_rent', $client_rent);
    $smarty->assign('client_reason_change', $client_reason_change);
    $smarty->assign('client_time_change', $client_time_change);
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
