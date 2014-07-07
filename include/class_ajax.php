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

    function deleteRoom($id, $broker_id, $house_id) {
        global $database;
        $query = "delete from home_room where broker_id='{$broker_id}' and house_id='{$house_id}' and room_detail_id='{$id}'";
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

    function deleteSource($source_id) {
        global $database;
        $query = "delete from home_source where id='{$source_id}'";
        return $database->database_query($query);
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
            $house['house_area'] = $row['house_area'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_photo'] = $row['house_photo'];
            $house['house_structure'] = $row['house_structure'];
            $house['house_owner_id'] = $row['house_owner_id'];
            $house_arr[] = $house;
        }
        return $house_arr;
    }

    function getPartnerByKey($agent_id) {
        global $database;
        $query = "select hu.* from home_agent as ha left join home_user as hu on ha.id=hu.agent_id ";
        $query.="where hu.user_authorities>1 and hu.user_locked=0";
        if (!empty($agent_id))
            $query.=" and ha.id='{$agent_id}' and hu.agent_id='{$agent_id}' ";

        $result = $database->database_query($query);
        $partner_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $partner['id'] = $row['id'];
            $partner['user_fname'] = $row['user_fname'];
            $partner['user_lname'] = $row['user_lname'];
            $partner_arr[] = $partner;
        }
        return $partner_arr;
    }

    function getBrokerByKey($search) {
        global $database;
        $search = trim($search);
        $query = "select * from home_broker_company";
        if (!empty($search))
            $query.=" where broker_company_name like '%{$search}%'";

        //echo $query;
        $result = $database->database_query($query);
        $broker_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $broker['id'] = $row['id'];
            $broker['user_id'] = $row['user_id'];
            $broker['broker_company_name'] = $row['broker_company_name'];
            $broker['broker_company_address'] = $row['broker_company_address'];
            $broker['broker_company_phone'] = $row['broker_company_phone'];
            $broker['broker_company_email'] = $row['broker_company_email'];
            $broker['broker_company_fax'] = $row['broker_company_fax'];
            $broker['broker_company_undertake'] = $row['broker_company_undertake'];

            $broker_arr[] = $broker;
        }
        return $broker_arr;
    }

    function getHouseListByBrokerId($broker_id) {
        global $database;
        $broker_id = trim($broker_id);
        $query = "SELECT hh.* FROM home_room AS hr

                        LEFT JOIN home_house AS hh ON hr.house_id=hh.id";

        if (!empty($broker_id))
            $query.=" where hr.broker_id='{$broker_id}'";
        $query.=" GROUP BY hr.house_id";
        //echo $query;
        $result = $database->database_query($query);
        $house_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $house['id'] = $row['id'];
            $house['user_id'] = $row['user_id'];
            $house['house_name'] = $row['house_name'];
            $house['house_address'] = $row['house_address'];
            $house['house_area'] = $row['house_area'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_photo'] = $row['house_photo'];
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

    function getRoomContentByHouseId($house_id) {
        global $database;
        $query = "select hr.* from home_room as hr where hr.house_id='{$house_id}' group by hr.id";
        $result = $database->database_query($query);

        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room['id'] = $row['id'];
            $room['house_id'] = $row['house_id'];
            $room['room_detail_id'] = $row['room_detail_id'];
            $room_arr[] = $room;
        }

        return array_unique($room_arr, SORT_REGULAR);
    }

    function checkRoom($house_id, $room_id, $broker_id) {
        global $database;
        $query = "select * from home_room hr left join home_room_detail hrd on hr.room_detail_id=hrd.id where hr.id='{$room_id}' and hr.broker_id='{$broker_id}' and hr.house_id='{$house_id}'";
        $result = $database->database_query($query);
        $value = $database->database_fetch_assoc($result);
        $row = $database->database_num_rows($result);
//print_r($value);
        if ($row >= 1)
            return array('room_rent' => $value['room_rent'], 'flag' => 'true', 'status' => $value['room_status']);
        else
            return array('room_rent' => $value['room_rent'], 'flag' => 'false', 'status' => $value['room_status']);
    }

    function checkRoomExist($house_id, $room_id, $broker_id) {
        global $database;
        $query = "select * from home_room as hr where hr.id='{$room_id}' and hr.broker_id='{$broker_id}' and hr.house_id='{$house_id}'";
        $result = $database->database_query($query);
        $value = $database->database_fetch_assoc($result);
        $row = $database->database_num_rows($result);

        if ($row >= 1)
            return array('flag' => 'true');
        else
            return array('flag' => 'false');
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

    function update_history($log_time_call, $log_time_arrive_company, $log_time_mail, $log_tel, $log_tel_status, $log_mail, $log_comment, $log_date_appointment_from, $log_date_appointment_to, $log_mail_status, $log_contact_head_office, $log_shop_sign, $log_local_sign, $log_introduction, $log_flyer, $log_line, $log_revisit, $source_id, $log_status_appointment, $client_id, $order_id) {
        global $database, $user;
        //check order exist

        if (checkExistHistory($user->user_info['id'], $client_id, $order_id)) {
            //update history exist
            $query = "update home_history_log set 
                    source_id='{$source_id}',
                    log_time_call='{$log_time_call}',
                    log_time_arrive_company='{$log_time_arrive_company}',
                    log_comment='{$log_comment}',
                    log_date_appointment_from='{$log_date_appointment_from}',
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
                    log_time_mail='{$log_time_mail}',
                    log_date_appointment_to='{$log_date_appointment_to}'
                    
                     where user_id='{$user->user_info['id']}' and client_id='{$client_id}' and order_id='{$order_id}'    
                    ";

            return array('id' => "", 'update' => $database->database_query($query));
        } else {
            $query = "insert into home_history_log("
                    . "user_id,"
                    . "client_id,"
                    . "order_id,"
                    . "source_id,"
                    . "log_time_call,"
                    . "log_time_arrive_company,"
                    . "log_comment,"
                    . "log_date_appointment_from,"
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
                    . "log_time_mail,"
                    . "log_date_appointment_to"
                    . ") values("
                    . "'{$user->user_info['id']}',"
                    . "'{$client_id}',"
                    . "'{$order_id}',"
                    . "'{$source_id}',"
                    . "'{$log_time_call}',"
                    . "'{$log_time_arrive_company}',"
                    . "'{$log_comment}',"
                    . "'{$log_date_appointment_from}',"
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
                    . "'{$log_time_mail}',"
                    . "'{$log_date_appointment_to}'"
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

    function update_introduce($house_id, $room_id, $introduce_house_content, $client_id, $order_id) {
        global $database, $user;
        //check order exist

        if (checkExistIntroduce($client_id, trim($house_id), trim($room_id))) {

            return array('id' => "");
        } else {
            $query = "insert into home_introduce_house("
                    . "user_id,"
                    . "client_id,"
                    . "house_id,"
                    . "room_id,"
                    . "order_id,"
                    . "introduce_house_content,"
                    . "introduce_house_photo"
                    . ") values("
                    . "'{$user->user_info['id']}',"
                    . "'{$client_id}',"
                    . "'{$house_id}',"
                    . "'{$room_id}',"
                    . "'{$order_id}',"
                    . "'{$introduce_house_content}',"
                    . "''"
                    . ")";

            $result = $database->database_query($query);
            return array('id' => $database->database_insert_id());
        }
    }

    function update_contract($contract_name, $contract_cost, $contract_key_money, $contract_condition, $contract_valuation, $contract_signature_day, $contract_handover_day, $contract_period_from, $contract_period_to, $contract_deposit_1, $contract_deposit_2, $contract_cancel, $contract_total, $contract_application, $contract_application_date, $contract_broker_fee, $contract_broker_fee_unit, $contract_ads_fee, $contract_ads_fee_unit, $contract_transaction_finish, $contract_payment_date_from, $contract_payment_date_to, $contract_payment_status, $contract_payment_report, $label, $plus_money, $plus_money_unit, $contract_key_money_unit, $contract_deposit1_money_unit, $contract_deposit2_money_unit, $partner_id, $partner_percent, $contract_ambition, $client_id, $order_id) {
        global $database, $user;
        //calculator fee
        $total = 0;

        if ($contract_key_money_unit == 'ヵ月')
            $contract_key_money = (float) $contract_key_money * $contract_cost;
        if ($contract_ads_fee_unit == 'ヵ月')
            $contract_ads_fee = (float) $contract_ads_fee * $contract_cost;
        if ($contract_broker_fee_unit == 'ヵ月')
            $contract_broker_fee = (float) $contract_broker_fee * $contract_cost;
        if ($contract_deposit1_money_unit == 'ヵ月')
            $contract_deposit_1 = (float) $contract_deposit_1 * $contract_cost;
        if ($contract_deposit2_money_unit == 'ヵ月')
            $contract_deposit_2 = (float) $contract_deposit_2 * $contract_cost;

        for ($i = 0; $i < count($plus_money); $i++) {
            if ($plus_money_unit[$i] == 'ヵ月')
                $total = (float) ($total + $plus_money[$i] * $contract_cost);
            else
                $total = (float) ($total + $plus_money[$i]);
        }
        $contract_total = (float) ($contract_cost + $contract_key_money + $contract_ads_fee + $contract_broker_fee + $total);
        //check order exist
        $contract_date_create = $contract_date_update = time();
        $contract_id = checkExistContract($user->user_info['id'], $order_id);
        if ($contract_id) {
            //update history exist
            $query = "update home_contract_detail set 
                  
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
                    contract_key_money='{$contract_key_money}',
                    contract_name='{$contract_name}',
                    contract_application='{$contract_application}',
                    contract_application_date='{$contract_application_date}',
                    contract_broker_fee='{$contract_broker_fee}',    
                    contract_ads_fee='{$contract_ads_fee}',    
                    contract_transaction_finish='{$contract_transaction_finish}',
                    contract_payment_date_from='{$contract_payment_date_from}',
                    contract_payment_date_to='{$contract_payment_date_to}',
                    contract_payment_status='{$contract_payment_status}',
                    contract_payment_report='{$contract_payment_report}',
                    contract_ambition='{$contract_ambition}'
                     where contract_id='{$contract_id}'    
                    ";

            $update = $database->database_query($query);
            //update plus money
            //1. Delete first
            //1.1 get contract_detail_id
            $query = "select id from home_contract_detail where  contract_id='{$contract_id}' ";

            $result = $database->database_query($query);
            $row = $database->database_fetch_assoc($result);
            $contract_detail_id = $row['id'];

            if (!empty($label) && !empty($plus_money)) {
                //1.2 delete
                $query = "delete from home_plus_money where contract_detail_id={$contract_detail_id}";

                $database->database_query($query);

                //2. Insert new 
                for ($i = 0; $i < count($label); $i++) {
                    $query = "insert into home_plus_money(contract_detail_id,label,price)values(
                                '{$contract_detail_id}',
                                '{$label[$i]}',
                                '{$plus_money[$i]}{$plus_money_unit[$i]}'
                                )";

                    $database->database_query($query);
                }
            }
            //end update plus money
            //begin partner
            if ($partner_id) {
                $check_partner_exist = checkPartnerExist($contract_detail_id, $partner_id);
                if ($check_partner_exist) {
                    $query = "update home_contract_partner set
                     partner_percent='{$partner_percent}'
                         where id='{$check_partner_exist}'
                    ";
                    $database->database_query($query);
                } else {
                    $query = "insert into home_contract_partner("
                            . "contract_detail_id,"
                            . "partner_id,"
                            . "partner_percent"
                            . ")values("
                            . "'{$contract_detail_id}',"
                            . "'{$partner_id}',"
                            . "'{$partner_percent}'"
                            . ")";
                    $database->database_query($query);
                }
            }

            //end partner                        
            return array('id' => "", 'update' => $update);
        } else {
            $query = "insert into home_contract("
                    . "user_id,"
                    . "client_id,"
                    . "order_id"
                    . ") values("
                    . "'{$user->user_info['id']}',"
                    . "'{$client_id}',"
                    . "'{$order_id}'"
                    . ")";

            $result = $database->database_query($query);
            $contract_id = $database->database_insert_id();
            if ($contract_id) {
                //insert contract detail
                $query = "insert into home_contract_detail("
                        . "contract_id,"
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
                        . "contract_name,"
                        . "contract_application,"
                        . "contract_application_date,"
                        . "contract_broker_fee,"
                        . "contract_ads_fee,"
                        . "contract_transaction_finish,"
                        . "contract_payment_date_from,"
                        . "contract_payment_date_to,"
                        . "contract_payment_status,"
                        . "contract_payment_report,"
                        . "contract_ambition"
                        . ") values("
                        . "'{$contract_id}',"
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
                        . "'{$contract_name}',"
                        . "'{$contract_application}',"
                        . "'{$contract_application_date}',"
                        . "'{$contract_broker_fee}',"
                        . "'{$contract_ads_fee}',"
                        . "'{$contract_transaction_finish}',"
                        . "'{$contract_payment_date_from}',"
                        . "'{$contract_payment_date_to}',"
                        . "'{$contract_payment_status}',"
                        . "'{$contract_payment_report}',"
                        . "'{$contract_ambition}'"
                        . ")";
                //   echo $query;die();
                $result = $database->database_query($query);

                $contract_detail_id = $database->database_insert_id();
                //insert plus money

                if (!empty($label) && !empty($plus_money)) {
                    //parse string to array
//                    $label = explode(",", $label);
//                    $plus_money = explode(",", $plus_money);
                    for ($i = 0; $i < count($label); $i++) {
                        $query = "insert into home_plus_money(contract_detail_id,label,price)values(
                                '{$contract_detail_id}',
                                '{$label[$i]}',
                                '{$plus_money[$i]}'
                                )";

                        $database->database_query($query);
                    }
                }
                //begin partner
                if ($partner_id) {
                    $check_partner_exist = checkPartnerExist($contract_detail_id, $partner_id);
                    if ($check_partner_exist) {
                        $query = "update home_contract_partner set
                     partner_percent='{$partner_percent}'
                         where id='{$check_partner_exist}'
                    ";
                        $database->database_query($query);
                    } else {
                        $query = "insert into home_contract_partner("
                                . "contract_detail_id,"
                                . "partner_id,"
                                . "partner_percent"
                                . ")values("
                                . "'{$contract_detail_id}',"
                                . "'{$partner_id}',"
                                . "'{$partner_percent}'"
                                . ")";
                        $database->database_query($query);
                    }
                }

                //end partner
            }
            return array('id' => $contract_detail_id);
        }
    }

    function getCustomerSelected($id) {
        global $database;
        $house = new HOMEHouse();
        $client_arr = array();
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
            $city_id = $district_id = $street_id = $ward_id = 0;
            $row['client_id'] = $row['client_id'];
            $row['user_id'] = $row['user_id'];
            $row['client_name'] = $row['client_name'];
            $row['client_birthday'] = $row['client_birthday'];
            if ($house->isSerialized($row['client_address'])) {
                $house_address_serialize = unserialize($row['client_address']);
                $city_id = $house_address_serialize['city_id'];
                $district_id = $house_address_serialize['district_id'];
                $street_id = $house_address_serialize['street_id'];
                $ward_id = $house_address_serialize['ward_id'];
                $row['client_address'] = $house_address_serialize['client_address'];
            } else {
                $row['client_address'] = $row['client_address'];
            }
            //$row['client_address'] = $row['client_address'];
            $row['city_id'] = $city_id;
            $row['district_id'] = $district_id;
            $row['street_id'] = $street_id;
            $row['ward_id'] = $ward_id;

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
            $row['client_room_type'] = $house->getRoomTypeById($row['client_room_type']);

            $client_arr = $row;
        }
        return $client_arr;
    }

    function getDistrictListByCityID($city_id) {
        global $database;
        $query = "select * from house_district where city_id='{$city_id}'";
        $result = $database->database_query($query);
        $district_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $district['id'] = $row['id'];
            $district['district_name'] = $row['district_name'];
            $district_arr[] = $district;
        }
        return $district_arr;
    }

    function getStreetListByDistrictID($district_id) {
        global $database;
        $query = "select * from house_street where district_id='{$district_id}'";
        $result = $database->database_query($query);
        $street_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $street['id'] = $row['id'];
            $street['street_name'] = $row['street_name'];
            $street_arr[] = $street;
        }
        return $street_arr;
    }

    function getWardListByStreetID($street_id) {
        global $database;
        $query = "select * from house_ward where street_id='{$street_id}'";
        $result = $database->database_query($query);
        $ward_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $ward['id'] = $row['id'];
            $ward['ward_name'] = $row['ward_name'];
            $ward_arr[] = $ward;
        }
        return $ward_arr;
    }

    function getSchedule($signature_day, $handover_day, $payment_day, $appointment_day, $period, $birthday, $all_agent, $agent_id, $position, $assign_id, $date_from, $date_to) {
        global $database;
        $agent = new HOMEAgent();
        $staff = new HOMEUser();
        $client = new Client();
        $events = Array();
        //get order list
        $query = "select ho.* from home_order as ho where "
                . "ho.order_status=1 ";
        if ($date_from)
            $query.=" and ho.order_day_update >='{$date_from}'";
        if ($date_to)
            $query.=" and ho.order_day_update <='{$date_to}'";
        $query.=" order by ho.order_day_update ASC";
//echo $query;die();
        $result_order = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result_order)) {
            //get transaction info
            //get contract info             
            $query = "select hcd.* from home_contract hc left join home_contract_detail hcd on hc.id=hcd.contract_id "
                    . "where hc.order_id='{$row['id']}'"
                    . " and hc.user_id='{$row['user_id']}'";

            $result_contract = $database->database_query($query);
            while ($contract = $database->database_fetch_assoc($result_contract)) {
                if (trim($contract['contract_signature_day'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Signature Day";
                    $start = explode(" ", $contract['contract_signature_day']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];
                    $event['end'] = "";
                    //$event['time']="";
                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
                if (trim($contract['contract_handover_day'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Handover Day";
                    $start = explode(" ", $contract['contract_handover_day']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];
                    $event['end'] = "";

                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
                if (trim($contract['contract_payment_date_from'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Payment Day";
                    $start = explode(" ", $contract['contract_payment_date_from']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];

                    $end = explode(" ", $contract['contract_payment_date_to']);
                    if (isset($end[0]))
                        $event['end'] = $end[0];
                    else
                        $event['end'] = "";
                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
                if (trim($contract['contract_period_from'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Period To";
                    $start = explode(" ", $contract['contract_period_from']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];

                    $end = explode(" ", $contract['contract_period_to']);
                    if (isset($end[0]))
                        $event['end'] = $end[0];
                    else
                        $event['end'] = "";
                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
            }
            //get birthday client
            if ($row['client_id']) {
                $event['id'] = $row['id'];
                $event['title'] = "Birthday";

                //$event['end']=$contract['contract_period_to'];
                //fetch agent, user info.      
                $agent_info = $agent->getAgentByUserId($row['user_id']);
                if ($agent_info)
                    $event['agent'] = $agent_info['agent_name'];
                $staff_info = $staff->getAccountById($row['user_id']);
                if ($staff_info) {
                    $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                    if ($staff_info['user_authorities'] == 2)
                        $event['position'] = 'Super manager';
                    elseif ($staff_info['user_authorities'] == 3)
                        $event['position'] = "Manager";
                    elseif ($staff_info['user_authorities'] == 4)
                        $event['position'] = "Staff";
                }
                //fetch client info
                $client_info = $client->getClientId($row['client_id']);
                if ($client_info) {
                    $event['customer'] = $client_info['client_name'];
                    $event['start'] = $client_info['client_birthday'];
                    $event['end'] = "";
                    $event['time'] = "";
                }
                $event['link'] = 'google.com.vn';
                $events[] = $event;
            }
            //get history info
            $query = "select * from home_history_log where order_id='{$row['id']}' and user_id='{$row['user_id']}'";
            $result_history = $database->database_query($query);
            $history = $database->database_fetch_assoc($result_history);
            if (trim($history['log_date_appointment_from'])) {
                $event['id'] = $row['id'];
                $event['title'] = "Appointment day";

                $start = explode(" ", $history['log_date_appointment_from']);
                if (isset($start[1]))
                    $event['time'] = $start[1];
                else
                    $event['time'] = "";
                $event['start'] = $start[0];

                $end = explode(" ", $history['log_date_appointment_to']);
                if (isset($end[0]))
                    $event['end'] = $end[0];
                else
                    $event['end'] = "";
                //fetch agent, user info.      
                $agent_info = $agent->getAgentByUserId($row['user_id']);
                if ($agent_info)
                    $event['agent'] = $agent_info['agent_name'];
                $staff_info = $staff->getAccountById($row['user_id']);
                if ($staff_info) {
                    $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                    if ($staff_info['user_authorities'] == 2)
                        $event['position'] = 'Super manager';
                    elseif ($staff_info['user_authorities'] == 3)
                        $event['position'] = "Manager";
                    elseif ($staff_info['user_authorities'] == 4)
                        $event['position'] = "Staff";
                }
                //fetch client info
                $client_info = $client->getClientId($row['client_id']);
                if ($client_info)
                    $event['customer'] = $client_info['client_name'];
                $event['link'] = 'google.com.vn';
                $events[] = $event;
            }
        }
        // Obtain a list of columns
        //sort event
        foreach ($events as $key => $row) {
            $volume[$key] = $row['start'];
            $edition[$key] = $row['time'];
        }

// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
        array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $events);
        
        return $events;
    }

}

function checkPartnerExist($contract_detail_id, $partner_id) {
    global $database;
    $query = "select * from home_contract_partner where contract_detail_id={$contract_detail_id} and partner_id={$partner_id}";

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

function checkExistIntroduce($client_id, $house_id, $room_id) {
    global $database;
    $query = "select * from home_introduce_house where client_id={$client_id} and house_id={$house_id} and room_id='{$room_id}'";
    //echo $query;
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
