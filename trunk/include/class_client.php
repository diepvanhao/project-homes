<?php

class Client {

    function create($params = array()) {

        global $user, $database;

        $query = "INSERT INTO home_client(
            `user_id`,
            `client_name`,
            `client_birthday`,
            `client_address`,
            `client_phone`,
            `client_income`,
            `client_occupation`,
            `client_company`,
            `client_fax`,
            `client_gender`,
            `client_email`,
            `client_reason_change`,
            `client_time_change`,
            `client_photo`,
            `client_resident_name`,
            `client_resident_phone`,
            `client_rent`,
            `client_room_type`,
            `client_room_type_number`
            ) VALUES(
                '{$user->user_info['id']}',
                '{$params['client_name']}',
                '{$params['client_birthday']}',
                '{$params['client_address']}',
                '{$params['client_phone']}',
                '{$params['client_income']}',
                '{$params['client_occupation']}',
                '{$params['client_company']}',
                '{$params['client_fax']}',
                '{$params['client_gender']}',
                '{$params['client_email']}',
                '{$params['client_reason_change']}',
                '{$params['client_time_change']}',
                '',
                '{$params['client_resident_name']}',
                '{$params['client_resident_phone']}',
                '{$params['client_rent']}',
                '{$params['client_room_type']}',
                '{$params['client_room_type_number']}'
                )";

        return $database->database_query($query);
    }

    function getTotalItem($search) {
        global $database;

        $query = "SELECT count(*) FROM home_client";
        if (!empty($search)){
            $query.=" WHERE client_name LIKE '%{$search}%'";
        }
        $result = $database->database_query($query);
        $row = $database->database_fetch_array($result);
        return $row[0];
    }

    function getClients($search = "", $offset = 0, $length = 50) {
        global $database;


        $query = "SELECT * FROM home_client";
        if (!empty($search))
            $query.=" WHERE client_name LIKE '%{$search}%'";

        $query.=" LIMIT $offset,$length";

        $result = $database->database_query($query);
        $client_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $client_arr[] = $row;
        }
        return $client_arr;
    }
/*
    function getHouses() {
        global $database;

        $query = "SELECT * FROM home_client";

        $result = $database->database_query($query);
        $client_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $client_arr[] = $row;
        }
        return $client_arr;
    }
*/
    function getClientId($id = null) {
        if ($id) {
            global $database;
            $query = "SELECT * FROM home_client WHERE id={$id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }

    function update($id,$params) {

        global $user, $database;
        $query = "UPDATE home_client SET 
                user_id = '{$user->user_info['id']}',
                client_name = '{$params['client_name']}',
                client_birthday = '{$params['client_birthday']}',
                client_address = '{$params['client_address']}',
                client_phone = '{$params['client_phone']}',
                client_income = '{$params['client_income']}',
                client_occupation = '{$params['client_occupation']}',
                client_company = '{$params['client_company']}',
                client_fax = '{$params['client_fax']}',
                client_gender = '{$params['client_gender']}',
                client_email = '{$params['client_email']}',
                client_reason_change = '{$params['client_reason_change']}',
                client_time_change = '{$params['client_time_change']}',
                client_resident_name = '{$params['client_resident_name']}',
                client_resident_phone = '{$params['client_resident_phone']}',
                client_rent = '{$params['client_rent']}',
                client_room_type = '{$params['client_room_type']}',
                client_room_type_number = '{$params['client_room_type_number']}'    
                WHERE id={$id}
        ";

        return $database->database_query($query);
    }

    function delete($id) {
        global $database;
        $query = "DELETE FROM home_client WHERE id={$id}";
        return $database->database_query($query);
    }
}
