<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMECustomer {

    function getCustomers($search = "", $offset = 0, $length = 50) {
        global $database;
        $client_arr = array();
        $query = "select * from home_client";
        if (!empty($search))
            $query.=" where client_name like '%{$search}%'";

        $query.=" limit $offset,$length";
        $result = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result)) {
            $client['id'] = $row['id'];
            $client['client_name'] = $row['client_name'];
            $client['client_birthday'] = $row['client_birthday'];
            $client['client_address'] = $row['client_address'];
            $client['client_phone'] = $row['client_phone'];
            $client_arr[] = $client;
        }
        return $client_arr;
    }

    function getTotalItem($search) {
        global $database;

        $query = "select * from home_client";
        if (!empty($search))
            $query.=" where client_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function create_customer($client_name, $client_birthday, $client_email, $client_phone, $order_id, $client_id) {
        global $database, $user;
        $exits = FALSE;
        if ($client_id) {
            $exits = true;
        } elseif (checkExistClient($client_phone, $client_name)) {
            $exits = true;
        }
        if (!$exits) {
            $query = "insert into home_client(
                        user_id,
                        client_name,
                        client_birthday,
                        client_email,
                        client_phone                                                                                  
                     )values(
                     $user->user_info['id'],
                     $client_name,
                     $client_birthday,
                     $client_email,
                     $client_phone
                    )";
            $result = $database->database_query($query);
            $id = $database->database_insert_id($result);
            //update order
            $query = "update home_order set client_id={$id}";
            $database->database_query($query);
            return array('exist' => $exits, 'id' => $id);
        } else {
            if ($client_id) {
                //update order
                $query = "update home_order set client_id={$client_id}";
                $database->database_query($query);
                return array('exist' => $exits, 'id' => $client_id);
            } else {
                $query = "select id from home_client where client_phone='{$client_phone}' or client_name='{$client_name}'";
                $result = $database->database_query($query);
                $row = $database->database_fetch_assoc($result);
                $id = $row['id'];
                //update order
                $query = "update home_order set client_id={$client_id}";
                $database->database_query($query);
                return array('exist' => $exits, 'id' => $id);
            }
        }
    }

    function checkExistClient($client_phone, $client_name) {
        global $database;
        $query = "select * from home_client where client_phone='{$client_phone}' or client_name='{$client_name}'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        if ($row >= 1)
            return true;
        else
            return false;
    }

}
