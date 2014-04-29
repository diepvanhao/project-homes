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
                '',
                '{$house_id}',
                '{$room_id}',    
                '',
                '{$order_rent_cost}',
                '{$order_day_create}',
                '1',
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
                //update room status
                $query = "update home_room_detail set room_status=1 where room_id='{$room_id}'";
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
            $order['house_name']=$row['house_name'];
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

}
