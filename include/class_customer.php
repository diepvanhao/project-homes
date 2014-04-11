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

    function create_customer($client_name, $client_birthday, $client_email, $client_phone, $order_id, $client_id) {
        global $database, $user;

        $exits = FALSE;
        if ($client_id) {
            $exits = true;
        } elseif ($this->checkExistClient($client_phone)) {
            $exits = true;
        }
        if (!$exits) {
            $query = "insert into home_client(
                        user_id,
                        client_name,
                        client_birthday,
                        client_email,
                        client_phone                                                                                  
                     )values(
            {$user->user_info['id']},
                     '{$client_name}',
                     '{$client_birthday}',
                     '{$client_email}',
                     '{$client_phone}'
                    )";

            $result = $database->database_query($query);
            $id = $database->database_insert_id($result);
            //update order
            $query = "update home_order set client_id={$id}, user_id={$user->user_info['id']} where id={$order_id}";
            $database->database_query($query);
            return array('exist' => $exits, 'id' => $id, 'client_arr' => "");
        } else {
//            if ($client_id) {
//                //update order
//                $query = "update home_order set client_id={$client_id}, user_id={$user->user_info['id']} where id={$order_id}";
//                $database->database_query($query);
//                return array('exist' => $exits, 'id' => $client_id, 'client_arr' => "");
//            } else {
            $query = "select id from home_client where   client_phone='{$client_phone}' ";

            $result = $database->database_query($query);
            $row = $database->database_fetch_assoc($result);
            $id = $row['id'];

            //update order
            $query = "update home_order set client_id={$id}, user_id={$user->user_info['id']} where id={$order_id}";
            $database->database_query($query);


            //get information about client
            $query = "SELECT hc.id AS client_id_client,
                                hc.user_id AS user_id_client,
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

                                ,hhl.*, 

                                hha.id AS aspirations_id,
                                hha.aspirations_type_house AS aspirations_type_house,
                                hha.aspirations_rent_cost AS aspirations_rent_cost,
                                hha.aspirations_type_room AS aspirations_type_room,
                                hha.aspirations_build_time AS aspirations_build_time,
                                hha.aspirations_area AS aspirations_area,
                                hha.aspirations_size AS aspirations_size,
                                hha.aspirations_comment AS aspirations_comment

                                  FROM home_client AS hc

                                LEFT JOIN home_history_log AS hhl ON hc.id=hhl.client_id

                                LEFT JOIN home_history_aspirations AS hha ON hc.id=hha.client_id 
                                
                                where hc.id={$id}";

            $result = $database->database_query($query);

            while ($row = $database->database_fetch_assoc($result)) {
                $row['client_id_client'] = $row['client_id_client'];
                $row['user_id_client'] = $row['user_id_client'];
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

                $row['id'] = $row['id'];
                $row['user_id'] = $row['user_id'];
                $row['client_id'] = $row['client_id'];
                $row['log_time_call'] = $row['log_time_call'];
                $row['log_time_arrive_company'] = $row['log_time_arrive_company'];
                $row['log_comment'] = $row['log_comment'];
                $row['log_date_appointment'] = $row['log_date_appointment'];
                $row['log_status_appointment'] = $row['log_status_appointment'];
                $row['log_shop_sign'] = $row['log_shop_sign'];
                $row['log_local_sign'] = $row['log_local_sign'];
                $row['log_introduction'] = $row['log_introduction'];
                $row['log_tel'] = $row['log_tel'];
                $row['log_mail'] = $row['log_mail'];
                $row['log_flyer'] = $row['log_flyer'];
                $row['log_line'] = $row['log_line'];
                $row['log_contract_head_offcie'] = $row['log_contract_head_offcie'];
                $row['log_tel_status'] = $row['log_tel_status'];
                $row['log_mail_status'] = $row['log_mail_status'];
                $row['log_revisit'] = $row['log_revisit'];
                $row['log_time_mail'] = $row['log_time_mail'];

                $row['aspirations_id'] = $row['aspirations_id'];
                $row['aspirations_type_house'] = $row['aspirations_type_house'];
                $row['aspirations_rent_cost'] = $row['aspirations_rent_cost'];
                $row['aspirations_type_room'] = $row['aspirations_type_room'];
                $row['aspirations_build_time'] = $row['aspirations_build_time'];
                $row['aspirations_area'] = $row['aspirations_area'];
                $row['aspirations_size'] = $row['aspirations_size'];
                $row['aspirations_comment'] = $row['aspirations_comment'];
                $client_arr = $row;
            }

            return array('exist' => $exits, 'id' => $id, 'client_arr' => $client_arr);
        }
    }

    function update_customer($gender, $client_address, $client_occupation, $client_company, $client_income, $client_room_type, $client_rent, $client_reason_change, $client_time_change, $client_id, $order_id) {
        global $database;
        $query="update home_client set 
                client_gender= '{$gender}',
                client_address='{$client_address}',
                client_occupation='{$client_occupation}',
                client_company= '{$client_company}',
                client_income= '{$client_income}',
                client_room_type='{$client_room_type}',
                client_rent='{$client_rent}',
                client_reason_change= '{$client_reason_change}',
                client_time_change   ='{$client_time_change}'     
                    
                where id={$client_id}
                ";
          return $database->database_query($query);      
    }

    function checkExistClient($client_phone) {
        global $database;
        $query = "select * from home_client where client_phone='{$client_phone}' ";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        if ($row >= 1)
            return true;
        else
            return false;
    }

}
