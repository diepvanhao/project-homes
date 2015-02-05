<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('class_client.php');
@include_once "class_ajax.php";

class HOMEOrder {

    function create_order($room_id, $order_name, $order_rent_cost, $order_comment, $create_id, $house_id, $broker_id, $order_day_create) {
        global $database;
        $room_arr = Array();
        $room_arr[] = $room_id . "_" . $house_id . "_" . $broker_id;
        $change_house_array = serialize($room_arr);
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
//            if ($id) {
//                $houseClass = new HOMEHouse();
//                $room_detail_id = getRoomDetailId($room_id, $house_id);
//                //update room status
//                $query = "update home_room_detail set room_status=1 where id='{$room_detail_id}'";
//                $database->database_query($query);
//            }
            return array('id' => $id);
        }
    }

    function create_order_fetch_email($message_id) {
        global $database, $user;
        if ($message_id) {
            //get info of message
            $query = "select * from home_fetch_email where id='{$message_id}'";
            $result = $database->database_query($query);
            $row = $database->database_fetch_assoc($result);

            if (!empty($row)) {
                //prepare data create order
                //house
                $house = new HOMEHouse();
                $house_id = $house->getHouseByName($row['house_name']);

                if (!$house_id) {
                    //create new house
                    //get house address exist in system
                    $house_address = $house->getHouseAddress($row['house_address']);
                    //get house type exist in system
                    $house_type = $house->getHouseTypeByName($row['house_type']);
                    $row['house_name'] = trim($row['house_name']);
                    $house_id = $house->create($row['house_name'], $house_address, null, null, $house_type, null, null, null, null, null, null, null, $row['house_address'], null);
                }
                //client 
                $customer = new HOMECustomer();
                $client_id = $customer->create_customer_fetch_email($row['client_name'], $row['client_read_way'], $row['client_phone'], $row['client_email'], $row['client_address']);
                $order_name = $this->generate_order_name();
                if ($house_id && $client_id) {
                    $order_day_create = time();
                    $query = "insert into home_order(
                `order_name`,
                `user_id`,
                `house_id`,                
                `client_id`,
                `order_rent_cost`,
                `order_day_create`,
                `order_status`,                
                `order_day_update`,
               `create_id`                
                )
                values(
                '{$order_name}',  
                '{$user->user_info['id']}',
                '{$house_id}',                    
                '{$client_id}',
                '{$row['rent_cost']}',
                '{$order_day_create}',
                1,               
                '{$order_day_create}',
                '{$user->user_info['id']}'                               
                )";

                    $result = $database->database_query($query);
                    $order_id = $database->database_insert_id();
                    if ($order_id) {
                        //update history
                        //get source_id;
                        $source_id = $house->getSourceByName($row['source_name']);
                        $ajax = new ajax();
                        
                        $ajax->update_history_create($row['date_sent'], null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null, $source_id, null, $client_id, $order_id);
                        //update contract
                        $contract_cost = str_replace('円', '', $row['rent_cost']);
                        //change 万 into 円
                        // $room_administrative_expense=  str_replace("円", "", $room_administrative_expense);
                        if (strpos($contract_cost, '万')) {
                            $room_exp = explode("万", $contract_cost);
                            $contract_cost = $room_exp[0] * 10000 + ($room_exp[1] != "" ? $room_exp[1] : 0);
                        }
                        //$contract_cost = $contract_cost != "" ? number_format($contract_cost, 0, '', ',') : $contract_cost;

                        $ajax->update_contract(null, $contract_cost, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $client_id, $order_id);
                        //update status message
                        $query = "update home_fetch_email set status=1 where id='{$message_id}'";
                        $database->database_query($query);
                        $str = 'edit&' . $order_id;
                        $link = 'edit_order.php?url=' . base64_encode($str);

                        header('Location: ' . $link);
                    }
                }
            } else {
                return 0;
            }
        }
    }

    function create_fetch_email($order) {

        global $database;
        // $checkExistMessage
        for ($i = 0; $i < count($order); $i++) {
            if (!$this->checkExistMessage($order[$i]['house_name'], $order[$i]['client_name'], $order[$i]['client_email'], $order[$i]['date_sent'])) {
                if (!empty($order[$i]['house_name']) && !empty($order[$i]['client_name'])) {
                    $query = "insert into home_fetch_email(
                `house_type`,
                `house_name`,
                `house_address`,
                `rent_cost`,
                `client_name`,
                `client_read_way`,
                `client_address`,
                `client_email`,
                `client_phone`,
                `source_name`,
               `date_sent`,
                `status`
                )
                values(
                '{$order[$i]['house_type']}',
                '{$order[$i]['house_name']}',
                '{$order[$i]['house_address']}',
                '{$order[$i]['rent_cost']}',    
                '{$order[$i]['client_name']}',
                '{$order[$i]['client_read_way']}',
                '{$order[$i]['client_address']}',
                '{$order[$i]['client_email']}',
                '{$order[$i]['client_phone']}',
                '{$order[$i]['source']}',
                '{$order[$i]['date_sent']}',
                0                
                )";
                    $result = $database->database_query($query);
                }
            }
        }
    }
    function get_message_total($search){
        global $database;
        $query = "select * from home_fetch_email";
        if (!empty($search))
            $query.=" where house_type like '%{$search}%' or house_name like '%{$search}%' or house_address like '%{$search}%' or rent_cost like '%{$search}%'"
            . " or client_name like '%{$search}%' or client_email like '%{$search}%' or client_phone like '%{$search}%' or source_name like '%{$search}%'";        
        
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }
    function get_message($search, $offset, $length) {
        global $database;
        $query = "select * from home_fetch_email";
        if (!empty($search))
            $query.=" where house_type like '%{$search}%' or house_name like '%{$search}%' or house_address like '%{$search}%' or rent_cost like '%{$search}%'"
            . " or client_name like '%{$search}%' or client_email like '%{$search}%' or client_phone like '%{$search}%' or source_name like '%{$search}%'";
        $query.=" order by status ASC";    
        $query.=" limit $offset,$length";
        
        $result = $database->database_query($query);
        $message = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $message[] = $row;
        }
        return $message;
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

        //$query = "select * from home_order";
        $query = "select ho.*,hh.house_name,hc.client_name,
            l.source_id,l.log_time_call,l.log_time_arrive_company,l.log_comment,l.log_date_appointment_from,
            l.log_status_appointment,l.log_shop_sign,l.log_local_sign,l.log_introduction,l.log_tel,l.log_mail,
            l.log_flyer,l.log_line,l.log_contact_head_office,l.log_tel_status,l.log_mail_status,l.log_revisit,l.log_time_mail,l.log_date_appointment_to,
            d.contract_id,d.contract_cost,d.contract_total,d.contract_signature_day,d.contract_handover_day,d.contract_condition,d.contract_valuation,
            d.contract_date_create,d.contract_date_update,d.contract_cancel,d.contract_period_from,d.contract_period_to,d.contract_deposit_1,d.contract_deposit_2,
            d.contract_key_money,d.contract_name,d.contract_application,d.contract_application_date,d.contract_broker_fee,d.contract_ads_fee,d.contract_transaction_finish,
            d.contract_payment_date_from,d.contract_payment_date_to,d.contract_payment_status,d.contract_payment_report,d.contract_ambition,d.money_payment,d.room_rented
            
               from home_order as ho
               left join home_house as hh on ho.house_id=hh.id   
               left join home_client as hc on ho.client_id=hc.id
               LEFT JOIN home_history_log AS l ON l.order_id = ho.id
               LEFT JOIN home_contract AS c ON c.order_id = ho.id
               LEFT JOIN home_contract_detail AS d ON d.contract_id = c.id
                ";
        if ($search['order_name'] ||
                $search['house_name'] ||
                $search['room_id'] ||
                $search['order_rent_cost'] ||
                $search['order_status'] ||
                $search['order_day_create'] ||
                $search['client_name'] ||
                $search['log_revisit'] ||
                $search['contract_application_date'] ||
                $search['money_payment'] ||
                $search['contract_signature_day'] ||
                $search['contract_payment_date_from'] ||
                $search['contract_payment_date_to'] ||
                $search['contract_handover_day']
        ) {
            $query.=" where";
            if ($search['order_name'])
                $query.=" ho.order_name like '%{$search['order_name']}%'";
            if ($search['house_name'])
                $query.=" and hh.house_name like '%{$search['house_name']}%'";
            if ($search['room_id'])
                $query.=" and ho.room_id = '{$search['room_id']}'";
            if ($search['order_rent_cost'])
                $query.=" and ho.order_rent_cost = '{$search['order_rent_cost']}'";
            if ($search['order_status']) {
                switch ($search['order_status']) {
                    case 1:
                        $query.=" and d.room_rented = '1'";
                        break;
                    case 2:
                        $query.=" and d.contract_cancel = '1'";
                        break;
                    case 3:
                        $query.=" and (d.contract_signature_day !='' or d.contract_signature_day !='0')";
                        break;
                    case 4:
                        $query.=" and (d.contract_application != '' or d.contract_application !='0')";
                        break;
                    case 5:
                        //$query.=" and ho.order_status = '{$search['order_status']}'";
                        break;
                    default :
                    //$query.=" and ho.order_status = '{$search['order_status']}'";
                }
            }
            if ($search['order_day_create']) {
                if (strpos($search['order_day_create'], '/')) {
                    $min = strtotime($search['order_day_create'] . ' ' . '00:00');
                    $max = strtotime($search['order_day_create'] . ' ' . '23:59');
                    $search['order_day_create'] = strtotime($search['order_day_create']);
                    $query.=" and ho.order_day_create >'{$min}' and ho.order_day_create < '{$max}'";
                } else {
                    $query.=" and (ho.order_day_create ='' or ho.order_day_create ='0')";
                }
            }
            if ($search['client_name'])
                $query.=" and hc.client_name like '%{$search['client_name']}%'";
            if ($search['log_revisit']) {
                if (strpos($search['log_revisit'], '/'))
                    $query.=" and l.log_revisit like '%{$search['log_revisit']}%'";
                else
                    $query.=" and (l.log_revisit = '' or l.log_revisit='0')";
            }
            if ($search['contract_application_date']) {
                if (strpos($search['contract_application_date'], '/')) {
                    $search['contract_application_date'] = strtotime($search['contract_application_date']);
                    $query.=" and d.contract_application_date = '{$search['contract_application_date']}'";
                } else {
                    $query.=" and (d.contract_application_date = '' or d.contract_application_date = '0')";
                }
            }
            if ($search['money_payment'])
                $query.=" and d.money_payment = '{$search['money_payment']}'";
            if ($search['contract_signature_day']) {
                if (strpos($search['contract_signature_day'], '/')) {
                    $min = strtotime($search['contract_signature_day'] . ' ' . '00:00');
                    $max = strtotime($search['contract_signature_day'] . ' ' . '23:59');
                    $search['contract_signature_day'] = strtotime($search['contract_signature_day']);
                    $query.=" and d.contract_signature_day > '{$min}' and d.contract_signature_day < '{$max}'";
                } else {
                    $query.=" and (d.contract_signature_day = '' or d.contract_signature_day ='0')";
                }
            }
            if ($search['contract_payment_date_from']) {
                if (strpos($search['contract_payment_date_from'], '/')) {
                    $search['contract_payment_date_from'] = strtotime($search['contract_payment_date_from']);
                    $query.=" and d.contract_payment_date_from = '{$search['contract_payment_date_from']}'";
                } else {
                    $query.=" and (d.contract_payment_date_from = '' or d.contract_payment_date_from = '0')";
                }
            }
            if ($search['contract_payment_date_to']) {
                if (strpos($search['contract_payment_date_to'], '/')) {
                    $search['contract_payment_date_to'] = strtotime($search['contract_payment_date_to']);
                    $query.=" and d.contract_payment_date_to = '{$search['contract_payment_date_to']}'";
                } else {
                    $query.=" and (d.contract_payment_date_to = '' or d.contract_payment_date_to = '0')";
                }
            }
            if ($search['contract_handover_day']) {
                if (strpos($search['contract_handover_day'], '/')) {
                    $min = strtotime($search['contract_handover_day'] . ' ' . '00:00');
                    $max = strtotime($search['contract_handover_day'] . ' ' . '23:59');
                    $search['contract_handover_day'] = strtotime($search['contract_handover_day']);
                    $query.=" and d.contract_handover_day > '{$min}' and d.contract_handover_day < '{$max}'";
                } else {
                    $query.=" and (d.contract_handover_day = '' or d.contract_handover_day = '0')";
                }
            }
        }
        $query = str_replace("where and", "where", $query);
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getOrder($search = "", $offset = 0, $length = 50) {
        global $database;

        $query = "select ho.*,hh.house_name,hc.client_name,
            l.source_id,l.log_time_call,l.log_time_arrive_company,l.log_comment,l.log_date_appointment_from,
            l.log_status_appointment,l.log_shop_sign,l.log_local_sign,l.log_introduction,l.log_tel,l.log_mail,
            l.log_flyer,l.log_line,l.log_contact_head_office,l.log_tel_status,l.log_mail_status,l.log_revisit,l.log_time_mail,l.log_date_appointment_to,
            d.contract_id,d.contract_cost,d.contract_total,d.contract_signature_day,d.contract_handover_day,d.contract_condition,d.contract_valuation,
            d.contract_date_create,d.contract_date_update,d.contract_cancel,d.contract_period_from,d.contract_period_to,d.contract_deposit_1,d.contract_deposit_2,
            d.contract_key_money,d.contract_name,d.contract_application,d.contract_application_date,d.contract_broker_fee,d.contract_ads_fee,d.contract_transaction_finish,
            d.contract_payment_date_from,d.contract_payment_date_to,d.contract_payment_status,d.contract_payment_report,d.contract_ambition,d.money_payment,d.room_rented
            
               from home_order as ho
               left join home_house as hh on ho.house_id=hh.id   
               left join home_client as hc on ho.client_id=hc.id
               LEFT JOIN home_history_log AS l ON l.order_id = ho.id
               LEFT JOIN home_contract AS c ON c.order_id = ho.id
               LEFT JOIN home_contract_detail AS d ON d.contract_id = c.id
                ";
        if ($search['order_name'] ||
                $search['house_name'] ||
                $search['room_id'] ||
                $search['order_rent_cost'] ||
                $search['order_status'] ||
                $search['order_day_create'] ||
                $search['client_name'] ||
                $search['log_revisit'] ||
                $search['contract_application_date'] ||
                $search['money_payment'] ||
                $search['contract_signature_day'] ||
                $search['contract_payment_date_from'] ||
                $search['contract_payment_date_to'] ||
                $search['contract_handover_day']
        ) {
            $query.=" where";
            if ($search['order_name'])
                $query.=" ho.order_name like '%{$search['order_name']}%'";
            if ($search['house_name'])
                $query.=" and hh.house_name like '%{$search['house_name']}%'";
            if ($search['room_id'])
                $query.=" and ho.room_id = '{$search['room_id']}'";
            if ($search['order_rent_cost'])
                $query.=" and ho.order_rent_cost = '{$search['order_rent_cost']}'";
            if ($search['order_status']) {
                switch ($search['order_status']) {
                    case 1:
                        $query.=" and d.room_rented = '1'";
                        break;
                    case 2:
                        $query.=" and d.contract_cancel = '1'";
                        break;
                    case 3:
                        $query.=" and (d.contract_signature_day !='' or d.contract_signature_day !='0')";
                        break;
                    case 4:
                        $query.=" and (d.contract_application != '' or d.contract_application !='0')";
                        break;
                    case 5:
                        //$query.=" and ho.order_status = '{$search['order_status']}'";
                        break;
                    default :
                    //$query.=" and ho.order_status = '{$search['order_status']}'";
                }
            }
            if ($search['order_day_create']) {
                if (strpos($search['order_day_create'], '/')) {
                    $min = strtotime($search['order_day_create'] . ' ' . '00:00');
                    $max = strtotime($search['order_day_create'] . ' ' . '23:59');
                    $search['order_day_create'] = strtotime($search['order_day_create']);
                    $query.=" and ho.order_day_create >'{$min}' and ho.order_day_create < '{$max}'";
                } else {
                    $query.=" and (ho.order_day_create ='' or ho.order_day_create ='0')";
                }
            }
            if ($search['client_name'])
                $query.=" and hc.client_name like '%{$search['client_name']}%'";
            if ($search['log_revisit']) {
                if (strpos($search['log_revisit'], '/'))
                    $query.=" and l.log_revisit like '%{$search['log_revisit']}%'";
                else
                    $query.=" and (l.log_revisit = '' or l.log_revisit='0')";
            }
            if ($search['contract_application_date']) {
                if (strpos($search['contract_application_date'], '/')) {
                    $search['contract_application_date'] = strtotime($search['contract_application_date']);
                    $query.=" and d.contract_application_date = '{$search['contract_application_date']}'";
                } else {
                    $query.=" and (d.contract_application_date = '' or d.contract_application_date = '0')";
                }
            }
            if ($search['money_payment'])
                $query.=" and d.money_payment = '{$search['money_payment']}'";
            if ($search['contract_signature_day']) {
                if (strpos($search['contract_signature_day'], '/')) {
                    $min = strtotime($search['contract_signature_day'] . ' ' . '00:00');
                    $max = strtotime($search['contract_signature_day'] . ' ' . '23:59');
                    $search['contract_signature_day'] = strtotime($search['contract_signature_day']);
                    $query.=" and d.contract_signature_day > '{$min}' and d.contract_signature_day < '{$max}'";
                } else {
                    $query.=" and (d.contract_signature_day = '' or d.contract_signature_day ='0')";
                }
            }
            if ($search['contract_payment_date_from']) {
                if (strpos($search['contract_payment_date_from'], '/')) {
                    $search['contract_payment_date_from'] = strtotime($search['contract_payment_date_from']);
                    $query.=" and d.contract_payment_date_from = '{$search['contract_payment_date_from']}'";
                } else {
                    $query.=" and (d.contract_payment_date_from = '' or d.contract_payment_date_from = '0')";
                }
            }
            if ($search['contract_payment_date_to']) {
                if (strpos($search['contract_payment_date_to'], '/')) {
                    $search['contract_payment_date_to'] = strtotime($search['contract_payment_date_to']);
                    $query.=" and d.contract_payment_date_to = '{$search['contract_payment_date_to']}'";
                } else {
                    $query.=" and (d.contract_payment_date_to = '' or d.contract_payment_date_to = '0')";
                }
            }
            if ($search['contract_handover_day']) {
                if (strpos($search['contract_handover_day'], '/')) {
                    $min = strtotime($search['contract_handover_day'] . ' ' . '00:00');
                    $max = strtotime($search['contract_handover_day'] . ' ' . '23:59');
                    $search['contract_handover_day'] = strtotime($search['contract_handover_day']);
                    $query.=" and d.contract_handover_day > '{$min}' and d.contract_handover_day < '{$max}'";
                } else {
                    $query.=" and (d.contract_handover_day = '' or d.contract_handover_day = '0')";
                }
            }
        }
        $query.=" limit $offset,$length";
        $query = str_replace("where and", "where", $query);


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
            $order['order_day_create'] = date('Y-m-d', $row['order_day_create']);
            $order['order_status'] = $row['order_status'];
            $order['order_comment'] = $row['order_comment'];
            $order['order_day_update'] = date('Y-m-d', $row['order_day_update']);
            $order['create_id'] = $row['create_id'];
            $order['broker_id'] = $row['broker_id'];
            $order['change'] = $row['change'];
            $order['change_house_array'] = $row['change_house_array'];
            $order_arr[] = array_merge($row, $order);
        }

        return $order_arr;
    }

    function getClientByOrderId($order_id) {
        global $database;
        //get client_id
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
                                hc.client_room_type_number AS client_room_type_number,
                                
                                hhl.id AS history_log_id, 
                                hhl.source_id,
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
                                
                                hha.id AS aspirations_id,
                                hha.aspirations_type_house AS aspirations_type_house,
                                hha.aspirations_rent_cost AS aspirations_rent_cost,
                                hha.aspirations_type_room AS aspirations_type_room,
                                hha.aspirations_type_room_number AS aspirations_type_room_number,
                                hha.aspirations_build_time AS aspirations_build_time,
                                hha.aspirations_area AS aspirations_area,
                                hha.aspirations_size AS aspirations_size,
                                hha.aspirations_comment AS aspirations_comment,
                                
                                ho.order_name,
                                ho.order_rent_cost,
                                ho.order_comment,
                                ho.house_id,
                                ho.broker_id,
                                ho.room_id,
                                ho.change_house_array
                                
                                FROM home_order AS ho 
                                
                                LEFT JOIN home_client as hc on ho.client_id=hc.id
                                
                                LEFT JOIN home_history_log AS hhl ON ho.id=hhl.order_id

                                LEFT JOIN home_history_aspirations AS hha ON ho.id=hha.order_id 
                                where ho.id={$order_id}                                                             
                                LIMIT 1";
//echo $query;
        $result = $database->database_query($query);
        $client_arr = array();
        $client = array();

        $row = $database->database_fetch_assoc($result);
        $client['client_id'] = $row['client_id'];
        $client['user_id'] = $row['user_id'];
        $client['client_name'] = $row['client_name'];
        $client['client_read_way'] = $row['client_read_way'];
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
        $client['client_room_type_number'] = $row['client_room_type_number'];

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
        $client['source_id'] = $row['source_id'];
        $client['log_time_mail'] = $row['log_time_mail'];
        $client['log_date_appointment_to'] = $row['log_date_appointment_to'];

        $client['aspirations_id'] = $row['aspirations_id'];
        $client['aspirations_type_house'] = $row['aspirations_type_house'];
        $client['aspirations_rent_cost'] = $row['aspirations_rent_cost'];
        $client['aspirations_type_room'] = $row['aspirations_type_room'];
        $client['aspirations_type_room_number'] = $row['aspirations_type_room_number'];
        $client['aspirations_build_time'] = $row['aspirations_build_time'];
        $client['aspirations_area'] = $row['aspirations_area'];
        $client['aspirations_size'] = $row['aspirations_size'];
        $client['aspirations_comment'] = $row['aspirations_comment'];

        $client['order_name'] = $row['order_name'];
        $client['order_comment'] = $row['order_comment'];
        $client['order_rent_cost'] = $row['order_rent_cost'];
        $client['house_id'] = $row['house_id'];
        $client['broker_id'] = $row['broker_id'];
        $client['room_id'] = $row['room_id'];
        $client['change_house_array'] = $row['change_house_array'];
        // }
        //get contact 
        $query = "SELECT                               
                                hcon.id AS contract_id,                                
                                hcd.id AS contract_detail_id,                               
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
                                hcd.contract_name,
                                hcd.contract_application,
                                hcd.contract_application_date,
                                hcd.contract_broker_fee,
                                hcd.contract_ads_fee,
                                hcd.contract_transaction_finish,
                                hcd.contract_payment_date_from,
                                hcd.contract_payment_date_to,
                                hcd.contract_payment_status,
                                hcd.contract_payment_report,
                                hcd.contract_ambition,
                                hcd.money_payment,
                                hcd.room_rented,
                                hcd.room_administrative_expense
                                
                                FROM home_order AS ho                                                                                                                               
                                
                                LEFT JOIN home_contract AS hcon ON ho.id=hcon.order_id,
                                
                                home_contract AS hct 
                                
                                LEFT JOIN home_contract_detail AS hcd ON hct.id=hcd.contract_id
                                
                                where hct.order_id={$order_id}                                                                
                                
                                LIMIT 1";
        $result = $database->database_query($query);

        $row = $database->database_fetch_assoc($result);

        $client['contract_id'] = $row['contract_id'];
        $client['contract_detail_id'] = $row['contract_detail_id'];
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
        $client['contract_application'] = $row['contract_application'];
        $client['contract_application_date'] = $row['contract_application_date'];
        $client['contract_payment_date_from'] = $row['contract_payment_date_from'];
        $client['contract_payment_date_to'] = $row['contract_payment_date_to'];
        $client['contract_payment_status'] = $row['contract_payment_status'];
        $client['contract_payment_report'] = $row['contract_payment_report'];
        $client['contract_broker_fee'] = $row['contract_broker_fee'];
        $client['contract_ads_fee'] = $row['contract_ads_fee'];
        $client['contract_transaction_finish'] = $row['contract_transaction_finish'];
        $client['contract_ambition'] = $row['contract_ambition'];
        $client['money_payment'] = $row['money_payment'];
        $client['room_rented'] = $row['room_rented'];
        $client['room_administrative_expense'] = $row['room_administrative_expense'];
        // }
        $client_arr = $client;
        return $client_arr;
    }

    function getPlusMoney($contract_detail_id) {
        global $database;
        $query = "select * from home_plus_money where contract_detail_id={$contract_detail_id}";
        $result = $database->database_query($query);
        $plus_money = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $plus_money[$row['label']] = $row['price'];
            // $plus_money[]=$money;
        }
        return $plus_money;
    }

    function getContractDetailId($contract_id) {
        global $database;
        $query = "select id from home_contract_detail where  contract_id='{$contract_id}' ";

        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        $contract_detail_id = $row['id'];
        return $contract_detail_id;
    }

    function getPartnerId($contract_detail_id) {
        global $database;
        $query = "select * from home_contract_partner where contract_detail_id={$contract_detail_id} order by id DESC limit 1";
        $result = $database->database_query($query);
        $partner_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $partner['id'] = $row['id'];
            $partner['partner_id'] = $row['partner_id'];
            $partner['partner_percent'] = $row['partner_percent'];
            $partner_arr[] = $partner;
        }
        return $partner_arr;
    }

    function generate_order_name() {
        global $database, $user;
        $query = "select id from home_order order by id DESC limit 1";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        $id = $row['id'] + 1;
        return $user->user_info['user_lname'] . "_" . $user->user_info['user_fname'] . "_" . $id;
    }

    //fetch events for private schedule     
    function fetchEvents($user_id) {
        global $database, $user;
        $client = new Client();
        $events = array();
        $query = "select ho.* from home_order as ho          
                where ho.user_id='{$user_id}'";
        $result = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result)) {
            //get client infomation
            $birthday[] = $row['client_id'];
            //contract info
            $query = "select home_contract_detail.* from home_contract "
                    . "left join home_contract_detail on home_contract.id = home_contract_detail.contract_id"
                    . " where home_contract.user_id='{$user_id}' and home_contract.order_id='{$row['id']}'";
            $data = $database->database_query($query);
            while ($schedule = $database->database_fetch_assoc($data)) {
                if ($schedule['contract_signature_day']) {
                    $event['id'] = $schedule['id'];
                    $event['title'] = "Signature date";
                    //$temp = explode(" ", $schedule['contract_signature_day']);
                    //if (isset($temp[1])) {
                    $start = date('Y-m-d', $schedule['contract_signature_day']);
                    $start.='T' . date('H:i:s', $schedule['contract_signature_day']);
                    // } else {
                    //    $start = date('Y-m-d', strtotime($schedule['contract_signature_day']));
                    //}
                    $event['start'] = $start;
                    $event['end'] = $start;
                    $events[] = $event;
                }
                if ($schedule['contract_handover_day']) {
                    $event['id'] = $schedule['id'];
                    $event['title'] = "Handover day";
                    //$temp = explode(" ", $schedule['contract_handover_day']);
                    //if (isset($temp[1])) {
                    $start = date('Y-m-d', $schedule['contract_handover_day']);
                    $start.='T' . date('H:i:s', $schedule['contract_handover_day']);
                    // } else {
                    //    $start = date('Y-m-d', strtotime($schedule['contract_handover_day']));
                    // }
                    $event['start'] = $start;
                    $event['end'] = $start;
                    $events[] = $event;
                }
                if ($schedule['contract_payment_date_from']) {
                    $event['id'] = $schedule['id'];
                    $event['title'] = "Payment day";
                    // $temp = explode(" ", $schedule['contract_payment_date_from']);
                    // if (isset($temp[1])) {
                    // $start = date('Y-m-d', strtotime($schedule['contract_payment_date_from']));
                    // $start.='T' . date('H:i:s', strtotime($schedule['contract_payment_date_from']));
                    //  } else {
                    $start = date('Y-m-d', $schedule['contract_payment_date_from']);
                    // }
                    $event['start'] = $start;

//                    $temp = explode(" ", $schedule['contract_payment_date_to']);
//                    if (isset($temp[1])) {
//                        $end = date('Y-m-d', strtotime($schedule['contract_payment_date_to']));
//                        $end.='T' . date('H:i:s', strtotime($schedule['contract_payment_date_to']));
//                    } else {
//                        $end = date('Y-m-d', strtotime($schedule['contract_payment_date_to']));
//                    }
//                    $event['end'] = $end;
                    $event['end'] = $start;
                    $events[] = $event;
                }
                if ($schedule['contract_period_from']) {
                    $event['id'] = $schedule['id'];
                    $event['title'] = "Period time";
                    //$temp = explode(" ", $schedule['contract_period_from']);
                    //if (isset($temp[1])) {
                    //   $start = date('Y-m-d', strtotime($schedule['contract_period_from']));
                    //    $start.='T' . date('H:i:s', strtotime($schedule['contract_period_from']));
                    //} else {
                    $start = date('Y-m-d', $schedule['contract_period_from']);
                    // }
                    $event['start'] = $start;
                    //$temp = explode(" ", $schedule['contract_period_to']);
                    // if (isset($temp[1])) {
                    //   $end = date('Y-m-d', strtotime($schedule['contract_period_to']));
                    //    $end.='T' . date('H:i:s', strtotime($schedule['contract_period_to']));
                    //} else {
                    $end = date('Y-m-d', $schedule['contract_period_to']);
                    //  }
                    $event['end'] = $end;
                    $events[] = $event;
                }
            }

            //history
            $query = "select * from home_history_log where order_id='{$row['id']}' and user_id='{$user_id}'";
            $history = $database->database_query($query);
            while ($history_schedule = $database->database_fetch_assoc($history)) {
                if ($history_schedule['log_date_appointment_from']) {
                    $event['id'] = $history_schedule['id'];
                    $event['title'] = "Appointment day";
                    //$temp = explode(" ", $history_schedule['log_date_appointment_from']);
                    //if (isset($temp[1])) {
                    $start = date('Y-m-d', $history_schedule['log_date_appointment_from']);
                    $start.='T' . date('H:i:s', $history_schedule['log_date_appointment_from']);
                    // } else {
                    //   $start = date('Y-m-d', strtotime($history_schedule['log_date_appointment_from']));
                    // }
                    $event['start'] = $start;
//                    $temp = explode(" ", $history_schedule['log_date_appointment_to']);
//                    if (isset($temp[1])) {
//                        $end = date('Y-m-d', strtotime($history_schedule['log_date_appointment_to']));
//                        $end.='T' . date('H:i:s', strtotime($history_schedule['log_date_appointment_to']));
//                    } else {
//                        $end = date('Y-m-d', strtotime($history_schedule['log_date_appointment_to']));
//                    }
//                    $event['end'] = $end;
                    $event['end'] = $start;
                    $events[] = $event;
                }
            }
            //get client infomation
        }
        if (isset($birthday)) {
            $birthday = array_unique($birthday);

            for ($i = 0; $i < count($birthday); $i++) {
                $clients = $client->getClientId($birthday[$i]);
                $event['id'] = $clients['id'];
                $event['title'] = "Birthday's" . " " . $clients['client_name'];
                //$event['title'] = "Birthday";
                $event['start'] = $event['end'] = date('Y-m-d', strtotime($clients['client_birthday']));
                $events[] = $event;
            }
        }

        //get new event
        $query = "select * from home_event where user_id='{$user->user_info['id']}'";
        $result = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result)) {
            $event['id'] = $row['id'];
            $event['title'] = $row['event_title'];
            $event['start'] = $row['event_start'];
            $event['end'] = $row['event_end'];
            $event['url'] = $row['event_url'];
            $events[] = $event;
        }
        //var_dump($events);
        return json_encode($events);
    }

    function checkExistMessage($house_name, $client_name, $client_email, $date_sent) {
        global $database;
        $query = "select * from home_fetch_email where house_name='{$house_name}' and client_name='{$client_name}' and client_email='{$client_email}' and date_sent='{$date_sent}'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        if ($row > 0) {
            return true;
        } else {
            return false;
        }
    }

}
