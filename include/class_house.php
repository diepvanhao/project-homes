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

    function getTotalRoomItem($search) {
        global $database;

        $query = "select * from home_room";
        if (!empty($search))
            $query.=" where id like '%{$search}%'";
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

    function getRoom($search = "", $offset = 0, $length = 50) {
        global $database;

        $query = "select hr.broker_id,hr.house_id,hr.room_detail_id,hrd.*,hbc.broker_company_name,hh.house_name from home_room as hr 
                left join home_room_detail as hrd on hr.room_detail_id=hrd.id
                left join home_house as hh on hr.house_id=hh.id
                left join home_broker_company as hbc on hr.broker_id=hbc.id
                ";
        if (!empty($search))
            $query.=" where hr.id like '%{$search}%'";
        $query.=" ORDER BY hh.house_name ASC ";
        $query.=" limit $offset,$length";
        //echo $query;die();
        $result = $database->database_query($query);
        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room['id'] = $row['id'];
            $room['room_number'] = $row['room_number'];
            $room['room_type'] = $row['room_type'];
            $room['room_size'] = $row['room_size'];
            $room['room_status'] = $row['room_status'];
            $room['room_rent'] = $row['room_rent'];
            $room['room_key_money'] = $row['room_key_money'];
            $room['room_administrative_expense'] = $row['room_administrative_expense'];
            $room['room_deposit'] = $row['room_deposit'];
            $room['room_photo'] = $row['room_photo'];
            $room['room_detail_id'] = $row['room_detail_id'];
            $room['broker_company_name'] = $row['broker_company_name'];
            $room['house_name'] = $row['house_name'];
            $room['broker_id'] = $row['broker_id'];
            $room['house_id'] = $row['house_id'];
            $room_arr[] = $room;
        }
        return $room_arr;
    }

    function getRoomById($room_detail_id, $broker_id, $house_id) {
        global $database;

        $query = "select hrd.* from home_room_detail as hrd 
                left join home_room as hr on hr.room_detail_id=hrd.id                                
                ";

        $query.=" where hr.broker_id ='{$broker_id}' and hr.house_id='{$house_id}' and room_detail_id='{$room_detail_id}'";

        $result = $database->database_query($query);
        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room['id'] = $row['id'];
            $room['room_number'] = $row['room_number'];
            $room['room_type'] = $row['room_type'];
            $room['room_size'] = $row['room_size'];
            $room['room_status'] = $row['room_status'];
            $room['room_rent'] = $row['room_rent'];
            $room['room_key_money'] = $row['room_key_money'];
            $room['room_administrative_expense'] = $row['room_administrative_expense'];
            $room['room_deposit'] = $row['room_deposit'];
            $room['room_photo'] = $row['room_photo'];

            $room_arr = $room;
        }
        return $room_arr;
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
            $query = "select hh.*,hho.id as house_owner_id,hho.house_owner_name,hho.house_owner_address,hho.house_owner_phone,hho.house_owner_fax,hho.house_owner_email,hho.house_owner_photo"
                    . " from home_house as hh left join home_house_owner as hho on hh.house_owner_id=hho.id  where hh.id={$id}";
            $result = $database->database_query($query);

            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }

    function update_house($house_name, $house_address, $house_size, $house_area, $house_build_time, $house_type, $house_description, $house_discount, $house_structure, $house_owner_name, $house_owner_address, $house_owner_phone, $house_owner_fax, $house_owner_email, $house_id, $owner_id) {

        global $database;
        $query = "update home_house set 
                house_name='{$house_name}',
                house_address='{$house_address}',
                house_size='{$house_size}',
                house_area='{$house_area}',              
                house_build_time='{$house_build_time}',
                house_type='{$house_type}',
                house_description='{$house_description}',                             
                house_discount='{$house_discount}',
                house_structure='{$house_structure}'
                
         where id={$house_id}
        ";

        $database->database_query($query);
        //update owner
        if ($owner_id) {
            $query = "update home_house_owner set 
                house_owner_name='{$house_owner_name}',
                house_owner_address='{$house_owner_address}',
                house_owner_phone='{$house_owner_phone}',
                house_owner_fax='{$house_owner_fax}',
                house_owner_email='{$house_owner_email}'               
                             
         where id={$owner_id}
        ";

            return $database->database_query($query);
        } elseif ($house_owner_name) {
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
        }
        return true;
    }

    function create_room($room_number, $room_type, $room_size, $room_status, $room_rent, $room_key_money, $room_administrative_expense, $room_deposit, $room_photo, $house_id, $broker_id) {
        global $database;
        //check exist room
        if (checkRoomExist($room_number, $broker_id, $house_id)) {
            return array('error' => 'This room is existed', 'flag' => false);
        } else {
            //check room_detail exist
            $room_detail_id = getRoomDetailId($room_number, $house_id);
            //insert new 
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
                if ($room_detail_id) {
                    //update room_detail_id for home_room
                    $query = "update home_room set room_detail_id='{$room_detail_id}' where id='{$room_number}' and broker_id='{$broker_id}' and house_id='{$house_id}'";
                    return array('error' => '', 'flag' => $database->database_query($query));
                } else {
                    $query = "insert into home_room_detail(                                   
                        `room_number`,
                        `room_type`,
                        `room_size`,
                        `room_status`,
                        `room_rent`,
                        `room_key_money`,
                        `room_administrative_expense`,
                        `room_deposit`,
                        `room_photo`                       
                    )values(
                        '{$room_number}',
                        '{$room_type}',
                        '{$room_size}',
                        '{$room_status}',
                        '{$room_rent}',
                        '{$room_key_money}',
                        '{$room_administrative_expense}',
                        '{$room_deposit}',
                        '{$room_photo}'                       
                    )";
                    $result = $database->database_query($query);
                    $id = $database->database_insert_id();
                    //update room_detail_id for home_room
                    $query = "update home_room set room_detail_id='{$id}' where id='{$room_number}' and broker_id='{$broker_id}' and house_id='{$house_id}'";
                    return array('error' => '', 'flag' => $database->database_query($query));
                }
                //return $database->database_query($query);
            } else {
                return array('error' => '', 'flag' => FALSE);
            }
        }
    }

    function update_room($room_number, $room_type, $room_size, $room_status, $room_rent, $room_key_money, $room_administrative_expense, $room_deposit, $room_photo, $house_id, $broker_id, $room_detail_id, $house_id_bk, $broker_id_bk) {
        global $database;
        if (checkRoomExist($room_number, $broker_id, $house_id)) {
            return array('error' => 'This room is existed', 'flag' => false);
        } else {
            $query = "update home_room_detail set 
                room_number='{$room_number}',
                room_type='{$room_type}',
                room_size='{$room_size}',
                room_status='{$room_status}',              
                room_rent='{$room_rent}',
                room_key_money='{$room_key_money}',
                room_administrative_expense='{$room_administrative_expense}',                             
                room_deposit='{$room_deposit}',
                room_photo='{$room_photo}'
                
         where id={$room_detail_id}
        ";
            $database->database_query($query);
            //update home_room
            $query = "update home_room set
                    id='{$room_number}',
                    broker_id='{$broker_id}',
                    house_id='{$house_id}'
                     
                    where broker_id='{$broker_id_bk}' and house_id='{$house_id_bk}' and room_detail_id='{$room_detail_id}'
         ";                    
            return array('error' => '', 'flag' => $database->database_query($query));
        }
    }

}

function getRoomDetailId($room_number, $house_id) {
    global $database;
    $query = "select room_detail_id from home_room where id='{$room_number}' and house_id='{$house_id}' limit 1";

    $result = $database->database_query($query);
    $row = $database->database_fetch_assoc($result);
    return $row['room_detail_id'];
}

function checkRoomExist($room_number, $broker_id, $house_id) {
    global $database;
    $query = "select * from home_room where id='{$room_number}' and broker_id='{$broker_id}' and house_id='{$house_id}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1)
        return true;
    else
        return false;
}
