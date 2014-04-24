<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEOrder {

    function create_order($room_id, $order_name, $order_rent_cost, $order_comment, $create_id, $house_id, $broker_id, $order_day_create) {
        global $database;
        //check house empty
        $checkExist = $this->checkHouseEmpty($house_id,$room_id);
        if ($checkExist)
            return array('error' => $checkExist);
        else {

            $query = "insert into home_order(
                order_name,
                user_id,
                house_id,
                room_id,
                client_id,
                order_rent_cost,
                order_day_create,
                order_status,
                order_comment,
                order_day_update,
                create_id,
                broker_id)
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
                '{$broker_id}'
                )";

            $result = $database->database_query($query);
            $id = $database->database_insert_id();
            return array('id' => $id);
        }
    }

    function checkHouseEmpty($house_id,$room_id) {
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

}
