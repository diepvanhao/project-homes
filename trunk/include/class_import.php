<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'class_broker.php';
include_once 'class_house.php';

class HOMEImport {

    public function import($arr = array()) {
        if (empty($arr)) {
            return;
        }
        $room = new HOMEHouse();
        foreach ($arr as $value) {
            try {
                $broker_id = $this->_getBrokerId(array('name' => (string) @$value[12], 'undertake' => (string) @$value[13], 'phone' => (string) @$value[14]));
                $house_id = $this->_getHouseId(array('name' => (string) @$value[5], 'address' => (string) @$value[4], 'type' => (string) @$value[3]));
                $room->create_room((string) @$value[6], '', '', (string) @$value[7], (string) @$value[8], (string) @$value[10], (string) @$value[9], (string) @$value[11], '', '', $house_id, $broker_id);
            } catch (Exception $ex) {
                continue;
            }
        }
    }

    public function _getBrokerId($arr = array()) {
        if (empty($arr) || empty($arr['name'])) {
            return false;
        }
        global $database, $user;

        $query = "SELECT * FROM home_broker_company WHERE  broker_company_name = '{$arr['name']}' LIMIT 1";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row)) {
            return $row['id'];
        }
        $database->database_query("
      INSERT INTO home_broker_company(user_id,broker_company_name,broker_company_phone, broker_company_undertake) 
      VALUES ('{$user->user_info['id']}','{$arr['name']}','{$arr['phone']}','{$arr['undertake']}')");
        return (int) $database->database_insert_id();
    }

    public function _getHouseId($arr = array()) {
        if (empty($arr) || empty($arr['name'])) {
            return false;
        }
        global $database, $user;

        $query = "SELECT * FROM home_house WHERE  house_name = '{$arr['name']}' AND house_address = '{$arr['address']}' LIMIT 1";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row)) {
            return (int) $row['id'];
        }

        $database->database_query("
      INSERT INTO home_house(user_id,house_name,house_address, house_type) 
      VALUES ('{$user->user_info['id']}','{$arr['name']}','{$arr['address']}','{$arr['type']}')");
        return (int) $database->database_insert_id();
    }

    

}
