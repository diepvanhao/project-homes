<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEHouse {

    function create($house_name, $house_address, $house_area, $house_build_time, $house_type, $house_description, $house_structure, $house_owner_name, $house_owner_address, $house_owner_phone, $house_owner_fax, $house_owner_email) {

        global $user, $database;

        $query = "insert into home_house(
            `user_id`,
            `house_name`,
            `house_address`,           
            `house_area`,            
            `house_build_time`,
            `house_type`,
            `house_description`,           
            `house_photo`,                       
            `house_structure`,
            `house_owner_id`
            ) values(
                '{$user->user_info['id']}',
                '{$house_name}',
                '{$house_address}',              
                '{$house_area}',               
                '{$house_build_time}',
                '{$house_type}',
                '{$house_description}',     
                    '',                
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
        $search = trim($search);
        $query = "select * from home_house";
        if (!empty($search))
            $query.=" where house_name like '%{$search}%' or house_address like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getTotalRoomItem($search) {
        global $database;
        $search = trim($search);
        $query = "select hr.broker_id,hr.house_id,hr.room_detail_id,hrd.*,hbc.broker_company_name,hh.house_name 
            from home_room as hr 
                left join home_room_detail as hrd on hr.room_detail_id=hrd.id
                left join home_house as hh on hr.house_id=hh.id
                left join home_broker_company as hbc on hr.broker_id=hbc.id
                ";
        if (!empty($search))
            $query.=" where hr.id like '%{$search}%' or hbc.broker_company_name like '%{$search}%' or hh.house_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getHouse($search = "", $offset = 0, $length = 50) {
        global $database;
        $search = trim($search);

        $query = "select * from home_house";
        if (!empty($search))
            $query.=" where house_name like '%{$search}%' or house_address like '%{$search}%'";

        $query.=" limit $offset,$length";
        //echo $query;
        $result = $database->database_query($query);
        $house_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
//            $house['id'] = $row['id'];
//            $house['user_id'] = $row['user_id'];
//            $house['house_name'] = $row['house_name'];
//            $house['house_address'] = $row['house_address'];
//            $house['house_area'] = $row['house_area'];
//            $house['house_build_time'] = $row['house_build_time'];
//            $house['house_type'] = $row['house_type'];
//            $house['house_description'] = $row['house_description'];
//            $house['house_photo'] = $row['house_photo'];
//            $house['house_structure'] = $row['house_structure'];
//            $house['house_owner_id'] = $row['house_owner_id'];
            $house_arr[] = $row;
        }
       
        return $house_arr;
    }

    function getRoom($search = "", $offset = 0, $length = 50) {
        global $database;
        $search = trim($search);
        $query = "select hr.id as room_id,hr.broker_id,hr.house_id,hr.room_detail_id,hrd.*,hbc.broker_company_name,hh.house_name 
            from home_room as hr 
                left join home_room_detail as hrd on hr.room_detail_id=hrd.id
                left join home_house as hh on hr.house_id=hh.id
                left join home_broker_company as hbc on hr.broker_id=hbc.id
                ";
        if (!empty($search))
            $query.=" where hr.id like '%{$search}%' or hbc.broker_company_name like '%{$search}%' or hh.house_name like '%{$search}%'";
        $query.=" ORDER BY hh.house_name ASC ";
        $query.=" limit $offset,$length";
        //echo $query;die();
        $result = $database->database_query($query);
        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
//            $room['id'] = $row['id'];
//            $room['room_number'] = $row['room_number'];
//            $room['room_type'] = $row['room_type'];
//            $room['room_size'] = $row['room_size'];
//            $room['room_status'] = $row['room_status'];
//            $room['room_rent'] = $row['room_rent'];
//            $room['room_key_money'] = $row['room_key_money'];
//            $room['room_administrative_expense'] = $row['room_administrative_expense'];
//            $room['room_deposit'] = $row['room_deposit'];
//            $room['room_photo'] = $row['room_photo'];
//            $room['room_detail_id'] = $row['room_detail_id'];
//            $room['broker_company_name'] = $row['broker_company_name'];
//            $room['house_name'] = $row['house_name'];
//            $room['broker_id'] = $row['broker_id'];
//            $room['house_id'] = $row['house_id'];
            $room_arr[] = $row;
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
            $room['room_type_number'] = $row['room_type_number'];
            $room['room_size'] = $row['room_size'];
            $room['room_status'] = $row['room_status'];
            $room['room_rent'] = $row['room_rent'];
            $room['room_key_money'] = $row['room_key_money'];
            $room['room_administrative_expense'] = $row['room_administrative_expense'];
            $room['room_deposit'] = $row['room_deposit'];
            $room['room_discount'] = $row['room_discount'];
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
            $house['house_area'] = $row['house_area'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_photo'] = $row['house_photo'];
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
            $house['house_area'] = $row['house_area'];
            $house['house_build_time'] = $row['house_build_time'];
            $house['house_type'] = $row['house_type'];
            $house['house_description'] = $row['house_description'];
            $house['house_photo'] = $row['house_photo'];
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

    function update_house($house_name, $house_address, $house_area, $house_build_time, $house_type, $house_description, $house_structure, $house_owner_name, $house_owner_address, $house_owner_phone, $house_owner_fax, $house_owner_email, $house_id, $owner_id) {

        global $database;
        $query = "update home_house set 
                house_name='{$house_name}',
                house_address='{$house_address}',                
                house_area='{$house_area}',              
                house_build_time='{$house_build_time}',
                house_type='{$house_type}',
                house_description='{$house_description}',                                             
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

    function create_room($room_number, $room_type, $room_type_number, $room_size, $room_status, $room_rent, $room_key_money, $room_administrative_expense, $room_deposit, $room_discount, $room_photo, $house_id, $broker_id) {
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
                        `room_type_number`,
                        `room_size`,
                        `room_status`,
                        `room_rent`,
                        `room_key_money`,
                        `room_administrative_expense`,
                        `room_deposit`,
                        `room_discount`,
                        `room_photo`                       
                    )values(
                        '{$room_number}',
                        '{$room_type}',
                        '{$room_type_number}',
                        '{$room_size}',
                        '{$room_status}',
                        '{$room_rent}',
                        '{$room_key_money}',
                        '{$room_administrative_expense}',
                        '{$room_deposit}',
                        '{$room_discount}',
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

    function update_room($room_number, $room_type,$room_type_number, $room_size, $room_status, $room_rent, $room_key_money, $room_administrative_expense, $room_deposit, $room_discount, $room_photo, $house_id, $broker_id, $room_detail_id, $house_id_bk, $broker_id_bk, $room_number_bk) {
        global $database;
        if (($room_number == $room_number_bk) && ($broker_id == $broker_id_bk) && ($house_id == $house_id_bk)) {
            $query = "update home_room_detail set 
                room_number='{$room_number}',
                room_type='{$room_type}',
                room_type_number='{$room_type_number}',    
                room_size='{$room_size}',                   
                room_status='{$room_status}',              
                room_rent='{$room_rent}',
                room_key_money='{$room_key_money}',
                room_administrative_expense='{$room_administrative_expense}',                             
                room_deposit='{$room_deposit}',
                room_discount='{$room_discount}',    
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
        } else {
            if (checkRoomExist($room_number, $broker_id, $house_id)) {
                return array('error' => 'Update fail!. This room is existed. Pls check room number, broker company and house.', 'flag' => false);
            } else {
                $query = "update home_room_detail set 
                room_number='{$room_number}',
                room_type='{$room_type}',
                room_type_number='{$room_type_number}', 
                room_size='{$room_size}',                   
                room_status='{$room_status}',              
                room_rent='{$room_rent}',
                room_key_money='{$room_key_money}',
                room_administrative_expense='{$room_administrative_expense}',                             
                room_deposit='{$room_deposit}',
                room_discount='{$room_discount}',    
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

    function create_source($source_name) {
        global $database;
        $source_name = trim($source_name);
        if (checkExistSource($source_name)) {
            return array('error' => "Source existed. Please enter other source !!!", 'flag' => '');
        }
        $query = "insert into home_source(`source_name`) values('{$source_name}')";

        $result = $database->database_query($query);
        if ($result)
            return array('error' => "", 'flag' => TRUE);
        else
            return array('error' => "", 'flag' => FALSE);
    }

    function getSource($search = "", $offset = 0, $length = 50) {
        global $database;
        $query = "select * from home_source";
        if (!empty($search))
            $query.=" where source_name like '%{$search}%'";
        $query.=" ORDER BY source_name ASC ";
        $query.=" limit $offset,$length";

        $result = $database->database_query($query);
        $source_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $source['id'] = $row['id'];
            $source['source_name'] = $row['source_name'];
            $source_arr[] = $source;
        }
        return $source_arr;
    }

    function getAllSource() {
        global $database;

        $query = "select * from home_source";

        //echo $query;
        $result = $database->database_query($query);
        $source_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $source['id'] = $row['id'];
            $source['source_name'] = $row['source_name'];
            $source_arr[] = $source;
        }
        return $source_arr;
    }

    function getTotalSourceItem($search) {
        global $database;

        $query = "select * from home_source";
        if (!empty($search))
            $query.=" where source_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getSourceById($source_id) {
        global $database;
        $query = "select * from home_source where id='{$source_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        $source['id'] = $row['id'];
        $source['source_name'] = $row['source_name'];
        return $source;
    }

    function update_source($source_id, $source_name) {
        global $database;
        $source_name = trim($source_name);
        if (checkExistSource($source_name)) {
            return array('error' => 'This source is existed', 'flag' => false);
        } else {
            $query = "update home_source set 
                source_name='{$source_name}'
               
         where id={$source_id}
        ";
            return array('error' => '', 'flag' => $database->database_query($query));
        }
    }

    function getHouseType() {
        global $database;
        $query = "select * from house_type order by type_name ASC";
        $result = $database->database_query($query);
        $houseTypes = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $house_type['id'] = $row['id'];
            $house_type['type_name'] = $row['type_name'];
            $houseTypes[] = $house_type;
        }
        return $houseTypes;
    }

    function getHouseTypeById($house_type_id) {
        global $database;
        $query = "select type_name from house_type where id='{$house_type_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['type_name'];
    }

    function getHouseStructures() {
        global $database;
        $query = "select * from house_structure order by structure_name ASC";
        $result = $database->database_query($query);
        $houseStructures = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $house_structure['id'] = $row['id'];
            $house_structure['structure_name'] = $row['structure_name'];
            $houseStructures[] = $house_structure;
        }
        return $houseStructures;
    }

    function getHouseStructureById($house_structure_id) {
        global $database;
        $query = "select structure_name from house_structure where id='{$house_structure_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['structure_name'];
    }

    function getRoomType() {
        global $database;
        $query = "select * from house_room_type ";
        $result = $database->database_query($query);
        $roomTypes = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room_type['id'] = $row['id'];
            $room_type['room_name'] = $row['room_name'];
            $roomTypes[] = $room_type;
        }
        return $roomTypes;
    }

    function getRoomTypeById($room_type_id) {
        global $database;
        $query = "select room_name from house_room_type where id='{$room_type_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['room_name'];
    }

//Address
    function create_city($city_name) {
        global $database;
        $city_name = trim($city_name);
        if (checkExistCity($city_name)) {
            return array('error' => "City existed. Please enter other city !!!", 'flag' => '');
        }
        $query = "insert into house_city(`city_name`) values('{$city_name}')";

        $result = $database->database_query($query);
        if ($result)
            return array('error' => "", 'flag' => TRUE);
        else
            return array('error' => "", 'flag' => FALSE);
    }

    function getAllCity() {
        global $database;

        $query = "select * from house_city";

        //echo $query;
        $result = $database->database_query($query);
        $city_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $city['id'] = $row['id'];
            $city['city_name'] = $row['city_name'];
            $city_arr[] = $city;
        }
        return $city_arr;
    }

    function create_district($city_id, $district_name) {
        global $database;
        $district_name = trim($district_name);
        if (checkExistDistrict($city_id, $district_name)) {
            return array('error' => "District existed. Please enter other district !!!", 'flag' => '');
        }
        $query = "insert into house_district(`district_name`,`city_id`) values('{$district_name}','{$city_id}')";

        $result = $database->database_query($query);
        if ($result)
            return array('error' => "", 'flag' => TRUE);
        else
            return array('error' => "", 'flag' => FALSE);
    }

    function getAllDistrict() {
        global $database;

        $query = "select * from house_district";

        //echo $query;
        $result = $database->database_query($query);
        $district_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $district['id'] = $row['id'];
            $district['district_name'] = $row['district_name'];
            $district_arr[] = $district;
        }
        return $district_arr;
    }

    function create_street($district_id, $street_name) {
        global $database;
        $street_name = trim($street_name);
        if (checkExistStreet($district_id, $street_name)) {
            return array('error' => "Street existed. Please enter other street !!!", 'flag' => '');
        }
        $query = "insert into house_street(`street_name`,`district_id`) values('{$street_name}','{$district_id}')";

        $result = $database->database_query($query);
        if ($result)
            return array('error' => "", 'flag' => TRUE);
        else
            return array('error' => "", 'flag' => FALSE);
    }

    function getAllStreet() {
        global $database;

        $query = "select * from house_street";

        //echo $query;
        $result = $database->database_query($query);
        $street_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $street['id'] = $row['id'];
            $street['street_name'] = $row['street_name'];
            $street_arr[] = $street;
        }
        return $street_arr;
    }

    function create_ward($street_id, $ward_name) {
        global $database;
        $ward_name = trim($ward_name);
        if (checkExistWard($street_id, $ward_name)) {
            return array('error' => "Ward existed. Please enter other ward !!!", 'flag' => '');
        }
        $query = "insert into house_ward(`ward_name`,`street_id`) values('{$ward_name}','{$street_id}')";

        $result = $database->database_query($query);
        if ($result)
            return array('error' => "", 'flag' => TRUE);
        else
            return array('error' => "", 'flag' => FALSE);
    }

//check serialize
    function isSerialized($s) {
        if (
                stristr($s, '{') != false &&
                stristr($s, '}') != false &&
                stristr($s, ';') != false &&
                stristr($s, ':') != false
        ) {
            return true;
        } else {
            return false;
        }
    }

    //parse address 
    function getNameCity($city_id) {
        global $database;
        $query = "select city_name from house_city where id='{$city_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['city_name'];
    }

    function getNameDistrict($district_id) {
        global $database;
        $query = "select district_name from house_district where id='{$district_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['district_name'];
    }

    function getNameStreet($street_id) {
        global $database;
        $query = "select street_name from house_street where id='{$street_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['street_name'];
    }

    function getNameWard($ward_id) {
        global $database;
        $query = "select ward_name from house_ward where id='{$ward_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        return $row['ward_name'];
    }

}

function checkExistWard($street_id, $ward_name) {
    global $database;

    $query = "select * from house_ward where ward_name='{$ward_name}' and street_id='{$street_id}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1)
        return true;
    else
        return false;
}

function checkExistStreet($district_id, $street_name) {
    global $database;

    $query = "select * from house_street where street_name='{$street_name}' and district_id='{$district_id}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1)
        return true;
    else
        return false;
}

function checkExistDistrict($city_id, $district_name) {
    global $database;

    $query = "select * from house_district where district_name='{$district_name}' and city_id='{$city_id}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1)
        return true;
    else
        return false;
}

function checkExistCity($city_name) {
    global $database;

    $query = "select * from house_city where city_name='{$city_name}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1)
        return true;
    else
        return false;
}

function checkExistSource($source_name) {
    global $database;

    $query = "select * from home_source where source_name='{$source_name}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1)
        return true;
    else
        return false;
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
