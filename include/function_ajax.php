<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "class_ajax.php";

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

        $result = $ajax->update_customer($gender, $client_address, $client_occupation, $client_company, $client_income, $client_room_type, $client_rent, $client_reason_change, $client_time_change, $client_resident_name, $client_resident_phone, $client_id, $order_id);
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
        if (isset($_POST['log_date_appointment'])) {
            $log_date_appointment = $_POST['log_date_appointment'];
        } elseif (isset($_GET['log_date_appointment'])) {
            $log_date_appointment = $_GET['log_date_appointment'];
        } else {
            $log_date_appointment = "";
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

        $result = $ajax->update_history($log_time_call, $log_time_arrive_company, $log_time_mail, $log_tel, $log_tel_status, $log_mail, $log_comment, $log_date_appointment, $log_mail_status, $log_contact_head_office, $log_shop_sign, $log_local_sign, $log_introduction, $log_flyer, $log_line, $log_revisit, $log_status_appointment, $client_id, $order_id);
        echo json_encode($result);
    }
}


