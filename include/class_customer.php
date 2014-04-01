<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMECustomer{
    
    function getCustomers($search = "", $offset = 0, $length = 50){
        global $database;
        $client_arr=array();
        $query="select * from home_client";
        if (!empty($search))
            $query.=" where client_name like '%{$search}%'";

        $query.=" limit $offset,$length";
        $result=$database->database_query($query);
        while($row=$database->database_fetch_assoc($result)){
            $client['id']=$row['id'];
            $client['client_name']=$row['client_name'];
            $client['client_birthday']=$row['client_birthday'];
            $client['client_address']=$row['client_address'];
            $client['client_phone']=$row['client_phone'];
            $client_arr[]=$client;
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
}