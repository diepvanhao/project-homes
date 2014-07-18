<?php

class HOMEDetail {

    /**
     * 
     * @global type $database
     * @param type $house_id
     * @return null
     */
    function getRooms($house_id = null) {

        if (empty($house_id)) {
            return null;
        }
        global $database;
        $query = "SELECT hr.broker_id,hr.house_id,hr.room_detail_id,hrd.*,hbc.broker_company_name,hh.house_name FROM home_room AS hr 
                LEFT JOIN home_room_detail AS hrd ON hr.room_detail_id=hrd.id
                LEFT JOIN home_house AS hh ON hr.house_id=hh.id
                LEFT JOIN home_broker_company AS hbc ON hr.broker_id=hbc.id
                WHERE hr.house_id = '{$house_id}'
                ";
        $result = $database->database_query($query);
        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room_arr[] = $row;
        }
        return $room_arr;
    }

    /**
     * 
     * @global type $database
     * @param type $id
     * @return null
     */
    public function getRoomDetail($id = null) {
        if (empty($id)) {
            return null;
        }
        global $database;

        $query = "SELECT * FROM home_room_detail AS d
                INNER JOIN home_room AS r ON r.room_detail_id = d.id
                LEFT JOIN house_room_type AS t ON d.room_type = t.id
                WHERE r.id = '{$id}'
                ";
        $result = $database->database_query($query);
        return $database->database_fetch_assoc($result);
    }

    /**
     * 
     * @global type $database
     * @param type $room_id
     * @param type $house_id
     * @return null
     */
    public function getBrokers($room_id = null, $house_id = null) {
        if (empty($room_id) || empty($house_id)) {
            return null;
        }
        global $database;

        $query = "SELECT * FROM home_broker_company AS c
                LEFT JOIN home_room AS r ON r.broker_id = c.id
                WHERE r.room_detail_id = '{$room_id}' AND r.house_id = '{$house_id}'
                ";
        $result = $database->database_query($query);
        $room_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $room_arr[] = $row;
        }
        return $room_arr;
    }
    /**
     * 
     * @global type $database
     * @param type $order_id
     * @return null
     */
    public function getOrder($order_id = NULL) {
        if (empty($order_id)) {
            return null;
        }
        global $database;

        $query = "SELECT * FROM home_order AS o
                WHERE o.id = '{$order_id}'
                ";
        $result = $database->database_query($query);
        return $database->database_fetch_assoc($result);
    }
/**
 * 
 * @global type $database
 * @param type $id
 * @return null
 */
    function getClient($id = null) {
        if ($id) {
            global $database;
            $query = "SELECT * FROM home_client WHERE id={$id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }
    /**
     * 
     * @global type $database
     * @param type $id
     * @return null
     */
    function getBroker($id = null) {
        if ($id) {
            global $database;
            $query = "SELECT * FROM home_broker_company WHERE id={$id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }
    
    function getHistory($id = null) {
        if ($id) {
            global $database;
            $query = "SELECT * FROM home_history_log WHERE order_id={$id} LIMIT 1";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }
}
