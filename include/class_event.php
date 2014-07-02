<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEEvent {

    function create_event($title, $start, $end, $url) {
        global $database, $user;
        $checkExist = checkEventExist($title, $start, $end);
        if ($checkExist) {
            return false;
        } else {            
            $query = "insert into home_event(user_id,event_title,event_start,event_end,event_url) values('{$user->user_info['id']}','$title','$start','$end','$url')";
            return $database->database_query($query);
        }
    }

    function getEvents($signature_day, $handover_day, $payment_day, $appointment_day, $other, $period, $birthday, $all_agent, $agent_id, $assign_id, $position, $date_from, $date_to) {
        global $database,$user;
        
        
    }

}

function checkEventExist($title, $start, $end) {
    global $database;
    $query = "select * from home_event where event_title='{$title}' and event_start='{$start}' and event_end='{$end}'";
    $result = $database->database_query($query);
    $row = $database->database_num_rows($result);
    if ($row >= 1) {
        return true;
    } else {
        return FALSE;
    }
}
