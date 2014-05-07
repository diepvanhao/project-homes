<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEHouse {

    function create($house_name, $house_address, $house_size, $house_area, $house_build_time, $house_type, $house_description, $house_discount, $house_structure, $house_owner_name, $house_owner_address, $house_owner_phone, $house_owner_fax, $house_owner_email) {

        global $user, $database;

        $query = "insert into home_house(
            `user_id`,
            `house_name`,
            `house_address`,
            `house_size`,
            `house_area`,            
            `house_build_time`,
            `house_type`,
            `house_description`,           
            `house_photo`,            
            `house_discount`,
            `house_structure`,
            `house_owner_id`
            ) values(
                '{$user->user_info['id']}',
                '{$house_name}',
                '{$house_address}',
                '{$house_size}',
                '{$house_area}',               
                '{$house_build_time}',
                '{$house_type}',
                '{$house_description}',     
                    '',
                '{$house_discount}',
                '{$house_structure}',
                    ''                
                )";
        //echo $query;die();
        $result = $database->database_query($query);
        $house_id = $database->database_insert_id();
        if ($house_id) {
            if ($house_owner_name) {
                $query = "insert into home_house_owner(                            
                        `house_owner_name`,
                        `house_owner_address`,
                        `house_owner_phone`,
                        `house_owner_fax`,
                        `house_owner_email`,
                        `house_owner_photo`
                    )values(
                        '{$house_owner_name}',
                        '{$house_owner_address}',
                        '{$house_owner_phone}',
                        '{$house_owner_fax}',
                        '{$house_owner_email}',
                         ''                        
                    )";
                //echo $query;die();
                $result = $database->database_query($query);
                $id = $database->database_insert_id();

                if ($id) {
                    //update house
                    $query = "update home_house set house_owner_id={$id} where id={$house_id} ";
                    return $database->database_query($query);
                }
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    function getTotalItem($search) {
        global $database;

        $query = "select * from home_house";
        if (!empty($search))
            $query.=" where house_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getHouse($search = "", $offset = 0, $length = 50) {
        global $database;


        $query = "select * from home_house";
        if (!empty($search))
            $query.=" where house_name like '%{$search}%'";

        $query.=" limit $offset,$length";
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

    function getHouses() {
        global $database;

        $query = "select * from home_house ";

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

    function getAllHouses() {
        global $database;

        $query = "select * from home_house";

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

    function getHouseById($id = null) {
        if ($id) {
            global $database;
            $query = "select home_house.* from home_house  where home_house.id={$id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }

    function update($house_name, $house_address, $house_size, $house_area, $house_original_price, $house_build_time, $house_type, $house_description, $house_room_type, $house_administrative_expense, $house_discount, $house_structure, $house_owner_name, $house_owner_address, $house_owner_phone, $house_owner_fax, $house_owner_email, $house_id, $owner_id) {

        global $database;
        $query = "update home_house set 
                house_name='{$house_name}',
                house_address='{$house_address}',
                house_size='{$house_size}',
                house_area='{$house_area}',
                house_original_price='{$house_original_price}',
                house_build_time='{$house_build_time}',
                house_type='{$house_type}',
                house_description='{$house_description}',
                house_room_type='{$house_room_type}',
                house_administrative_expense='{$house_administrative_expense}',  
                house_discount='{$house_discount}',
                house_structure='{$house_structure}'
                

         where id={$house_id}
        ";

        $database->database_query($query);
        //update owner
        $query = "update home_house_owner set 
                house_owner_name='{$house_owner_name}',
                house_owner_address='{$house_owner_address}',
                house_owner_phone='{$house_owner_phone}',
                house_owner_fax='{$house_owner_fax}',
                house_owner_email='{$house_owner_email}'               
                             
         where id={$owner_id}
        ";
        return $database->database_query($query);
    }

    function create_room($room_number, $room_type, $room_size, $room_status, $room_rent, $room_key_money, $room_administrative_expense, $room_deposit, $room_photo, $house_id, $broker_id) {
        global $database;
        //check exist room
        if (checkRoomExist($room_number, $broker_id, $house_id)) {
            return array('error' => 'This room is existed', 'flag' => false);
        } else {
            $room_number = trim($room_number);
            $query = "insert into home_room(            
                        `id`,
                        `broker_id`,
                        `house_id`                        
                    )values(
                        '{$room_number}',
                        '{$broker_id}',
                        '{$house_id}'                        
                    )";

            $result = $database->database_query($query);
            if ($result) {
                $query = "insert into home_room_detail(                                   
                        `room_number`,
                        `room_type`,
                        `room_size`,
                        `room_status`,
                        `room_rent`,
                        `room_key_money`,
                        `room_administative_expense`,
                        `room_deposit`,
                        `room_photo`,
                        `room_id`
                    )values(
                        '{$room_number}',
                        '{$room_type}',
                        '{$room_size}',
                        '{$room_status}',
                        '{$room_rent}',
                        '{$room_key_money}',
                        '{$room_administrative_expense}',
                        '{$room_deposit}',
                        '{$room_photo}',
                        '{$room_number}'
                    )";
                return array('error' => '', 'flag' => $database->database_query($query));
                //return $database->database_query($query);
            } else {
                return array('error' => '', 'flag' => FALSE);
            }
        }
    }

}
function checkRoomExist($room_number, $broker_id, $house_id){
    global $database;
    $query = "select * from home_room where id='{$room_number}' and broker_id='{$broker_id}' and house_id='{$house_id}'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        if ($row >= 1)
            return true;
        else
            return false;
}
