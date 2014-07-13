<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'class_client.php';

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
        global $database, $user;
        $agent = new HOMEAgent();
        $staff = new HOMEUser();
        $client = new Client();
        $events = Array();
        //get order list
        $query = "select ho.* from home_order as ho where "
                . "ho.order_status=1 ";
        if ($date_from)
            $query.=" and ho.order_day_update >='{$date_from}'";
        if ($date_to)
            $query.=" and ho.order_day_update <='{$date_to}'";
        $query.=" order by ho.order_day_update ASC";
//echo $query;die();
        $result_order = $database->database_query($query);
        while ($row = $database->database_fetch_assoc($result_order)) {
            //get transaction info
            //get contract info             
            $query = "select hcd.* from home_contract hc left join home_contract_detail hcd on hc.id=hcd.contract_id "
                    . "where hc.order_id='{$row['id']}'"
                    . " and hc.user_id='{$row['user_id']}'";

            $result_contract = $database->database_query($query);
            while ($contract = $database->database_fetch_assoc($result_contract)) {
                if (trim($contract['contract_signature_day'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Signature Day";
                    $start = explode(" ", $contract['contract_signature_day']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];
                    $event['end'] = "";
                    //$event['time']="";
                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
                if (trim($contract['contract_handover_day'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Handover Day";
                    $start = explode(" ", $contract['contract_handover_day']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];
                    $event['end'] = "";

                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
                if (trim($contract['contract_payment_date_from'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Payment Day";
                    $start = explode(" ", $contract['contract_payment_date_from']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];

                    $end = explode(" ", $contract['contract_payment_date_to']);
                    if (isset($end[0]))
                        $event['end'] = $end[0];
                    else
                        $event['end'] = "";
                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
                if (trim($contract['contract_period_from'])) {
                    $event['id'] = $row['id'];
                    $event['title'] = "Period To";
                    $start = explode(" ", $contract['contract_period_from']);
                    if (isset($start[1]))
                        $event['time'] = $start[1];
                    else
                        $event['time'] = "";
                    $event['start'] = $start[0];

                    $end = explode(" ", $contract['contract_period_to']);
                    if (isset($end[0]))
                        $event['end'] = $end[0];
                    else
                        $event['end'] = "";
                    //fetch agent, user info.      
                    $agent_info = $agent->getAgentByUserId($row['user_id']);
                    if ($agent_info)
                        $event['agent'] = $agent_info['agent_name'];
                    $staff_info = $staff->getAccountById($row['user_id']);
                    if ($staff_info) {
                        $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                        if ($staff_info['user_authorities'] == 2)
                            $event['position'] = 'Super manager';
                        elseif ($staff_info['user_authorities'] == 3)
                            $event['position'] = "Manager";
                        elseif ($staff_info['user_authorities'] == 4)
                            $event['position'] = "Staff";
                    }
                    //fetch client info
                    $client_info = $client->getClientId($row['client_id']);
                    if ($client_info)
                        $event['customer'] = $client_info['client_name'];
                    $event['link'] = 'google.com.vn';
                    $events[] = $event;
                }
            }
            //get birthday client
            if ($row['client_id']) {
                $event['id'] = $row['id'];
                $event['title'] = "Birthday";

                //$event['end']=$contract['contract_period_to'];
                //fetch agent, user info.      
                $agent_info = $agent->getAgentByUserId($row['user_id']);
                if ($agent_info)
                    $event['agent'] = $agent_info['agent_name'];
                $staff_info = $staff->getAccountById($row['user_id']);
                if ($staff_info) {
                    $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                    if ($staff_info['user_authorities'] == 2)
                        $event['position'] = 'Super manager';
                    elseif ($staff_info['user_authorities'] == 3)
                        $event['position'] = "Manager";
                    elseif ($staff_info['user_authorities'] == 4)
                        $event['position'] = "Staff";
                }
                //fetch client info
                $client_info = $client->getClientId($row['client_id']);
                if ($client_info) {
                    $event['customer'] = $client_info['client_name'];
                    $event['start'] = $client_info['client_birthday'];
                    $event['end'] = "";
                    $event['time'] = "";
                }
                $event['link'] = 'google.com.vn';
                $events[] = $event;
            }
            //get history info
            $query = "select * from home_history_log where order_id='{$row['id']}' and user_id='{$row['user_id']}'";
            $result_history = $database->database_query($query);
            $history = $database->database_fetch_assoc($result_history);
            if (trim($history['log_date_appointment_from'])) {
                $event['id'] = $row['id'];
                $event['title'] = "Appointment day";

                $start = explode(" ", $history['log_date_appointment_from']);
                if (isset($start[1]))
                    $event['time'] = $start[1];
                else
                    $event['time'] = "";
                $event['start'] = $start[0];

                $end = explode(" ", $history['log_date_appointment_to']);
                if (isset($end[0]))
                    $event['end'] = $end[0];
                else
                    $event['end'] = "";
                //fetch agent, user info.      
                $agent_info = $agent->getAgentByUserId($row['user_id']);
                if ($agent_info)
                    $event['agent'] = $agent_info['agent_name'];
                $staff_info = $staff->getAccountById($row['user_id']);
                if ($staff_info) {
                    $event['assigned'] = $staff_info['user_fname'] . " " . $staff_info['user_lname'];
                    if ($staff_info['user_authorities'] == 2)
                        $event['position'] = 'Super manager';
                    elseif ($staff_info['user_authorities'] == 3)
                        $event['position'] = "Manager";
                    elseif ($staff_info['user_authorities'] == 4)
                        $event['position'] = "Staff";
                }
                //fetch client info
                $client_info = $client->getClientId($row['client_id']);
                if ($client_info)
                    $event['customer'] = $client_info['client_name'];
                $event['link'] = 'google.com.vn';
                $events[] = $event;
            }
        }
        // Obtain a list of columns
        //sort event
        foreach ($events as $key => $row) {
            $volume[$key] = $row['start'];
            $edition[$key] = $row['time'];
        }

// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
        if ($events)
            array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $events);
        return $events;
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
