<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEAgent {

    function create($agent_name, $agent_email, $agent_address, $agent_phone, $agent_fax) {
        global $database;

        $database->database_query("

      INSERT INTO home_agent(

        agent_name,

        agent_email,

        agent_address,

        agent_phone,
        
        agent_fax                

      ) VALUES (
        
        '{$agent_name}',
           
        '{$agent_email}',
        
        '{$agent_address}',

        '{$agent_phone}',  
        
        '{$agent_fax}'        
      )

    ");
        $agent_id = $database->database_insert_id();

        if ($agent_id)
            $result = TRUE;
        else
            $result = FALSE;
        return $result;
    }

    function update($agent_id, $agent_name, $agent_email, $agent_address, $agent_phone, $agent_fax) {
        global $database;
        $query = "update home_agent set agent_name='{$agent_name}',agent_email='{$agent_email}',agent_address='{$agent_address}',agent_phone='{$agent_phone}',agent_fax='{$agent_fax}'
         where id={$agent_id}
        ";

        return $database->database_query($query);
    }

    function getTotalItem($search) {
        global $database;

        $query = "select * from home_agent";
        if (!empty($search))
            $query.=" where agent_name like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getAgent($search = "", $offset = 0, $length = 50) {
        global $database;
        $query = "select * from home_agent";
        if (!empty($search))
            $query.=" where agent_name like '%{$search}%'";

        $query.=" limit $offset,$length";
        //echo $query;
        $result = $database->database_query($query);
        $agent_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $agent['id'] = $row['id'];
            $agent['agent_name'] = $row['agent_name'];
            $agent['agent_email'] = $row['agent_email'];
            $agent['agent_address'] = $row['agent_address'];
            $agent['agent_phone'] = $row['agent_phone'];
            $agent['agent_fax'] = $row['agent_fax'];
            $agent_arr[] = $agent;
        }
        return $agent_arr;
    }

    function getAllAgent() {
        global $database;
        $query = "select * from home_agent";       
        //echo $query;
        $result = $database->database_query($query);
        $agent_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $agent['id'] = $row['id'];
            $agent['agent_name'] = $row['agent_name'];
            $agent['agent_email'] = $row['agent_email'];
            $agent['agent_address'] = $row['agent_address'];
            $agent['agent_phone'] = $row['agent_phone'];
            $agent['agent_fax'] = $row['agent_fax'];
            $agent_arr[] = $agent;
        }
        return $agent_arr;
    }

    function getAgentById($id = null) {
        if ($id) {
            global $database;
            $query = "select * from home_agent where id={$id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }

    function assign($agent_id = "", $staff_id = "") {
        global $database;
        if ($agent_id && $staff_id) {
            $query = "update home_user set agent_id={$agent_id} where id={$staff_id}";
            $result = $database->database_query($query);
            return $result;
        }
    }

}
