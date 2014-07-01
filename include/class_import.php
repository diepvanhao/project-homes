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
        $houseType = $this->_getHouseTypeAssoc();
        foreach ($arr as $value) {
            try {
                $roomStatus = 0;
                $tmp = mb_convert_encoding((string) @$value[7], "UTF-8", "Shift-JIS");
                if ($tmp == mb_convert_encoding('居住中', "UTF-8", "Shift-JIS")) {
                    $roomStatus = 1;
                } elseif ($tmp == mb_convert_encoding('未完成', "UTF-8", "Shift-JIS")) {
                    $roomStatus = 2;
                }
                $broker_id = $this->_getBrokerId(array('name' => (string) @$value[12], 'undertake' => (string) @$value[13], 'phone' => (string) @$value[14]));
                $house_id = $this->_getHouseId(array('name' => (string) @$value[5], 'address' => (string) @$value[4], 'type' => (string) @$houseType[mb_convert_encoding((string) @$value[3], "UTF-8", "Shift-JIS")]));
                $room->create_room((string) @$value[6], '', '', (int) $roomStatus, (string) @$value[8], (string) @$value[10], (string) @$value[9], (string) @$value[11], '', '', $house_id, $broker_id);
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
//address
        $addr = explode(',', $arr['address']);
        $address = '';
        
        if(is_array($addr)){
            $city_id = (int) $this->_checkExistCity(@$addr[0]);
            $district_id = (int) $this->_checkExistDistrict($city_id, @$addr[1]);
            $street_id = (int) $this->_checkExistStreet($district_id, @$addr[2]);
            $ward_id = (int) $this->_checkExistWard($street_id, @$addr[3]);
            $address = serialize(array(
                'city_id' => $city_id,
                'district_id' => $district_id,
                'street_id' => $street_id,
                'ward_id' => $ward_id,
                'house_address' => @$addr[4],
            ));
        }
        $database->database_query("
      INSERT INTO home_house(user_id,house_name,house_address, house_type) 
      VALUES ('{$user->user_info['id']}','{$arr['name']}','{$address}','{$arr['type']}')");
        return (int) $database->database_insert_id();
    }

    private function _getHouseTypeAssoc() {
        global $database;

        $query = "SELECT * FROM house_type ";
        $result = $database->database_query($query);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[mb_convert_encoding($row['type_name'], "UTF-8", "Shift-JIS")] = $row['id'];
        }
        return $arr;
    }

    public function importAddress($arr = array()) {
        if (empty($arr)) {
            return;
        }
        foreach ($arr as $key => $value) {
            try {
                $street_name = '';
                $ward_name = '';
                if(count($value) == 4){
                    $value[2] = $value[3];
                    $value[1] = $value[2];
                    $value[0] = $value[1];
                    unset($value[3]);
                }
                if (@strpos($value[1], ",") !== false) {
                    $value[2] = $value[1];
                    $value[1] = $value[0];
                    $value[0] = @$arr[$key - 1][2];
                }
                if (@strpos($value[2], ",") !== false) {
                    $tmp = explode(",", $value[2]);
                    if(count($tmp)){
                        $street_name = $tmp[0];
                        $ward_name = $tmp[1];
                    }
                }
                $city = $this->_create_city($value[0]);
                $district = $this->_create_district($city, $value[1]);
                if(!empty($street_name)){
                    $street = $this->_create_street($district, $street_name);
                }
                if(!empty($ward_name)){
                    $this->_create_ward($street, $ward_name);
                }
            } catch (Exception $e) {
                echo $key . implode('===', $value);
                continue;
            }
        }
    }

    private function _create_city($city_name) {

        global $database;

        $city_name = trim($city_name);

        $id = $this->_checkExistCity($city_name);
        if (!empty($id)) {
            return $id;
        }

        $query = "INSERT INTO house_city(`city_name`) VALUES ('{$city_name}')";
        $result = $database->database_query($query);
        if ($result) {
            return (int) $database->database_insert_id();
        } else {
            return false;
        }
    }

    private function _checkExistCity($city_name) {

        global $database;
        
        $city_name = trim($city_name);
        
        $query = "SELECT * FROM house_city WHERE city_name='{$city_name}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row)) {
            return (int) $row['id'];
        }
        return false;
    }

    private function _create_district($city_id, $district_name) {

        global $database;

        $district_name = trim($district_name);

        $id = $this->_checkExistDistrict($city_id, $district_name);
        if (!empty($id)) {
            return $id;
        }
        $query = "INSERT INTO house_district(`district_name`,`city_id`) VALUES('{$district_name}','{$city_id}')";

        $result = $database->database_query($query);
        if ($result) {
            return (int) $database->database_insert_id();
        } else {
            return false;
        }
    }

    private function _checkExistDistrict($city_id, $district_name) {

        global $database;

        $district_name = trim($district_name);
        
        $query = "SELECT * FROM house_district WHERE district_name='{$district_name}' AND city_id='{$city_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row)) {
            return (int) $row['id'];
        }
        return false;
    }

    private function _create_street($district_id, $street_name) {

        global $database;

        $street_name = trim($street_name);

        $id = $this->_checkExistStreet($district_id, $street_name);
        if (!empty($id)) {
            return $id;
        }

        $query = "INSERT INTO house_street(`street_name`,`district_id`) VALUES('{$street_name}','{$district_id}')";
        $result = $database->database_query($query);
        if ($result) {
            return (int) $database->database_insert_id();
        } else {
            return false;
        }
    }

    private function _checkExistStreet($district_id, $street_name) {

        global $database;

        $street_name = trim($street_name);
        
        $query = "SELECT * FROM house_street WHERE street_name='{$street_name}' AND district_id='{$district_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row)) {
            return (int) $row['id'];
        }
        return false;
    }

    private function _create_ward($street_id, $ward_name) {

        global $database;

        $ward_name = trim($ward_name);

        $id = $this->_checkExistWard($street_id, $ward_name);
        if (!empty($id)) {
            return $id;
        }

        $query = "INSERT INTO house_ward(`ward_name`,`street_id`) VALUES('{$ward_name}','{$street_id}')";
        $result = $database->database_query($query);
        if ($result) {
            return (int) $database->database_insert_id();
        } else {
            return false;
        }
    }

    private function _checkExistWard($street_id, $ward_name) {

        global $database;

        $ward_name = trim($ward_name);
        
        $query = "SELECT * FROM house_ward WHERE ward_name='{$ward_name}' AND street_id='{$street_id}'";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row)) {
            return (int) $row['id'];
        }
        return false;
    }

}
