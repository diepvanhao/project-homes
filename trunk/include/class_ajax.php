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
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];            
            $house['house_photo'] = $row['house_photo'];           
            $house['house_discount'] = $row['house_discount'];
            $house['house_structure'] = $row['house_structure'];
            $house['house_owner_id'] = $row['house_owner_id'];
            $house_arr[] = $house;
        }
        return $house_arr;
    }

    function getHouseContent($house_id) {
        global $database;
        $query = "select house_description from home_house where id='{$house_id}'";
        $result = $database->database_query($query);
        return $database->database_fetch_assoc($result);
    }
    function getRoomContentByHouseId($house_id){
        global $database;
        $query="select hrd.* from home_room as hr left join home_room_detail as hrd on hr.id=hrd.room_id where hr.house_id='{$house_id}' group by hrd.room_id";       
        $result = $database->database_query($query);
       
        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room['id'] = $row['id'];
            $room['room_number'] = $row['room_number'];
            $room['room_type'] = $row['room_type'];
            $room['room_size'] = $row['room_size'];
            $room['room_status'] = $row['room_status'];
            $room['room_rent'] = $row['room_rent'];                        
            $room['room_key_money'] = $row['room_key_money'];
            $room['room_administrative_expense'] = $row['room_administrative_expense'];
            $room['room_deposit'] = $row['room_deposit'];            
            $room['room_photo'] = $row['room_photo'];   
            $room['room_id'] = $row['room_id'];
            $room_arr[] = $room;
        }       
       
        return array_unique($room_arr,SORT_REGULAR);
        
    }
    
    function checkRoom($room_id,$broker_id){
        global $database;
        $query="select * from home_room hr left join home_room_detail hrd on hr.id=hrd.room_id where hr.id='{$room_id}' and hr.broker_id='{$broker_id}'";
        $result=$database->database_query($query);   
        $value=$database->database_fetch_assoc($result);       
        $row=$database->database_num_rows($result);
        if($row>=1)
            return array('room_rent'=>$value['room_rent'],'flag'=>'true');
        else 
            return array('room_rent'=>$value['room_rent'],'flag'=>'false');
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

    function update_aspirations($aspirations_type_house, $aspirations_type_room, $aspirations_build_time, $aspirations_area, $aspirations_size, $aspirations_rent_cost, $aspirations_comment, $client_id, $order_id) {
        global $database, $user;
        //check order exist

        if (checkExistAspirations($user->user_info['id'], $client_id, $order_id)) {
            //update history exist
            $query = "update home_history_aspirations set 
                    aspirations_type_house='{$aspirations_type_house}',
                    aspirations_type_room='{$aspirations_type_room}',
                    aspirations_build_time='{$aspirations_build_time}',
                    aspirations_area='{$aspirations_area}',
                    aspirations_size='{$aspirations_size}',
                    aspirations_rent_cost='{$aspirations_rent_cost}',
                    aspirations_comment='{$aspirations_comment}'                                      
                     where user_id='{$user->user_info['id']}' and client_id='{$client_id}' and order_id='{$order_id}'    
                    ";
            return array('id' => "", 'update' => $database->database_query($query));
        } else {
            $query = "insert into home_history_aspirations("
                    . "client_id,"
                    . "user_id,"
                    . "order_id,"
                    . "aspirations_type_house,"
                    . "aspirations_rent_cost,"
                    . "aspirations_type_room,"
                    . "aspirations_build_time,"
                    . "aspirations_area,"
                    . "aspirations_size,"
                    . "aspirations_comment"
                    . ") values("
                    . "'{$client_id}',"
                    . "'{$user->user_info['id']}',"
                    . "'{$order_id}',"
                    . "'{$aspirations_type_house}',"
                    . "'{$aspirations_rent_cost}',"
                    . "'{$aspirations_type_room}',"
                    . "'{$aspirations_build_time}',"
                    . "'{$aspirations_area}',"
                    . "'{$aspirations_size}',"
                    . "'{$aspirations_comment}'"
                    . ")";

            $result = $database->database_query($query);
            return array('id' => $database->database_insert_id());
        }
    }

    function update_introduce($house_id, $introduce_house_content, $client_id, $order_id) {
        global $database, $user;
        //check order exist

        if (checkExistIntroduce($client_id, $house_id)) {

            return array('id' => "");
        } else {
            $query = "insert into home_introduce_house("
                    . "user_id,"
                    . "client_id,"
                    . "house_id,"
                    . "introduce_house_content,"
                    . "introduce_house_photo"
                    . ") values("
                    . "'{$user->user_info['id']}',"
                    . "'{$client_id}',"
                    . "'{$house_id}',"
                    . "'{$introduce_house_content}',"
                    . "''"
                    . ")";

            $result = $database->database_query($query);
            return array('id' => $database->database_insert_id());
        }
    }

    function update_contract($contract_name, $contract_cost, $contract_plus_money, $contract_key_money, $contract_condition, $contract_valuation, $contract_signature_day, $contract_handover_day, $contract_period_from, $contract_period_to, $contract_deposit_1, $contract_deposit_2, $contract_cancel, $contract_total, $client_id, $order_id) {
        global $database, $user;
        //check order exist
        $contract_date_create = $contract_date_update = time();
        $contract_id = checkExistContract($user->user_info['id'], $order_id);
        if ($contract_id) {
            //update history exist
            $query = "update home_contract_detail set 
                    contract_plus_money={$contract_plus_money},
                    contract_cost={$contract_cost},
                    contract_total={$contract_total},
                    contract_signature_day='{$contract_signature_day}',
                    contract_handover_day='{$contract_handover_day}',
                    contract_condition='{$contract_condition}',
                    contract_valuation='{$contract_valuation}',
                    contract_date_create='{$contract_date_create}',
                    contract_date_update='{$contract_date_update}',
                    contract_cancel='{$contract_cancel}',
                    contract_period_from='{$contract_period_from}',
                    contract_period_to='{$contract_period_to}',
                    contract_deposit_1='{$contract_deposit_1}',
                    contract_deposit_2='{$contract_deposit_2}',
                    contract_key_money={$contract_key_money},
                    contract_name='{$contract_name}'
                                      
                     where contract_id='{$contract_id}'    
                    ";

            return array('id' => "", 'update' => $database->database_query($query));
        } else {
            $query = "insert into home_contract("
                    . "user_id,"
                    . "order_id"
                    . ") values("
                    . "'{$user->user_info['id']}',"
                    . "'{$order_id}'"
                    . ")";

            $result = $database->database_query($query);
            $contract_id = $database->database_insert_id();
            if ($contract_id) {
                //insert contract detail
                $query = "insert into home_contract_detail("
                        . "contract_id,"
                        . "contract_plus_money,"
                        . "contract_cost,"
                        . "contract_total,"
                        . "contract_signature_day,"
                        . "contract_handover_day,"
                        . "contract_condition,"
                        . "contract_valuation,"
                        . "contract_date_create,"
                        . "contract_date_update,"
                        . "contract_cancel,"
                        . "contract_period_from,"
                        . "contract_period_to,"
                        . "contract_deposit_1,"
                        . "contract_deposit_2,"
                        . "contract_key_money,"
                        . "contract_name"
                        . ") values("
                        . "'{$contract_id}',"
                        . "'{$contract_plus_money}',"
                        . "'{$contract_cost}',"
                        . "'{$contract_total}',"
                        . "'{$contract_signature_day}',"
                        . "'{$contract_handover_day}',"
                        . "'{$contract_condition}',"
                        . "'{$contract_valuation}',"
                        . "'{$contract_date_create}',"
                        . "'{$contract_date_update}',"
                        . "'{$contract_cancel}',"
                        . "'{$contract_period_from}',"
                        . "'{$contract_period_to}',"
                        . "'{$contract_deposit_1}',"
                        . "'{$contract_deposit_2}',"
                        . "'{$contract_key_money}',"
                        . "'{$contract_name}'"
                        . ")";
                $result = $database->database_query($query);
            }
            return array('id' => $database->database_insert_id());
        }
    }

    function getCustomerSelected($id) {
        global $database;
        $client_arr=array();
         //get information about client
            $query = "SELECT hc.id AS client_id,
                                hc.user_id AS user_id,
                                hc.client_name AS client_name,
                                hc.client_birthday AS client_birthday,
                                hc.client_address AS client_address,
                                hc.client_phone AS client_phone,
                                hc.client_income AS client_income,
                                hc.client_occupation AS client_occupation,
                                hc.client_company AS client_company,
                                hc.client_fax AS client_fax,
                                hc.client_gender AS client_gender,
                                hc.client_email AS client_email,
                                hc.client_reason_change AS client_reason_change,
                                hc.client_time_change AS client_time_change,
                                hc.client_photo AS client_photo,
                                hc.client_resident_name AS client_resident_name,
                                hc.client_resident_phone AS client_resident_phone,
                                hc.client_rent AS client_rent,
                                hc.client_room_type AS client_room_type

                                FROM home_client AS hc                                
                                where hc.id={$id}                                                             
                                LIMIT 1";


            $result = $database->database_query($query);
            $client_arr = array();

            while ($row = $database->database_fetch_assoc($result)) {
                $row['client_id'] = $row['client_id'];
                $row['user_id'] = $row['user_id'];
                $row['client_name'] = $row['client_name'];
                $row['client_birthday'] = $row['client_birthday'];
                $row['client_address'] = $row['client_address'];
                $row['client_phone'] = $row['client_phone'];
                $row['client_income'] = $row['client_income'];
                $row['client_occupation'] = $row['client_occupation'];
                $row['client_company'] = $row['client_company'];
                $row['client_fax'] = $row['client_fax'];
                $row['client_gender'] = $row['client_gender'];
                $row['client_email'] = $row['client_email'];
                $row['client_reason_change'] = $row['client_reason_change'];
                $row['client_time_change'] = $row['client_time_change'];
                $row['client_photo'] = $row['client_photo'];
                $row['client_resident_name'] = $row['client_resident_name'];
                $row['client_resident_phone'] = $row['client_resident_phone'];
                $row['client_rent'] = $row['client_rent'];
                $row['client_room_type'] = $row['client_room_type'];

                $client_arr = $row;
            }
        return $client_arr;
    }

}

function checkExistContract($user_id, $order_id) {
    global $database;
    $query = "select * from home_contract where user_id={$user_id} and order_id={$order_id}";

    $result = $database->database_query($query);

    $row = $database->database_num_rows($result);
    if ($row >= 1) {
        //get contract id
        $info = $database->database_fetch_assoc($result);
        return $info['id'];
    } else {
        return FALSE;
    }
}

function checkExistIntroduce($client_id, $house_id) {
    global $database;
    $query = "select * from home_introduce_house where client_id={$client_id} and house_id={$house_id}";

    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function checkExistAspirations($user_id, $client_id, $order_id) {
    global $database;
    $query = "select * from home_history_aspirations where user_id={$user_id} and client_id={$client_id} and order_id={$order_id}";

    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1) {
        return TRUE;
    } else {
        return FALSE;
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
