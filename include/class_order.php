<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEOrder {

    function create_order($room_id, $order_name, $order_rent_cost, $order_comment, $create_id, $house_id, $broker_id, $order_day_create) {
        global $database;
        $change_house_array = serialize($room_id);
        //check house empty
        $checkExist = $this->checkHouseEmpty($house_id, $room_id);
        if ($checkExist)
            return array('error' => $checkExist);
        else {

            $query = "insert into home_order(
                `order_name`,
                `user_id`,
                `house_id`,
                `room_id`,
                `client_id`,
                `order_rent_cost`,
                `order_day_create`,
                `order_status`,
                `order_comment`,
                `order_day_update`,
               `create_id`,
                `broker_id`,
                `change`,
                `change_house_array`)
                values(
                '{$order_name}',
                0,
                '{$house_id}',
                '{$room_id}',    
                0,
                '{$order_rent_cost}',
                '{$order_day_create}',
                1,
                '{$order_comment}',
                '{$order_day_create}',
                '{$create_id}',
                '{$broker_id}',
                 0,
                '{$change_house_array}'
                )";

            $result = $database->database_query($query);
            $id = $database->database_insert_id();
            if ($id) {
                $houseClass=new HOMEHouse();
                $room_detail_id = getRoomDetailId($room_id, $house_id); 
                //update room status
                $query = "update home_room_detail set room_status=1 where id='{$room_detail_id}'";
                $database->database_query($query);
            }
            return array('id' => $id);
        }
    }

    function checkHouseEmpty($house_id, $room_id) {
        global $database;
        $query = "select id from home_order where house_id='{$house_id}' and room_id='{$room_id}' and order_status=1 limit 1";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        if ($row >= 1) {
            $result = $database->database_fetch_assoc($result);
            return $result['id'];
        } else {
            return FALSE;
        }
    }

    function checkRoom($room_id, $broker_id) {
        global $database;
        $query = "select * from home_room hr left join home_room_detail hrd on hr.id=hrd.room_id where hr.id='{$room_id}' and hr.broker_id='{$broker_id}'";
        $result = $database->database_query($query);

        $row = $database->database_num_rows($result);
        if ($row >= 1)
            return true;
        else
            return FALSE;
    }

    function getTotalItem($search) {
        global $database;

        $query = "select * from home_order";
        if (!empty($search))
            $query.=" where order_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getOrder($search = "", $offset = 0, $length = 50) {
        global $database;

        $query = "select ho.*,hh.house_name,hc.client_name from home_order as ho
               left join home_house as hh on ho.house_id=hh.id   
               left join home_client as hc on ho.client_id=hc.id
                ";
        if (!empty($search))
            $query.=" where order_name like '%{$search}%'";

        $query.=" limit $offset,$length";
        //echo $query;
        $result = $database->database_query($query);
        $order_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $order['id'] = $row['id'];
            $order['order_name'] = $row['order_name'];
            $order['user_id'] = $row['user_id'];
            $order['house_id'] = $row['house_id'];
            $order['house_name'] = $row['house_name'];
            $order['room_id'] = $row['room_id'];
            $order['client_id'] = $row['client_id'];
            $order['client_name'] = $row['client_name'];
            $order['order_rent_cost'] = $row['order_rent_cost'];
            $order['order_day_create'] = $row['order_day_create'];
            $order['order_status'] = $row['order_status'];
            $order['order_comment'] = $row['order_comment'];
            $order['order_day_update'] = $row['order_day_update'];
            $order['create_id'] = $row['create_id'];
            $order['broker_id'] = $row['broker_id'];
            $order['change'] = $row['change'];
            $order['change_house_array'] = $row['change_house_array'];
            $order_arr[] = $order;
        }
        return $order_arr;
    }

    function getClientByOrderId($order_id) {
        global $database;
        //get client_id
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
                                hc.client_room_type AS client_room_type,
                                
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
                                hhl.log_time_mail,
                                hhl.log_date_appointment_to AS log_date_appointment_to,
                                hhl.log_payment_date_appointment_from,
                                hhl.log_payment_date_appointment_to,
                                hhl.log_payment_appointment_status,
                                hhl.log_payment_appointment_report,

                                hha.id AS aspirations_id,
                                hha.aspirations_type_house AS aspirations_type_house,
                                hha.aspirations_rent_cost AS aspirations_rent_cost,
                                hha.aspirations_type_room AS aspirations_type_room,
                                hha.aspirations_build_time AS aspirations_build_time,
                                hha.aspirations_area AS aspirations_area,
                                hha.aspirations_size AS aspirations_size,
                                hha.aspirations_comment AS aspirations_comment
                                
                                FROM home_order AS ho 
                                
                                LEFT JOIN home_client as hc on ho.client_id=hc.id
                                
                                LEFT JOIN home_history_log AS hhl ON ho.id=hhl.order_id

                                LEFT JOIN home_history_aspirations AS hha ON ho.id=hha.order_id 
                                where ho.id={$order_id}                                                             
                                LIMIT 1";

        $result = $database->database_query($query);
        $client_arr = array();
        $client = array();

        $row = $database->database_fetch_assoc($result);
        $client['client_id'] = $row['client_id'];
        $client['user_id'] = $row['user_id'];
        $client['client_name'] = $row['client_name'];
        $client['client_birthday'] = $row['client_birthday'];
        $client['client_address'] = $row['client_address'];
        $client['client_phone'] = $row['client_phone'];
        $client['client_income'] = $row['client_income'];
        $client['client_occupation'] = $row['client_occupation'];
        $client['client_company'] = $row['client_company'];
        $client['client_fax'] = $row['client_fax'];
        $client['client_gender'] = $row['client_gender'];
        $client['client_email'] = $row['client_email'];
        $client['client_reason_change'] = $row['client_reason_change'];
        $client['client_time_change'] = $row['client_time_change'];
        $client['client_photo'] = $row['client_photo'];
        $client['client_resident_name'] = $row['client_resident_name'];
        $client['client_resident_phone'] = $row['client_resident_phone'];
        $client['client_rent'] = $row['client_rent'];
        $client['client_room_type'] = $row['client_room_type'];

        $client['history_log_id'] = $row['history_log_id'];
        $client['log_time_call'] = $row['log_time_call'];
        $client['log_time_arrive_company'] = $row['log_time_arrive_company'];
        $client['log_comment'] = $row['log_comment'];
        $client['log_date_appointment_from'] = $row['log_date_appointment_from'];
        $client['log_status_appointment'] = $row['log_status_appointment'];
        $client['log_shop_sign'] = $row['log_shop_sign'];
        $client['log_local_sign'] = $row['log_local_sign'];
        $client['log_introduction'] = $row['log_introduction'];
        $client['log_tel'] = $row['log_tel'];
        $client['log_mail'] = $row['log_mail'];
        $client['log_flyer'] = $row['log_flyer'];
        $client['log_line'] = $row['log_line'];
        $client['log_contact_head_office'] = $row['log_contact_head_office'];
        $client['log_tel_status'] = $row['log_tel_status'];
        $client['log_mail_status'] = $row['log_mail_status'];
        $client['log_revisit'] = $row['log_revisit'];
        $client['log_time_mail'] = $row['log_time_mail'];
        $client['log_date_appointment_to'] = $row['log_date_appointment_to'];
        $client['log_payment_date_appointment_from'] = $row['log_payment_date_appointment_from'];
        $client['log_payment_date_appointment_to'] = $row['log_payment_date_appointment_to'];
        $client['log_payment_appointment_status'] = $row['log_payment_appointment_status'];
        $client['log_payment_appointment_report'] = $row['log_payment_appointment_report'];

        $client['aspirations_id'] = $row['aspirations_id'];
        $client['aspirations_type_house'] = $row['aspirations_type_house'];
        $client['aspirations_rent_cost'] = $row['aspirations_rent_cost'];
        $client['aspirations_type_room'] = $row['aspirations_type_room'];
        $client['aspirations_build_time'] = $row['aspirations_build_time'];
        $client['aspirations_area'] = $row['aspirations_area'];
        $client['aspirations_size'] = $row['aspirations_size'];
        $client['aspirations_comment'] = $row['aspirations_comment'];
        // }
        //get contact 
        $query = "SELECT                               
                                hcon.id AS contract_id,
                                
                                hcd.id AS contract_detail_id,
                                hcd.contract_plus_money AS contract_plus_money,
                                hcd.contract_cost AS contract_cost,
                                hcd.contract_total AS contract_total,
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
                                hcd.contract_name
                                
                                FROM home_order AS ho                                                                                                                               
                                
                                LEFT JOIN home_contract AS hcon ON ho.id=hcon.order_id,
                                
                                home_contract AS hct 
                                
                                LEFT JOIN home_contract_detail AS hcd ON hct.id=hcd.contract_id
                                
                                where ho.id={$order_id}                                                                
                                
                                LIMIT 1";
        $result = $database->database_query($query);

        $row = $database->database_fetch_assoc($result);

        $client['contract_id'] = $row['contract_id'];
        $client['contract_detail_id'] = $row['contract_detail_id'];
        $client['contract_plus_money'] = $row['contract_plus_money'];
        $client['contract_cost'] = $row['contract_cost'];
        $client['contract_total'] = $row['contract_total'];
        $client['contract_signature_day'] = $row['contract_signature_day'];
        $client['contract_handover_day'] = $row['contract_handover_day'];
        $client['contract_condition'] = $row['contract_condition'];
        $client['contract_valuation'] = $row['contract_valuation'];
        $client['contract_date_create'] = $row['contract_date_create'];
        $client['contract_date_update'] = $row['contract_date_update'];
        $client['contract_cancel'] = $row['contract_cancel'];
        $client['contract_period_from'] = $row['contract_period_from'];
        $client['contract_period_to'] = $row['contract_period_to'];
        $client['contract_deposit_1'] = $row['contract_deposit_1'];
        $client['contract_deposit_2'] = $row['contract_deposit_2'];
        $client['contract_key_money'] = $row['contract_key_money'];
        $client['contract_name'] = $row['contract_name'];
        // }
        $client_arr = $client;
        return $client_arr;
    }

}
