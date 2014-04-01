<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEHouse {

    function create($house_name, $house_address, $house_size, $house_area, $house_original_price, $house_build_time, $house_type, $house_description, $house_room_type, $house_administrative_expense, $house_discount, $house_structure, $house_owner_name, $house_owner_address, $house_owner_phone, $house_owner_fax, $house_owner_email) {

        global $user, $database;

        $query = "insert into home_house(
            `user_id`,
            `house_name`,
            `house_address`,
           `house_size`,
            `house_area`,
            `house_original_price`,
            `house_status`,
            `house_build_time`,
            `house_type`,
            `house_description`,
            `house_room_type`,
            `house_photo`,
            `house_administrative_expense`,
           `house_discount`,
            `house_structure`,
            `house_owner_id`

            ) values(
                '{$user->user_info['id']}',
                '{$house_name}',
                '{$house_address}',
                '{$house_size}',
                '{$house_area}',
                '{$house_original_price}',
                 '',
                '{$house_build_time}',
                '{$house_type}',
                '{$house_description}',
                '{$house_room_type}',
                    '',
                '{$house_administrative_expense}',
                '{$house_discount}',
                '{$house_structure}',
                    ''
                
                )";
        //echo $query;die();
        $result = $database->database_query($query);
        $house_id = $database->database_insert_id();
        if ($house_id) {
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
            $house['house_original_price'] = $row['house_original_price'];
            $house['house_status'] = $row['house_status'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_room_type'] = $row['house_room_type'];
            $house['house_photo'] = $row['house_photo'];
            $house['house_administrative_expense'] = $row['house_administrative_expense'];
            $house['house_discount'] = $row['house_discount'];
            $house['house_structure'] = $row['house_structure'];
            $house['house_owner_id'] = $row['house_owner_id'];
            $house_arr[] = $house;
        }
        return $house_arr;
    }
    
    function getHouses() {
        global $database;

        $query = "select * from home_house where broker_id=0";
       
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
            $house['house_original_price'] = $row['house_original_price'];
            $house['house_status'] = $row['house_status'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_room_type'] = $row['house_room_type'];
            $house['house_photo'] = $row['house_photo'];
            $house['house_administrative_expense'] = $row['house_administrative_expense'];
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
            $query = "select home_house.*,home_house_owner.id as owner_id,home_house_owner.house_owner_name,home_house_owner.house_owner_address,home_house_owner.house_owner_phone,home_house_owner.house_owner_fax,home_house_owner.house_owner_email,home_house_owner.house_owner_photo from home_house join home_house_owner on home_house_owner.id=home_house.house_owner_id where home_house.id={$id}";
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

}
