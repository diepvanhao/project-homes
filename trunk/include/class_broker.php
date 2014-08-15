<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEBroker {

    function create($broker_company_name, $broker_company_address, $broker_company_phone, $broker_company_email, $broker_company_fax, $broker_company_undertake,$house_search="") {
        global $database, $user;
        $database->database_query("

      INSERT INTO home_broker_company(
      
        user_id,

        broker_company_name,

        broker_company_address,

        broker_company_phone,

        broker_company_email,
        
        broker_company_fax,
        
        broker_company_undertake,
        
        broker_company_search

      ) VALUES (
      
       '{$user->user_info['id']}',
        
        '{$broker_company_name}',
           
        '{$broker_company_address}',
        
        '{$broker_company_phone}',

        '{$broker_company_email}',  
        
        '{$broker_company_fax}',
            
        '{$broker_company_undertake}',
         
        '{$house_search}'
      )

    ");
        $broker_id = $database->database_insert_id();

        if ($broker_id)
            $result = TRUE;
        else
            $result = FALSE;

        return $result;
    }

    function getTotalItem($search) {
        global $database;
        $search=trim($search);
        $query = "select * from home_broker_company";
        if (!empty($search))
            $query.=" where broker_company_name like '%{$search}%' or broker_company_phone like '%{$search}%' or broker_company_search like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getBroker($search = "", $offset = 0, $length = 50) {
        global $database;
        $search=trim($search);
        $query = "select * from home_broker_company";
        if (!empty($search))
            $query.=" where broker_company_name like '%{$search}%' or broker_company_phone like '%{$search}%' or broker_company_search like '%{$search}%'";

        $query.=" limit $offset,$length";
        //echo $query;
        $result = $database->database_query($query);
        $broker_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $broker['id'] = $row['id'];
            $broker['broker_company_name'] = $row['broker_company_name'];
            $broker['broker_company_address'] = $row['broker_company_address'];
            $broker['broker_company_phone'] = $row['broker_company_phone'];
            $broker['broker_company_email'] = $row['broker_company_email'];
            $broker['broker_company_fax'] = $row['broker_company_fax'];
            $broker['broker_company_undertake'] = $row['broker_company_undertake'];
            $broker['broker_company_lock'] = $row['broker_company_lock'];
            $broker_arr[] = $broker;
        }
        return $broker_arr;
    }

    function getAllBroker() {
        global $database;

        $query = "select * from home_broker_company order by broker_company_name ASC";

        $result = $database->database_query($query);
        $broker_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $broker['id'] = $row['id'];
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

    function getBrokerById($id = null) {
        if ($id) {
            global $database;
            $query = "select * from home_broker_company where id={$id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }

    function update($broker_id, $broker_company_name, $broker_company_address, $broker_company_phone, $broker_company_email, $broker_company_fax, $broker_company_undertake,$house_search="") {
        global $database;
        $query = "update home_broker_company set broker_company_name='{$broker_company_name}',broker_company_address='{$broker_company_address}',broker_company_phone='{$broker_company_phone}',broker_company_email='{$broker_company_email}',broker_company_fax='{$broker_company_fax}',broker_company_undertake='{$broker_company_undertake}',broker_company_search='{$house_search}'
         where id={$broker_id}
        ";

        return $database->database_query($query);
    }

    function assign($broker_id = "", $house_id = "", $room_id = "") {
        global $database;
        if ($broker_id && $house_id && $room_id) {
            //check room is added
            $houseClass = new HOMEHouse();
            if (checkRoomExist($room_id, $broker_id, $house_id)) {
                return false;
            }
            //check room_detail exist
            $room_detail_id = getRoomDetailId($room_id, $house_id);
            $query = "insert into  home_room(
                    `id`,
                    `broker_id`,
                    `house_id`,
                    `room_detail_id`)
                values('{$room_id}','{$broker_id}','{$house_id}','{$room_detail_id}')";
            $result = $database->database_query($query);
            return $result;
        }
    }

}
