<?php

include '../header.php';

//include 'class_database.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ajax {

    var $error = "";

    function checkExists($key, $val) {
        switch ($key) {
            case('email'):
                $this->checkEmail('Email', $val);
                break;
            case('username'):
                $this->checkUsername('UserName', $val);
                break;
            case('confirm_password'):
                $this->checkPassword('Password do not match', $val);
            default :
                break;
        }
        return $this->error;
    }

    function checkEmail($key, $val) {
        global $database;
        //$database = & HOMEDatabase::getInstance();
        if (!empty($val)) {
            $email = trim($val['email']);
            $query = "select * from home_user where user_email='{$email}'";

            $result = $database->database_query($query);
            if ($database->database_num_rows($result)) {
                $this->error = $key . " in use. Please enter a new email.";
            }
        }
    }

    function checkUsername($key, $val) {
        global $database;
        if (!empty($val)) {
            $username = trim($val['username']);
            $query = "select * from home_user where user_username='{$username}'";
            $result = $database->database_query($query);
            if ($database->database_num_rows($result)) {
                $this->error = $key . " in use. Please enter a new username.";
            }
        }
    }

    function checkPassword($key, $val) {
        if (trim($val['password']) != trim($val['confirm_password'])) {
            $this->error = $key;
        }
    }

    function deleteAgent($agent_id) {
        global $database;
        $query = "delete from home_agent where id={$agent_id}";
        $result = $database->database_query($query);
        if ($result) {
            //update user
            $query = "update home_user set agent_id=0 where agent_id={$agent_id}";
            return $database->database_query($query);
        }
        return;
    }

    function deleteHouse($house_id) {
        global $database;
        $query = "delete from home_house where id={$house_id}";
        return $database->database_query($query);
    }

    function deleteBroker($broker_id) {
        global $database;
        $query = "delete from home_broker_company where id={$broker_id}";
        $result = $database->database_query($query);
        if ($result) {
            //update house
            $query = "update home_house set broker_id=null where broker_id={$broker_id}";
            return $database->database_query($query);
        }
        return;
    }

    function deleteAccount($user_id) {
        global $database;
        //check lock user
        $query = "select user_locked from home_user where id={$user_id}";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        $user_locked = $row['user_locked'];
        if ($user_locked)
            $lock = 0;
        else
            $lock = 1;

        $query = "update home_user set user_locked={$lock} where id={$user_id}";
        $result = $database->database_query($query);

        return $result;
    }

    function editName($fname, $lname, $password) {
        //check $password match
        global $user, $database;
        if (!trim($password) || $user->user_password_crypt($password) != $user->user_info['user_password']) {

            $this->error = "Your password was incorrect !!!";
        } else {
            $query = "update home_user set user_fname='{$fname}',user_lname='{$lname}' where id='{$user->user_info['id']}'";
            $database->database_query($query);
        }
        return $this->error;
    }

    function editUsername($username, $password) {
        //check $password match
        global $user, $database;
        if (!trim($password) || $user->user_password_crypt($password) != $user->user_info['user_password']) {

            $this->error['password'] = "Your password was incorrect !!!";
        } elseif (!trim($username)) {
            $this->error['username'] = "Username isn't white space";
        } elseif (checkUsernameExist($username)) {
            $this->error['username'] = " Username in user. Please choose other !!!";
        } else {
            $query = "update home_user set user_username='{$username}' where id='{$user->user_info['id']}'";
            $database->database_query($query);
        }
        return $this->error;
    }

    function editEmail($email, $password) {
        //check $password match
        global $user, $database;
        if (!trim($password) || $user->user_password_crypt($password) != $user->user_info['user_password']) {

            $this->error['password'] = "Your password was incorrect !!!";
        } elseif (!trim($email)) {
            $this->error['email'] = "Email isn't white space";
        } elseif (checkEmailExist($email)) {
            $this->error['email'] = " Email in user. Please choose other !!!";
        } elseif (checkEmailValid($email)) {
            $this->error['email'] = "Email is not valid";
        } else {
            $query = "update home_user set user_email='{$email}' where id='{$user->user_info['id']}'";
            $database->database_query($query);
        }
        return $this->error;
    }

    function editPassword($current_password, $new_password, $re_new_password) {
        global $user, $database;

        // CHECK FOR EMPTY PASSWORDS
//
//        if (!trim($password['pass']) || !trim($password['confirm_pass'])) {
//            $this->error[] = "Password is required";
//            return;
//        }
//              
        if ($new_password != $re_new_password) {
            $this->error['re_new_password'] = "Passwords do not match";
            // MAKE SURE BOTH PASSWORDS ARE IDENTICAL
        } elseif (trim($new_password) && strlen($new_password) < 6) {
            $this->error['new_password'] = "Passwords 6 characters minimum ";
            // MAKE SURE PASSWORD IS LONGER THAN 5 CHARS
        } elseif (!trim($current_password) || $user->user_password_crypt($current_password) != $user->user_info['user_password']) {

            $this->error['current_password'] = "Your password was incorrect !!!";
            // CHECK FOR OLD PASSWORD MATCH
        } else {

            //update password
            $query = "update home_user set user_password='{$user->user_password_crypt($new_password)}' where id='{$user->user_info['id']}'";
            $database->database_query($query);
        }
        return $this->error;
    }

    function editPhoto($photoname, $password) {
        global $user, $database;
        if (!trim($password) || $user->user_password_crypt($password) != $user->user_info['user_password']) {

            $this->error['password'] = "Your password was incorrect !!!";
        }
        return $this->error;
    }

    function getHouseByKey($search) {
        global $database;
        $query = "select * from home_house";
        if (!empty($search))
            $query.=" where house_name like '%{$search}%'";

        //echo $query;
        $result = $database->database_query($query);
        $house_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $house['id'] = $row['id'];
            $house['user_id'] = $row['user_id'];
            $house['house_name'] = $row['house_name'];
            $house['house_address'] = $row['house_address'];
            $house['house_size'] = $row['house_size'];
            $house['house_area'] = $row['house_area'];
            $house['house_original_price'] = $row['house_original_price'];
            $house['house_status'] = $row['house_status'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_room_type'] = $row['house_room_type'];
            $house['house_photo'] = $row['house_photo'];
            $house['house_administrative_expense'] = $row['house_administrative_expense'];
            $house['house_discount'] = $row['house_discount'];
            $house['house_structure'] = $row['house_structure'];
            $house['house_owner_id'] = $row['house_owner_id'];
            $house_arr[] = $house;
        }
        return $house_arr;
    }

    function getHouseContent($house_id) {
        global $database;
        $query = "select house_description, house_original_price from home_house where id='{$house_id}'";
        $result = $database->database_query($query);
        return $database->database_fetch_assoc($result);
    }

    function update_customer($gender, $client_address, $client_occupation, $client_company, $client_income, $client_room_type, $client_rent, $client_reason_change, $client_time_change, $client_resident_name, $client_resident_phone, $client_id, $order_id) {
        global $database;
        $query = "update home_client set 
                client_gender= '{$gender}',
                client_address='{$client_address}',
                client_occupation='{$client_occupation}',
                client_company= '{$client_company}',
                client_income= '{$client_income}',
                client_room_type='{$client_room_type}',
                client_rent='{$client_rent}',
                client_reason_change= '{$client_reason_change}',
                client_time_change   ='{$client_time_change}',     
                client_resident_name='{$client_resident_name}',
                client_resident_phone='{$client_resident_phone}'    
                where id={$client_id}
                ";
        return $database->database_query($query);
    }

    function update_history($log_time_call, $log_time_arrive_company, $log_time_mail, $log_tel, $log_tel_status, $log_mail, $log_comment, $log_date_appointment, $log_mail_status, $log_contact_head_office, $log_shop_sign, $log_local_sign, $log_introduction, $log_flyer, $log_line, $log_revisit, $log_status_appointment, $client_id, $order_id) {
        global $database, $user;
        //check order exist

        if (checkExistHistory($user->user_info['id'], $client_id, $order_id)) {
            //update history exist
            $query = "update home_history_log set 
                    log_time_call='{$log_time_call}',
                    log_time_arrive_company='{$log_time_arrive_company}',
                    log_comment='{$log_comment}',
                    log_date_appointment='{$log_date_appointment}',
                    log_status_appointment='{$log_status_appointment}',
                    log_shop_sign='{$log_shop_sign}',
                    log_local_sign='{$log_local_sign}',
                    log_introduction='{$log_introduction}',
                    log_tel='{$log_tel}',
                    log_mail='{$log_mail}',
                    log_flyer='{$log_flyer}',
                    log_line='{$log_line}',
                    log_contact_head_office='{$log_contact_head_office}',
                    log_tel_status='{$log_tel_status}',
                    log_mail_status='{$log_mail_status}',
                    log_revisit='{$log_revisit}',
                    log_time_mail='{$log_time_mail}'                    
                     where user_id='{$user->user_info['id']}' and client_id='{$client_id}' and order_id='{$order_id}'    
                    ";
            
            return array('id' => "", 'update' => $database->database_query($query));
        } else {
            $query = "insert into home_history_log("
                    . "user_id,"
                    . "client_id,"
                    . "order_id,"
                    . "log_time_call,"
                    . "log_time_arrive_company,"
                    . "log_comment,"
                    . "log_date_appointment,"
                    . "log_status_appointment,"
                    . "log_shop_sign,"
                    . "log_local_sign,"
                    . "log_introduction,"
                    . "log_tel,"
                    . "log_mail,"
                    . "log_flyer,"
                    . "log_line,"
                    . "log_contact_head_office,"
                    . "log_tel_status,"
                    . "log_mail_status,"
                    . "log_revisit,"
                    . "log_time_mail"
                    . ") values("
                    . "'{$user->user_info['id']}',"
                    . "'{$client_id}',"
                    . "'{$order_id}',"
                    . "'{$log_time_call}',"
                    . "'{$log_time_arrive_company}',"
                    . "'{$log_comment}',"
                    . "'{$log_date_appointment}',"
                    . "'{$log_status_appointment}',"
                    . "'{$log_shop_sign}',"
                    . "'{$log_local_sign}',"
                    . "'{$log_introduction}',"
                    . "'{$log_tel}',"
                    . "'{$log_mail}',"
                    . "'{$log_flyer}',"
                    . "'{$log_line}',"
                    . "'{$log_contact_head_office}',"
                    . "'{$log_tel_status}',"
                    . "'{$log_mail_status}',"
                    . "'{$log_revisit}',"
                    . "'{$log_time_mail}'"
                    . ")";
            $result = $database->database_query($query);
            return array('id' => $database->database_insert_id());
        }
    }

}

function checkExistHistory($user_id, $client_id, $order_id) {
    global $database;
    $query = "select * from home_history_log where user_id={$user_id} and client_id={$client_id} and order_id={$order_id}";

    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function checkUsernameExist($username) {
    global $database;
    $query = "select user_username from home_user where user_username='{$username}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row)
        return true;
    else
        return false;
}

function checkEmailExist($email) {
    global $database;
    $email = trim($email);
    $query = "select user_email from home_user where user_email='{$email}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row)
        return true;
    else
        return false;
}

function checkEmailValid($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return FALSE;
    }
}
