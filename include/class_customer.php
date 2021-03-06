<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMECustomer {

    function getCustomers($search = "", $offset = 0, $length = 50) {
        global $database;
        $client_arr = array();
        $query = "select * from home_client";
        if (!empty($search))
            $query.=" where client_name like '%{$search}%'";

        $query.=" limit $offset,$length";
        $result = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result)) {
            $client['id'] = $row['id'];
            $client['client_name'] = $row['client_name'];
            $client['client_birthday'] = $row['client_birthday'];
            $client['client_address'] = $row['client_address'];
            $client['client_phone'] = $row['client_phone'];
            $client['client_fax'] = $row['client_fax'];
            $client_arr[] = $client;
        }
        return $client_arr;
    }

    function getTotalItem($search) {
        global $database;

        $query = "select * from home_client";
        if (!empty($search))
            $query.=" where client_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function create_customer_fetch_email($client_name, $client_read_way, $client_phone, $client_email, $client_address) {
        global $database, $user;
        $client_name = trim($client_name);
        $client_phone = trim($client_phone);
        $client_email = trim($client_email);
        if ($this->checkExistClient($client_name, $client_phone, $client_email)) {

            $query = "select id from home_client where   (client_phone='{$client_phone}' and  client_name='{$client_name}')"
                    . " or (client_email='{$client_email}' and  client_name='{$client_name}')";

            $result = $database->database_query($query);
            $row = $database->database_fetch_assoc($result);
            $id = $row['id'];
            return $id;
        } else {
            $query = "insert into home_client(
                        user_id,
                        client_name,
                        client_read_way,                        
                        client_email,
                        client_phone                        
                     )values(
            {$user->user_info['id']},
                     '{$client_name}',
                     '{$client_read_way}',                     
                     '{$client_email}',
                     '{$client_phone}'                        
                    )";

            $result = $database->database_query($query);
            $id = $database->database_insert_id($result);
            return $id;
        }
    }

    function create_customer($client_name, $client_birthday, $client_email, $client_phone, $client_fax, $order_id, $client_id, $client_read_way = "") {
        global $database, $user;

        $exits = FALSE;
        if ($client_id) {
            $exits = true;
        } elseif ($this->checkExistClient($client_name, $client_phone, $client_email)) {
            $exits = true;
        }
        if (!$exits) {
            $query = "insert into home_client(
                        user_id,
                        client_name,
                        client_read_way,
                        client_birthday,
                        client_email,
                        client_phone,
                        client_fax
                     )values(
            {$user->user_info['id']},
                     '{$client_name}',
                     '{$client_read_way}',
                     '{$client_birthday}',
                     '{$client_email}',
                     '{$client_phone}',
                     '{$client_fax}'    
                    )";

            $result = $database->database_query($query);
            $id = $database->database_insert_id($result);
            if ($id) {
                //update order
                $query = "update home_order set client_id={$id}, user_id={$user->user_info['id']} where id={$order_id}";
                $database->database_query($query);
                //update history
                $query = "update home_history_log set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
                //update aspirations
                $query = "update home_history_aspirations set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
                //update contract
                $query = "update home_contract set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
                //update introduce house
                $query = "update home_introduce_house set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
            } else {
                return array('exist' => $exits, 'id' => "", 'client_arr' => "");
            }
            return array('exist' => $exits, 'id' => $id, 'client_arr' => "");
        } else {
//            if ($client_id) {
//                //update order
//                $query = "update home_order set client_id={$client_id}, user_id={$user->user_info['id']} where id={$order_id}";
//                $database->database_query($query);
//                return array('exist' => $exits, 'id' => $client_id, 'client_arr' => "");
//            } else {
            $client_name = trim($client_name);
            $client_phone = trim($client_phone);
            $client_email = trim($client_email);

            $query = "select id from home_client where   (client_phone='{$client_phone}' and  client_name='{$client_name}')"
                    . " or (client_email='{$client_email}' and  client_name='{$client_name}')";

            $result = $database->database_query($query);
            $row = $database->database_fetch_assoc($result);
            $id = $row['id'];
            if ($id) {
                //update order
                $query = "update home_order set client_id={$id}, user_id={$user->user_info['id']} where id={$order_id}";
                $database->database_query($query);
                //update history
                $query = "update home_history_log set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
                //update aspirations
                $query = "update home_history_aspirations set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
                //update contract
                //update contract
                $query = "update home_contract set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
                //update introduce house
                $query = "update home_introduce_house set client_id={$id}, user_id={$user->user_info['id']} where order_id={$order_id}";
                $database->database_query($query);
            } else {
                return array('exist' => $exits, 'id' => "", 'client_arr' => "");
            }
            //get information about client
            $query = "SELECT hc.id AS client_id,
                                hc.user_id AS user_id,
                                hc.client_name AS client_name,
                                hc.client_read_way as client_read_way,
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
                                hc.client_room_type AS client_room_type,
                                hc.client_room_type_number AS client_room_type_number

                                FROM home_client AS hc                                
                                where hc.id={$id}                                                             
                                LIMIT 1";


            $result = $database->database_query($query);
            $client_arr = array();

            while ($row = $database->database_fetch_assoc($result)) {
                $row['client_id'] = $row['client_id'];
                $row['user_id'] = $row['user_id'];
                $row['client_name'] = $row['client_name'];
                $row['client_read_way'] = $row['client_read_way'];
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
                $row['client_room_type_number'] = $row['client_room_type_number'];

                $client_arr = $row;
            }

            return array('exist' => $exits, 'id' => $id, 'client_arr' => $client_arr);
        }
    }

    function getCustomerIntroduce($order_id, $client_id) {
        global $database;
        $client_arr = array();
        $query = " select hih.* from home_order AS ho"
                . " LEFT JOIN home_introduce_house AS hih ON ho.id=hih.order_id where hih.order_id='{$order_id}' and hih.client_id='{$client_id}' and ho.id='{$order_id}' order by hih.id DESC limit 1";

        $result = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result)) {
            $introduce['id'] = $row['id'];
            $introduce['introduce_house_id'] = $row['house_id'];
            $introduce['introduce_room_id'] = $row['room_id'];
            $introduce['introduce_house_content'] = $row['introduce_house_content'];
            $introduce['introduce_house_photo'] = $row['introduce_house_photo'];

            $client_arr = $introduce;
            return array('client_arr' => $client_arr);
        }
    }

    function getCustomersOrder($order_id, $client_id) {

        global $database;
        $client_arr = array();
        $query = "SELECT
                                hhl.id AS history_log_id, 
                                hhl.log_time_call AS log_time_call,
                                hhl.log_time_arrive_company AS log_time_arrive_company,
                                hhl.log_comment AS log_comment,
                                hhl.log_date_appointment_from AS log_date_appointment_from,
                                hhl.log_status_appointment AS log_status_appointment,
                                hhl.log_shop_sign AS log_shop_sign,
                                hhl.log_local_sign AS log_local_sign,
                                hhl.log_introduction AS log_introduction,
                                hhl.log_tel AS log_tel,
                                hhl.log_mail AS log_mail,
                                hhl.log_flyer AS log_flyer,
                                hhl.log_line AS log_line,
                                hhl.log_contact_head_office AS log_contact_head_office,
                                hhl.log_tel_status AS log_tel_status,
                                hhl.log_mail_status AS log_mail_status,
                                hhl.log_revisit AS log_revisit,
                                hhl.source_id AS source_id,
                                hhl.log_time_mail,
                                hhl.log_date_appointment_to AS log_date_appointment_to,                                

                                hha.id AS aspirations_id,
                                hha.aspirations_type_house AS aspirations_type_house,
                                hha.aspirations_rent_cost AS aspirations_rent_cost,
                                hha.aspirations_type_room AS aspirations_type_room,
                                hha.aspirations_type_room_number AS aspirations_type_room_number,
                                hha.aspirations_build_time AS aspirations_build_time,
                                hha.aspirations_area AS aspirations_area,
                                hha.aspirations_size AS aspirations_size,
                                hha.aspirations_comment AS aspirations_comment
                                
                                ,ho.id AS order_id,
                                ho.order_name AS order_name,
                                ho.house_id AS house_id,
                                ho.room_id AS room_id,
                                ho.order_rent_cost AS order_rent_cost,
                                ho.order_day_create AS order_day_create,
                                ho.order_status AS order_status,
                                ho.order_comment AS order_comment,
                                ho.order_day_update AS order_day_update,
                                ho.create_id AS create_id,
                                ho.broker_id AS broker_id,
                                ho.change AS order_change,
                                ho.change_house_array

                                FROM home_order AS ho

                                LEFT JOIN home_history_log AS hhl ON ho.id=hhl.order_id

                                LEFT JOIN home_history_aspirations AS hha ON ho.id=hha.order_id                                                                                                                                                                                               
                                
                                where ho.id={$order_id} and ho.client_id={$client_id}                                                                    
                                
                                LIMIT 1";
//echo $query;die();
        $result = $database->database_query($query);

        $row1 = $database->database_fetch_assoc($result);

        $row['history_log_id'] = $row1['history_log_id'];
        $row['log_time_call'] = $row1['log_time_call'];
        $row['log_time_arrive_company'] = $row1['log_time_arrive_company'];
        $row['log_comment'] = $row1['log_comment'];
        $row['log_date_appointment_from'] = $row1['log_date_appointment_from'];
        $row['log_status_appointment'] = $row1['log_status_appointment'];
        $row['log_shop_sign'] = $row1['log_shop_sign'];
        $row['log_local_sign'] = $row1['log_local_sign'];
        $row['log_introduction'] = $row1['log_introduction'];
        $row['log_tel'] = $row1['log_tel'];
        $row['log_mail'] = $row1['log_mail'];
        $row['log_flyer'] = $row1['log_flyer'];
        $row['log_line'] = $row1['log_line'];
        $row['log_contact_head_office'] = $row1['log_contact_head_office'];
        $row['log_tel_status'] = $row1['log_tel_status'];
        $row['log_mail_status'] = $row1['log_mail_status'];
        $row['log_revisit'] = $row1['log_revisit'];
        $row['source_id'] = $row1['source_id'];
        $row['log_time_mail'] = $row1['log_time_mail'];
        $row['log_date_appointment_to'] = $row1['log_date_appointment_to'];

        $row['aspirations_id'] = $row1['aspirations_id'];
        $row['aspirations_type_house'] = $row1['aspirations_type_house'];
        $row['aspirations_rent_cost'] = $row1['aspirations_rent_cost'];
        $row['aspirations_type_room'] = $row1['aspirations_type_room'];
        $row['aspirations_type_room_number'] = $row1['aspirations_type_room_number'];
        $row['aspirations_build_time'] = $row1['aspirations_build_time'];
        $row['aspirations_area'] = $row1['aspirations_area'];
        $row['aspirations_size'] = $row1['aspirations_size'];
        $row['aspirations_comment'] = $row1['aspirations_comment'];

        $row['order_id'] = $row1['order_id'];
        $row['order_name'] = $row1['order_name'];
        $row['house_id'] = $row1['house_id'];
        $row['order_rent_cost'] = $row1['order_rent_cost'];
        $row['order_day_create'] = $row1['order_day_create'];
        $row['order_status'] = $row1['order_status'];
        $row['order_comment'] = $row1['order_comment'];
        $row['order_day_update'] = $row1['order_day_update'];
        $row['create_id'] = $row1['create_id'];
        $row['broker_id'] = $row1['broker_id'];
        $row['order_change'] = $row1['order_change'];
        $row['change_house_array'] = $row1['change_house_array'];

        $query = "SELECT                               
                                hcon.id AS contract_id,
                                
                                hcd.id AS contract_detail_id,
                               
                                hcd.contract_cost AS contract_cost,
                                hcd.contract_total AS contract_total,
                                hcd.contract_application,
                                hcd.contract_application_date,
                                hcd.contract_signature_day,
                                hcd.contract_handover_day,
                                hcd.contract_condition,
                                hcd.contract_valuation,
                                hcd.contract_date_create,
                                hcd.contract_date_update,
                                hcd.contract_cancel,
                                hcd.contract_period_from,
                                hcd.contract_period_to,
                                hcd.contract_deposit_1,
                                hcd.contract_deposit_2,
                                hcd.contract_key_money,
                                hcd.contract_name,
                                hcd.contract_broker_fee,
                                hcd.contract_ads_fee,
                                hcd.contract_transaction_finish,
                                hcd.contract_payment_date_from,
                                hcd.contract_payment_date_to,
                                hcd.contract_payment_status,
                                hcd.contract_payment_report,
                                hcd.contract_ambition,
                                hcd.money_payment,
                                hcd.room_rented
                               
                                FROM home_order AS ho                                                                                                                               
                                
                                LEFT JOIN home_contract AS hcon ON ho.id=hcon.order_id,
                                
                                home_contract AS hct 
                                
                                LEFT JOIN home_contract_detail AS hcd ON hct.id=hcd.contract_id
                                
                                where hct.order_id={$order_id} and hct.client_id={$client_id}                                                                    
                                
                                LIMIT 1";

        $result = $database->database_query($query);

        $row1 = $database->database_fetch_assoc($result);

        $row['contract_id'] = $row1['contract_id'];
        $row['contract_detail_id'] = $row1['contract_detail_id'];
        $row['contract_cost'] = $row1['contract_cost'];
        $row['contract_total'] = $row1['contract_total'];
        $row['contract_application'] = $row1['contract_application'];
        $row['contract_application_date'] = $row1['contract_application_date'];
        $row['contract_signature_day'] = $row1['contract_signature_day'];
        $row['contract_handover_day'] = $row1['contract_handover_day'];
        $row['contract_condition'] = $row1['contract_condition'];
        $row['contract_valuation'] = $row1['contract_valuation'];
        $row['contract_date_create'] = $row1['contract_date_create'];
        $row['contract_date_update'] = $row1['contract_date_update'];
        $row['contract_cancel'] = $row1['contract_cancel'];
        $row['contract_period_from'] = $row1['contract_period_from'];
        $row['contract_period_to'] = $row1['contract_period_to'];
        $row['contract_deposit_1'] = $row1['contract_deposit_1'];
        $row['contract_deposit_2'] = $row1['contract_deposit_2'];
        $row['contract_key_money'] = $row1['contract_key_money'];
        $row['contract_name'] = $row1['contract_name'];
        $row['contract_payment_date_from'] = $row1['contract_payment_date_from'];
        $row['contract_payment_date_to'] = $row1['contract_payment_date_to'];
        $row['contract_payment_status'] = $row1['contract_payment_status'];
        $row['contract_payment_report'] = $row1['contract_payment_report'];
        $row['contract_broker_fee'] = $row1['contract_broker_fee'];
        $row['contract_ads_fee'] = $row1['contract_ads_fee'];
        $row['contract_transaction_finish'] = $row1['contract_transaction_finish'];
        $row['contract_ambition'] = $row1['contract_ambition'];
        $row['money_payment'] = $row1['money_payment'];
        $row['room_rented'] = $row1['room_rented'];

        $client_arr = $row;
        return array('client_arr' => $client_arr);
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
                client_room_type_number='{$client_room_type_number}',
                client_rent='{$client_rent}',
                client_reason_change= '{$client_reason_change}',
                client_time_change   ='{$client_time_change}',     
                client_resident_name='{$client_resident_name}',
                client_resident_phone='{$client_resident_phone}'    
                where id={$client_id}
                ";
        return $database->database_query($query);
    }

    function checkExistClient($client_name, $client_phone, $client_email) {
        global $database;
        $client_name = trim($client_name);
        $client_phone = trim($client_phone);
        $client_email = trim($client_email);
        $query = "select * from home_client where (client_phone='{$client_phone}' and  client_name='{$client_name}')"
                . " or (client_email='{$client_email}' and  client_name='{$client_name}')";

        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        if ($row >= 1)
            return true;
        else
            return false;
    }

    function sendmailhistory($subject, $client_email) {
        global $database, $user;
        $datetime = time();
        $query = "INSERT INTO history_send_email(
            `user_id`,
            `datetime`,
            `subject`,
            `inout`,
            `client_email`            
            ) VALUES(
                '{$user->user_info['id']}',
                '{$datetime}',
                '{$subject}',    
                '1',
                '{$client_email}'              
                )";
        //echo $query;die();
        return $database->database_query($query);
    }

    function clientSendMailHistory($emails) {
        global $database, $user;
        if ($emails) {
            $list = array();
            /* put the newest emails on top */
            //rsort($emails);
            /* for every email... */
            foreach ($emails as $email_number) {
                if ($email_number) {
                    // foreach ($email_number as $email) {
                    /* get information specific to this email */
                    $overview = imap_fetch_overview($inbox, $email_number, 0);
                    // $message = imap_fetchbody($inbox, $email, 1);var_dump($message);die();
                    //date sent
                    if ($overview[0]->date) {
                        $date_sent = explode(',', $overview[0]->date);
                        $date_sent = trim($date_sent[1]);
                        $date_sent = explode('+', $date_sent);
                        $date_sent = trim($date_sent[0]);

                        $fetch_array['date_sent'] = strtotime($date_sent);
                    } else {
                        $fetch_array['date_sent'] = "";
                    }
                    //get subject
                    $fetch_array['subject'] = $overview[0]->subject;
                    //get from
                    $fetch_array['from'] = $overview[0]->from;

                    $list[] = $fetch_array;
                }
            }
            if ($list) {
                foreach ($list as $lt) {
                    $query = "INSERT INTO history_send_email(
                    `user_id`,
                    `datetime`,
                    `subject`,
                    `inout`,
                    `client_email`            
                    ) VALUES(
                     '{$user->user_info['id']}',
                        '{$lt['date_sent']}',
                        '{$lt['subject']}',    
                        '2',
                        '{$lt['from']}'               
                     )";

                    $database->database_query($query);
                }
            }
        }
    }

    function getHistorySendEmail($user_id) {
        global $database;
        $list = array();
        $query = "select * from history_send_email where user_id='{$user_id}'";
        $result = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result)) {
            $list1['datetime'] = date('Y-m-d H:i', $row['datetime']);
            $list1['subject'] = $row['subject'];
            $list1['inout'] = $row['inout'];
            $list[] = $list1;
        }
        return $list;
    }

}
