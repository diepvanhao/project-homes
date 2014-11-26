<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Report {

    /**
     * 
     * @global type $user
     * @global type $database
     * @param type $id : It is agent id
     * @return array Description
     * 
     */
    public function getUsersByAgent($id = null) {
        if (empty($id)) {
            return null;
        }
        global $database;
        /*
          $select = " SELECT u.*,a.*,h.* FROM home_user as u
          INNER JOIN  home_agent as a ON u.agent_id = a.id
          LEFT JOIN home_order as o ON o.user_id = u.id
          LEFT JOIN home_history_log as h ON h.order_id = o.id
          WHERE u.agent_id = {$id}
          ";
         * 
         */
        $select = " SELECT u.* FROM home_user as u
            WHERE u.agent_id = {$id}
            ";

        $result = $database->database_query($select);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 
     * @global type $database
     * @param type $user_id
     * @param type $fromdate
     * @param type $todate
     * @return real
     */
    public function getUserTarget($user_id = 0, $todate = null, $fromdate = null) {
        global $database;
        if (empty($user_id)) {
            return array();
        }
        if (empty($todate)) {
            $time = time();
        } else {
            $arr = explode('/', $todate);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }
        $select = "SELECT SUM(t.target) as sum FROM home_user_target AS t 
                 WHERE t.user_id = {$user_id} AND DATE_FORMAT(  t.create_date ,'%Y-%m') <= '" . date('Y-m', $time) . "' AND DATE_FORMAT( t.create_date ,'%Y-%m') >= '" . date('Y-m', $fromtime) . "'";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        return $row[0];
    }

    /**
     * 
     * @global type $database
     * @return array 
     */
    public function getAllAgents() {
        global $database;
        $select = "SELECT a.* FROM home_agent a 
                  LEFT JOIN home_user u ON u.agent_id = a.id  
                  GROUP BY u.agent_id
                  ORDER BY a.agent_name ASC";
        $result = $database->database_query($select);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 
     * @global type $database
     * @param int $user_id
     * @return array
     */
    public function getUserInfo($user_id = 0, $date = null, $fromdate = null) {

        global $database;
        if (empty($user_id)) {
            return array();
        }
        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }
        $return = array();

        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";
        //cost today
        $select = "SELECT SUM(d.contract_total) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$today}";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['cost_today'] = (int) $row[0];

        ////cost of month
        $select = "SELECT SUM(d.contract_total) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$month}";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['cost_month'] = (int) $row[0];


        //previous month
//        $select = "SELECT SUM(d.contract_total) FROM home_order o
//            INNER JOIN home_contract c  ON o.id = c.order_id
//            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
//            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%m')= '" . date("Y-m", strtotime(date('Y-m', $time) . " -1 months")) . "'";
//        $result = $database->database_query($select);
//        $row = $database->database_fetch_array($result);
        $return['cost_previous_month'] = 0.00; 
       //Unsigned_broker_fee_today
        $select = "SELECT SUM(d.contract_broker_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  DATE_FORMAT( FROM_UNIXTIME( d.contract_application_date ) ,'%Y-%m')= '" . date("Y-m", strtotime(date('Y-m', $time) . " -1 months")) . "'
                  AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%m') <> '" . date("Y-m", strtotime(date('Y-m', $time) . " -1 months")) . "'";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['cost_previous_month'] += (float) $row[0];      
        //Unsigned_ads_fee_today
        $select = "SELECT SUM(d.contract_ads_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  DATE_FORMAT( FROM_UNIXTIME( d.contract_application_date ) ,'%Y-%m')= '" . date("Y-m", strtotime(date('Y-m', $time) . " -1 months")) . "'
                  AND  (d.contract_payment_report <> 1 OR DATE_FORMAT( FROM_UNIXTIME( d.contract_payment_date_to ) ,'%Y-%m') <> '" . date("Y-m", strtotime(date('Y-m', $time) . " -1 months")) . "')";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['cost_previous_month'] += (float) $row[0]; 
        
        
        $today_appointment = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month_appointment = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";
        
        $today_log_time_call = "DATE_FORMAT( FROM_UNIXTIME( h.log_time_call ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month_log_time_call = "h.log_time_call  <=  $time AND  h.log_time_call >=  $fromtime ";
        //more info on today
        $select = "SELECT SUM(log_shop_sign) AS today_shop_sign, SUM(log_local_sign) AS today_local_sign, SUM(log_introduction) AS today_introduction, SUM(log_flyer) AS today_flyer, SUM(log_line) AS today_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND  {$today_appointment}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT  SUM(log_tel) AS today_tel, SUM(log_mail) AS today_mail, SUM(log_contact_head_office) AS today_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS today_tel_status,SUM(log_mail) AS today_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['today_revisit'] = $this->getRevisit("o.order_status = 1 AND o.user_id = {$user_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'");
        
        //more info on this month 
        $select = "SELECT SUM(log_shop_sign) AS month_shop_sign, SUM(log_local_sign) AS month_local_sign, SUM(log_introduction) AS month_introduction, SUM(log_flyer) AS month_flyer, SUM(log_line) AS month_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND  {$month_appointment}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
         $select = "SELECT SUM(log_tel) AS month_tel, SUM(log_mail) AS month_mail, SUM(log_contact_head_office) AS month_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND  {$month_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS month_tel_status,SUM(log_mail) AS month_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND h.log_status_appointment = 1 AND {$month_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['month_revisit'] = $this->getRevisit(" o.order_status = 1 AND o.user_id = {$user_id} AND  rv.revisit_date  <= $time AND rv.revisit_date  >= $fromtime  ");
        
        //Application      
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND o.user_id = {$user_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_application'] = (int) $row[0];
        
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND o.user_id = {$user_id} AND o.order_status = 1 
                AND d.contract_application_date  <= $time AND d.contract_application_date >= $fromtime ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_cancel'] = (int) $row[0];
        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'  ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND d.contract_signature_day  <= $time AND d.contract_signature_day >= $fromtime  ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_agreement'] = (int) $row[0];

        //company registeration
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_ambition = 1 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_ambition'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_ambition = 1 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_ambition'] = (int) $row[0];

        return $return;
    }

    public function getLastyearInfo($agent_id = 0, $date = null, $fromdate = null) {
        global $database;
        if (empty($agent_id)) {
            return array();
        }
        $return = array();
        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }
        $return = array();

        $today = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $year = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

        $today_log_time_call = "DATE_FORMAT( FROM_UNIXTIME( h.log_time_call ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $year_log_time_call = " h.log_time_call <=  $time AND h.log_time_call >= $fromtime ";

//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m') . "'";
//        $year = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y')= '" . date('Y') . "'";
        /**
         * Mail report 
         */
        //track record
        $select = "SELECT SUM(log_shop_sign) AS todaymail_shop_sign, SUM(log_local_sign) AS todaymail_local_sign, SUM(log_introduction) AS todaymail_introduction, SUM(log_flyer) AS todaymail_flyer, SUM(log_line) AS todaymail_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT  SUM(log_tel) AS todaymail_tel, SUM(log_mail) AS todaymail_mail, SUM(log_contact_head_office) AS todaymail_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todaymail_tel_status,SUM(log_mail) AS todaymail_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todaymail_revisit'] = $this->getRevisit(" h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yearmail_shop_sign, SUM(log_local_sign) AS yearmail_local_sign, SUM(log_introduction) AS yearmail_introduction,SUM(log_flyer) AS yearmail_flyer, SUM(log_line) AS yearmail_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS yearmail_tel, SUM(log_mail) AS yearmail_mail, SUM(log_contact_head_office) AS yearmail_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS yearmail_tel_status,SUM(log_mail) AS yearmail_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearmail_revisit'] = $this->getRevisit(" h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime  ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaymail_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearmail_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaymail_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearmail_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaymail_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearmail_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaymail_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearmail_agreement'] = (int) $row[0];

        /**
         * Phone report
         */
        $select = "SELECT SUM(log_shop_sign) AS todayphone_shop_sign, SUM(log_local_sign) AS todayphone_local_sign, SUM(log_introduction) AS todayphone_introduction, SUM(log_flyer) AS todayphone_flyer, SUM(log_line) AS todayphone_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayphone_tel, SUM(log_mail) AS todayphone_mail, SUM(log_contact_head_office) AS todayphone_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayphone_tel_status,SUM(log_mail) AS todayphone_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todayphone_revisit'] = $this->getRevisit(" h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yearphone_shop_sign, SUM(log_local_sign) AS yearphone_local_sign, SUM(log_introduction) AS yearphone_introduction, SUM(log_flyer) AS yearphone_flyer, SUM(log_line) AS yearphone_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
         $select = "SELECT SUM(log_tel) AS yearphone_tel, SUM(log_mail) AS yearphone_mail,  SUM(log_contact_head_office) AS yearphone_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS yearphone_tel_status,SUM(log_mail) AS yearphone_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearphone_revisit'] = $this->getRevisit(" h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime  ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayphone_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearphone_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayphone_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearphone_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayphone_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearphone_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayphone_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearphone_agreement'] = (int) $row[0];
        /*
         * house discount
         */
        $select = "SELECT SUM(log_shop_sign) AS todaydiscount_shop_sign, SUM(log_local_sign) AS todaydiscount_local_sign, SUM(log_introduction) AS todaydiscount_introduction, SUM(log_tel) AS todaydiscount_tel, 
            SUM(log_mail) AS todaydiscount_mail, SUM(log_flyer) AS todaydiscount_flyer, SUM(log_line) AS todaydiscount_line, SUM(log_contact_head_office) AS todaydiscount_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todaydiscount_tel, SUM(log_mail) AS todaydiscount_mail,  SUM(log_contact_head_office) AS todaydiscount_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS todaydiscount_tel_status ,SUM(log_mail) AS todaydiscount_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todaydiscount_revisit'] = $this->getRevisit(" d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yeardiscount_shop_sign, SUM(log_local_sign) AS yeardiscount_local_sign, SUM(log_introduction) AS yeardiscount_introduction, SUM(log_tel) AS yeardiscount_tel, 
            SUM(log_mail) AS yeardiscount_mail, SUM(log_flyer) AS yeardiscount_flyer, SUM(log_line) AS yeardiscount_line, SUM(log_contact_head_office) AS yeardiscount_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT  SUM(log_tel) AS yeardiscount_tel, SUM(log_mail) AS yeardiscount_mail, SUM(log_contact_head_office) AS yeardiscount_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS yeardiscount_tel_status ,SUM(log_mail) AS yeardiscount_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
            
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yeardiscount_revisit'] = $this->getRevisit(" d.room_discount > 0 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime  ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaydiscount_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yeardiscount_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaydiscount_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yeardiscount_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaydiscount_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yeardiscount_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            WHERE d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaydiscount_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_house hh  ON hh.id = o.house_id
            WHERE hh.house_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yeardiscount_agreement'] = (int) $row[0];
        /*
         * Local sign
         */
        $select = "SELECT SUM(log_shop_sign) AS todaylocalsign_shop_sign, SUM(log_local_sign) AS todaylocalsign_local_sign, SUM(log_introduction) AS todaylocalsign_introduction, SUM(log_flyer) AS todaylocalsign_flyer, SUM(log_line) AS todaylocalsign_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT  SUM(log_tel) AS todaylocalsign_tel, SUM(log_mail) AS todaylocalsign_mail, SUM(log_contact_head_office) AS todaylocalsign_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todaylocalsign_tel_status ,SUM(log_mail) AS todaylocalsign_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todaylocalsign_revisit'] = $this->getRevisit(" h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yearlocalsign_shop_sign, SUM(log_local_sign) AS yearlocalsign_local_sign, SUM(log_introduction) AS yearlocalsign_introduction, SUM(log_tel) AS yearlocalsign_tel, 
            SUM(log_mail) AS yearlocalsign_mail, SUM(log_flyer) AS yearlocalsign_flyer, SUM(log_line) AS yearlocalsign_line, SUM(log_contact_head_office) AS yearlocalsign_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS yearlocalsign_tel, SUM(log_mail) AS yearlocalsign_mail,  SUM(log_contact_head_office) AS yearlocalsign_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS yearlocalsign_tel_status ,SUM(log_mail) AS yearlocalsign_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearlocalsign_revisit'] = $this->getRevisit(" h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaylocalsign_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearlocalsign_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaylocalsign_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearlocalsign_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaylocalsign_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearlocalsign_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaylocalsign_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearlocalsign_agreement'] = (int) $row[0];

        /*
         * Introduction
         */
        $select = "SELECT SUM(log_shop_sign) AS todayintroduction_shop_sign, SUM(log_local_sign) AS todayintroduction_local_sign, SUM(log_introduction) AS todayintroduction_introduction, SUM(log_flyer) AS todayintroduction_flyer, SUM(log_line) AS todayintroduction_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayintroduction_tel, SUM(log_mail) AS todayintroduction_mail, SUM(log_contact_head_office) AS todayintroduction_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayintroduction_tel_status ,SUM(log_mail) AS todayintroduction_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todayintroduction_revisit'] = $this->getRevisit(" h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");

        $select = "SELECT SUM(log_shop_sign) AS yearintroduction_shop_sign, SUM(log_local_sign) AS yearintroduction_local_sign, SUM(log_introduction) AS yearintroduction_introduction, SUM(log_flyer) AS yearintroduction_flyer, SUM(log_line) AS yearintroduction_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS yearintroduction_tel, SUM(log_mail) AS yearintroduction_mail, SUM(log_contact_head_office) AS yearintroduction_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS yearintroduction_tel_status ,SUM(log_mail) AS yearintroduction_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearintroduction_revisit'] = $this->getRevisit(" h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayintroduction_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearintroduction_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayintroduction_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearintroduction_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayintroduction_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearintroduction_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayintroduction_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearintroduction_agreement'] = (int) $row[0];
        /*
         * Shop sign
         */
        $select = "SELECT SUM(log_shop_sign) AS todayshopsign_shop_sign, SUM(log_local_sign) AS todayshopsign_local_sign, SUM(log_introduction) AS todayshopsign_introduction,
               SUM(log_flyer) AS todayshopsign_flyer, SUM(log_line) AS todayshopsign_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS todayshopsign_tel, SUM(log_mail) AS todayshopsign_mail, SUM(log_contact_head_office) AS todayshopsign_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayshopsign_tel_status ,SUM(log_mail) AS todayshopsign_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todayshopsign_revisit'] = $this->getRevisit(" h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yearshopsign_shop_sign, SUM(log_local_sign) AS yearshopsign_local_sign, SUM(log_introduction) AS yearshopsign_introduction,
               SUM(log_flyer) AS yearshopsign_flyer, SUM(log_line) AS yearshopsign_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS yearshopsign_tel, SUM(log_mail) AS yearshopsign_mail, SUM(log_contact_head_office) AS yearshopsign_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
         $select = "SELECT SUM(log_tel) AS yearshopsign_tel_status ,SUM(log_mail) AS yearshopsign_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearshopsign_revisit'] = $this->getRevisit(" h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayshopsign_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearshopsign_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayshopsign_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearshopsign_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayshopsign_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearshopsign_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayshopsign_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL  AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearshopsign_agreement'] = (int) $row[0];
        /*
         * Log Flyer
         */
        $select = "SELECT SUM(log_shop_sign) AS todayflyer_shop_sign, SUM(log_local_sign) AS todayflyer_local_sign, SUM(log_introduction) AS todayflyer_introduction, 
               SUM(log_flyer) AS todayflyer_flyer, SUM(log_line) AS todayflyer_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS todayflyer_tel, SUM(log_mail) AS todayflyer_mail, SUM(log_contact_head_office) AS todayflyer_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayflyer_tel_status ,SUM(log_mail) AS todayflyer_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todayflyer_revisit'] = $this->getRevisit(" h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yearflyer_shop_sign, SUM(log_local_sign) AS yearflyer_local_sign, SUM(log_introduction) AS yearflyer_introduction, SUM(log_tel) AS yearflyer_tel, 
            SUM(log_mail) AS yearflyer_mail, SUM(log_flyer) AS yearflyer_flyer, SUM(log_line) AS yearflyer_line, SUM(log_contact_head_office) AS yearflyer_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
         $select = "SELECT SUM(log_tel) AS yearflyer_tel, SUM(log_mail) AS yearflyer_mail, SUM(log_contact_head_office) AS yearflyer_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS yearflyer_tel_status ,SUM(log_mail) AS yearflyer_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearflyer_revisit'] = $this->getRevisit(" h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayflyer_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearflyer_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayflyer_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearflyer_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayflyer_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearflyer_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayflyer_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
               AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearflyer_agreement'] = (int) $row[0];

        /*
         * Line
         */
        $select = "SELECT SUM(log_shop_sign) AS todayline_shop_sign, SUM(log_local_sign) AS todayline_local_sign, SUM(log_introduction) AS todayline_introduction, 
               SUM(log_flyer) AS todayline_flyer, SUM(log_line) AS todayline_line
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        $select = "SELECT SUM(log_tel) AS todayline_tel, SUM(log_mail) AS todayline_mail, SUM(log_contact_head_office) AS todayline_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS todayline_tel_status ,SUM(log_mail) AS todayline_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$today_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['todayline_revisit'] = $this->getRevisit(" h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        $select = "SELECT SUM(log_shop_sign) AS yearline_shop_sign, SUM(log_local_sign) AS yearline_local_sign, SUM(log_introduction) AS yearline_introduction, SUM(log_tel) AS yearline_tel, 
            SUM(log_mail) AS yearline_mail, SUM(log_flyer) AS yearline_flyer, SUM(log_line) AS yearline_line, SUM(log_contact_head_office) AS yearline_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_tel) AS yearline_tel, SUM(log_mail) AS yearline_mail, SUM(log_contact_head_office) AS yearline_contact_head_office
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        
        $select = "SELECT SUM(log_tel) AS yearline_tel_status ,SUM(log_mail) AS yearline_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND h.log_status_appointment = 1 AND {$year_log_time_call}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['yearline_revisit'] = $this->getRevisit(" h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y')= '" . date('Y', $time) . "' AND rv.revisit_date <=  $time AND rv.revisit_date >= $fromtime  ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
                AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'  ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayline_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 
               AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearline_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayline_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearline_cancel'] = (int) $row[0];

        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayline_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearline_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayline_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' 
                AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "' ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearline_agreement'] = (int) $row[0];


        return $return;
    }

    /**
     * 
     * @param type $agent_id
     * @return int
     */
    public function getAgentCostOfMonth($agent_id = 0, $date = null, $fromdate = null) {
        if (empty($agent_id)) {
            return 0;
        }
        global $database;

        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }
        $return = array();

//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m',$time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%m')= '" . date('Y-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

//        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date('Y-m') . "'";
        ////cost of month
        $select = "SELECT SUM(d.contract_total) FROM home_contract_detail d
            INNER JOIN home_user u  ON d.user_id = u.id
            WHERE u.agent_id = {$agent_id} AND o.order_status = 1 AND  {$month}";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);

        return (float) $row[0];
    }

    /**
     * 
     * @global global $database
     * @param int $id
     * @return array
     */
    public function getAgentInfo($id = null) {
        if (empty($id)) {
            return null;
        }
        global $database;
        $select = "SELECT * FROM home_agent WHERE id = '{$id}' LIMIT 1";
        $result = $database->database_query($select);
        return $database->database_fetch_assoc($result);
    }

    /**
     * 
     * @global type $database
     * @return type
     */
    public function getAllCompany() {
        global $database;
        $select = "SELECT c.* FROM home_broker_company c 
                  ORDER BY c.id ASC";
        $result = $database->database_query($select);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 
     * @global type $database
     * @return type
     */
    public function getAllSource() {
        global $database;
        $select = "SELECT s.* FROM home_source s 
                  ORDER BY s.id ASC";
        $result = $database->database_query($select);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * 
     * @global type $database
     * @param type $company_id
     * @return type
     */
    public function getCompanyInfo($company_id = 0, $date = null, $fromdate = null) {
        global $database;
        if (empty($company_id)) {
            return array();
        }
        $return = array();

        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }

        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }
        $return = array();

        $today = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

//        
//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m') . "'";
//        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date('Y-m') . "'";
        //more info on today
        $select = "SELECT SUM(log_shop_sign) AS today_shop_sign, SUM(log_local_sign) AS today_local_sign, SUM(log_introduction) AS today_introduction, SUM(log_tel) AS today_tel, 
            SUM(log_mail) AS today_mail, SUM(log_flyer) AS today_flyer, SUM(log_line) AS today_line, SUM(log_contact_head_office) AS today_contact_head_office,
            SUM(log_tel_status) AS today_tel_status,SUM(log_mail_status) AS today_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //log revisit
        $return['today_revisit'] = $this->getRevisit(" o.order_status = 1 AND c.id = {$company_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");
        
        //more info on this month 
        $select = "SELECT SUM(log_shop_sign) AS month_shop_sign, SUM(log_local_sign) AS month_local_sign, SUM(log_introduction) AS month_introduction, SUM(log_tel) AS month_tel, 
            SUM(log_mail) AS month_mail, SUM(log_flyer) AS month_flyer, SUM(log_line) AS month_line, SUM(log_contact_head_office) AS month_contact_head_office,
            SUM(log_tel_status) AS month_tel_status,SUM(log_mail_status) AS month_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND  {$month}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //log revisit
        $return['month_revisit'] = $this->getRevisit(" o.order_status = 1 AND c.id = {$company_id} AND  rv.revisit_date  <= $time AND rv.revisit_date  >= $fromtime ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_broker_company b  ON o.broker_id = b.id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND o.order_status = 1 AND b.id = {$company_id}  AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id}  AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 1 AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 1 AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_cancel'] = (int) $row[0];
        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_agreement'] = (int) $row[0];

        return $return;
    }

    /**
     * 
     * @global type $database
     * @param type $company_id
     * @return type
     */
    public function getSourceInfo($source_id = 0, $date = null, $fromdate = null) {
        global $database;
        if (empty($source_id)) {
            return array();
        }

        $return = array();

//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m') . "'";
//        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date('Y-m') . "'";
        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }

        $today = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( h.log_date_appointment_from ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

 
        //more info on today
        $select = "SELECT SUM(log_shop_sign) AS today_shop_sign, SUM(log_local_sign) AS today_local_sign, SUM(log_introduction) AS today_introduction, SUM(log_tel) AS today_tel, 
            SUM(log_mail) AS today_mail, SUM(log_flyer) AS today_flyer, SUM(log_line) AS today_line, SUM(log_contact_head_office) AS today_contact_head_office,
            SUM(log_tel_status) AS today_tel_status,SUM(log_mail_status) AS today_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        
        //log revisit
        $return['today_revisit'] = $this->getRevisit(" o.order_status = 1 AND h.source_id = {$source_id} AND  DATE_FORMAT( FROM_UNIXTIME( rv.revisit_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "' ");

        //more info on this month 
        $select = "SELECT SUM(log_shop_sign) AS month_shop_sign, SUM(log_local_sign) AS month_local_sign, SUM(log_introduction) AS month_introduction, SUM(log_tel) AS month_tel, 
            SUM(log_mail) AS month_mail, SUM(log_flyer) AS month_flyer, SUM(log_line) AS month_line, SUM(log_contact_head_office) AS month_contact_head_office,
            SUM(log_tel_status) AS month_tel_status,SUM(log_mail_status) AS month_mail_status
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND  {$month}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //log revisit
        $return['month_revisit'] = $this->getRevisit(" o.order_status = 1 AND h.source_id = {$source_id} AND  rv.revisit_date  <= $time AND rv.revisit_date  >= $fromtime ");
        
        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.order_status = 1 AND h.source_id = {$source_id}  AND d.contract_application = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.order_status = 1 AND h.source_id = {$source_id}  AND d.contract_application = 1  AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND d.contract_cancel = 1 AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND d.contract_cancel = 1 AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_cancel'] = (int) $row[0];
        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND o.change = 1 AND h.source_id = {$source_id}  AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND o.change = 1  AND h.source_id = {$source_id} AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_agreement'] = (int) $row[0];

        return $return;
    }

    public function userCommission($user_id = null, $date = null, $fromdate = null) {
        global $database;
        if (empty($user_id)) {
            return array();
        }
        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }


        $today = "DATE_FORMAT( FROM_UNIXTIME( d.contract_application_date ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( d.contract_application_date ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( d.contract_application_date ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";
        $return = array(
            'today_already_recorded' => 0.00,
            'today_unsigned' => 0.00,
            'month_already_recorded' => 0.00,
            'month_unsigned' => 0.00,
        );
        //Unsigned_broker_fee_today
        $select = "SELECT SUM(d.contract_broker_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$today} 
                  AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') <> '" . date('Y-d-m', $time) . "'";
            
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_unsigned'] += (float) $row[0];      
        
        //Unsigned_broker_fee_month
        $select = "SELECT SUM(d.contract_broker_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$today} 
                  AND (d.contract_signature_day > {$time} OR d.contract_signature_day < {$fromtime} OR  d.contract_signature_day IS NULL OR  d.contract_signature_day = '' ) ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_unsigned'] += (float) $row[0]; 

        //Unsigned_ads_fee_today
        $select = "SELECT SUM(d.contract_ads_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$today} 
                  AND  (d.contract_payment_report <> 1 OR DATE_FORMAT( FROM_UNIXTIME( d.contract_payment_date_to ) ,'%Y-%d-%m') <> '" . date('Y-d-m', $time) . "')";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_unsigned'] += (float) $row[0]; 
        
        //Unsigned_ads_fee_month
        $select = "SELECT SUM(d.contract_ads_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$month} 
                  AND (d.contract_payment_date_to > {$time} OR d.contract_payment_date_to < {$fromtime} OR  d.contract_payment_date_to IS NULL OR d.contract_payment_report <> 1)";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_unsigned'] += (float) $row[0];        
        
        //***recorded_broker_fee_today
        $select = "SELECT SUM(d.contract_broker_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 
                  AND DATE_FORMAT( FROM_UNIXTIME( d.contract_signature_day ) ,'%Y-%d-%m') = '" . date('Y-d-m', $time) . "'";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_already_recorded'] += (float) $row[0];

        //***recorded_broker_fee_month
        $select = "SELECT SUM(d.contract_broker_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 
                  AND (d.contract_signature_day <= {$time} AND d.contract_signature_day >= {$fromtime})";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_already_recorded'] += (float) $row[0];
        
        //Already_ads_fee_today
        $select = "SELECT SUM(d.contract_ads_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 
                  AND  d.contract_payment_report = 1 AND DATE_FORMAT( FROM_UNIXTIME( d.contract_payment_date_to ) ,'%Y-%d-%m') = '" . date('Y-d-m', $time) . "'";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_already_recorded'] += (float) $row[0]; 
        //Already_ads_fee_month
        $select = "SELECT SUM(d.contract_ads_fee) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1
                  AND d.contract_payment_date_to <= {$time} AND d.contract_payment_date_to >= {$fromtime} 
                  AND  d.contract_payment_report = 1";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_already_recorded'] += (float) $row[0];   
        
        return  array(
            'today_already_recorded' => round($return['today_already_recorded']/1.08,2),
            'today_unsigned' => round($return['today_unsigned']/1.08,2),
            'month_already_recorded' => round($return['month_already_recorded']/1.08,2),
            'month_unsigned' => round($return['month_unsigned']/1.08,2),
        );;
    }

    public function getChartInfo($agent_id = 0, $date = null, $fromdate = null) {
        if (empty($agent_id)) {
            return 0;
        }
        global $database;

        if (empty($date)) {
            $time = time();
        } else {
            $arr = explode('/', $date);
            $time = mktime(23, 59, 59, $arr[1], $arr[2], $arr[0]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[1], $arr[2], $arr[0]);
        }
        $return = array();

        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%m')= '" . date('Y-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_update ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

        ////cost of month
        $select = "SELECT SUM(d.contract_total) FROM home_contract_detail d
            INNER JOIN home_user u  ON d.user_id = u.id
            WHERE u.agent_id = {$agent_id} AND o.order_status = 1 AND  {$month}";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);

        return (float) $row[0];
    }

    public function exportOrder($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = @date('d/m/Y');
        $order_date = @date('d/m/Y', $row['order_day_create']);

        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "Order Report";

        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                )
            )
        );
        $greenColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'CCFFCC')
            )
        );
        $yellowColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FCF305')
            )
        );

        $redColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF99CC')
            )
        );

        $index = 2;
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        //Top
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:S{$index}")->applyFromArray($border);

        $objPHPExcel->getActiveSheet()->getStyle("B{$index}:C{$index}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("M{$index}:S{$index}")->applyFromArray($greenColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:C{$index}")
                ->mergeCells("H{$index}:J{$index}")
                ->mergeCells("M{$index}:N{$index}")
                ->mergeCells("O{$index}:R{$index}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "秋葉原店")
                ->setCellValue("H{$index}", "担当者")
                ->setCellValue("K{$index}", "{$row['user_fname']} {$row['user_lname']}")
                ->setCellValue("L{$index}", "申込日")
                ->setCellValue("M{$index}", "{$order_date}")
        ;

        $index ++;
        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:D{$index}")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:R{$index}")->applyFromArray($border);

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:D{$index}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("M{$index}:R{$index}")->applyFromArray($greenColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:B{$index}")
                ->mergeCells("K{$index}:L{$index}")
                ->mergeCells("M{$index}:R{$index}")
        ;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "台帳コード")
                ->setCellValue("C{$index}", "{$row['id']}")
//                ->setCellValue("D{$index}", "AAA")
                ->setCellValue("K{$index}", "台帳提出日")
                ->setCellValue("M{$index}", "$order_date")
        ;

        $index ++;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:S{$index}")->applyFromArray($border);

        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:S{$index}")->applyFromArray($yellowColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:H{$plus}")
                ->mergeCells("K{$index}:L{$index}")
                ->mergeCells("M{$index}:S{$index}")
                ->mergeCells("L{$plus}:Q{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "取引成立台帳（賃貸借）")
                ->setCellValue("K{$index}", "伝票№")
                ->setCellValue("M{$index}", "")
                ->setCellValue("L{$plus}", "成立年月日")
        ;

        $signdate = @date('d/m/Y',$row['contract_signature_day']);
        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$plus}:J{$plus}")->applyFromArray(array_merge($border, $greenColor));
        $objPHPExcel->getActiveSheet()->getStyle("L{$index}:Q{$plus}")->applyFromArray(array_merge($border, $greenColor));
        $objPHPExcel->getActiveSheet()->getStyle("A{$plus}:T" . ($plus + 33))->applyFromArray($border);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("C{$plus}:J{$plus}")
                ->mergeCells("L{$index}:Q{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$plus}", "フリガナ")
                ->setCellValue("C{$plus}", "{$row['client_name']}")
                ->setCellValue("L{$index}", "{$signdate}")
        ;
        //1
        $index = $plus + 1;
        $plus = $index + 6;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "借主")
        ;

        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:J{$plus}")->applyFromArray(array_merge($border, $greenColor));

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "契約者")
                ->setCellValue("C{$index}", "{$row['client_name']}")
                ->setCellValue("K{$index}", "電話")
                ->setCellValue("L{$index}", "{$row['client_phone']}")
        ;
        $house = new HOMEHouse();
        if ($house->isSerialized($row['client_address'])) {
            $house_address_serialize = unserialize($row['client_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['client_address'];
            $row['client_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "現住所")
                ->setCellValue("C{$index}", "{$row['client_address']}")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("C{$index}:J{$index}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "フリガナ")
                ->setCellValue("C{$index}", "ホンニン")
        ;

        $resident = empty($row['client_resident_name']) ? $row['client_name'] : $row['client_resident_name'];
        $resident_phone = empty($row['client_resident_phone']) ? $row['client_phone'] : $row['client_resident_phone'];
        $index = $index + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "入居者")
                ->setCellValue("C{$index}", "{$resident}")
                ->setCellValue("K{$index}", "電話")
                ->setCellValue("L{$index}", "{$resident_phone}")
        ;

        //2
        $index = $plus + 1;
        $plus = $index + 3;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "貸主")
        ;

        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "氏　名")
                ->setCellValue("C{$index}", "{$row['house_owner_name']}")
                ->setCellValue("K{$index}", "電話")
                ->setCellValue("L{$index}", "{$row['house_owner_phone']}")
        ;

        if ($house->isSerialized($row['house_owner_address'])) {
            $house_address_serialize = unserialize($row['house_owner_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['house_owner_address'];
            $row['house_owner_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "住　所")
                ->setCellValue("C{$index}", "{$row['house_owner_address']}")
        ;

        //3
        $index = $plus + 1;
        $plus = $index + 5;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "元付")
        ;


        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:T{$plus}")->applyFromArray(array_merge($border, $greenColor));

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "元付業者")
                ->setCellValue("C{$index}", "{$row['broker_company_name']}")
        ;

        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:J{$plus}")->applyFromArray(array_merge($border, $greenColor));
        $objPHPExcel->getActiveSheet()->getStyle("L{$index}:T{$plus}")->applyFromArray(array_merge($border, $greenColor));

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "担当者")
                ->setCellValue("C{$index}", "{$row['broker_company_undertake']}")
                ->setCellValue("K{$index}", "電話")
                ->setCellValue("L{$index}", "{$row['broker_company_phone']}")
        ;
        if ($house->isSerialized($row['broker_company_address'])) {
            $house_address_serialize = unserialize($row['broker_company_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['broker_company_address'];
            $row['broker_company_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        if ($house->isSerialized($row['house_address'])) {
            $house_address_serialize = unserialize($row['house_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['house_address'];
            $row['house_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "現住所")
                ->setCellValue("C{$index}", "{$row['broker_company_address']}")
        ;

        //4
        $index = $plus + 1;
        $plus = $index + 5;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "物件の表示")
        ;

        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "所在地")
                ->setCellValue("C{$index}", "{$row['house_address']}")
        ;

        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:R{$plus}")->applyFromArray(array_merge($border, $greenColor));

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:N{$plus}")
                ->mergeCells("O{$index}:R{$plus}")
                ->mergeCells("S{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "名　称")
                ->setCellValue("C{$plus}", "{$row['house_name']}")
                ->setCellValue("O{$index}", "{$row['room_id']}")
                ->setCellValue("S{$index}", "号室")
        ;

        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("H{$index}:J{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("M{$index}:O{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("R{$index}:T{$plus}")->applyFromArray($redColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:D{$plus}")
                ->mergeCells("E{$index}:E{$plus}")
                ->mergeCells("F{$index}:G{$plus}")
                ->mergeCells("H{$index}:H{$plus}")
                ->mergeCells("I{$index}:J{$plus}")
                ->mergeCells("K{$index}:L{$plus}")
                ->mergeCells("M{$index}:O{$plus}")
                ->mergeCells("P{$index}:Q{$plus}")
                ->mergeCells("R{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "面　積")
                ->setCellValue("C{$index}", "{$row['room_size']}")
                ->setCellValue("E{$index}", "㎡")
                ->setCellValue("F{$index}", "間取り")
                ->setCellValue("H{$index}", "")
                ->setCellValue("I{$index}", "{$row['room_name']}")
                ->setCellValue("K{$index}", "構造")
                ->setCellValue("M{$index}", "")
                ->setCellValue("P{$index}", "用途")
                ->setCellValue("R{$index}", "")
        ;

        //5
        $index = $plus + 1;
        $plus = $index + 5;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "賃貸条件")
        ;

        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:E{$plus}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("I{$index}:I{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:M{$plus}")->applyFromArray($yellowColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:E{$plus}")
                ->mergeCells("F{$index}:F{$plus}")
                ->mergeCells("G{$index}:H{$plus}")
                ->mergeCells("I{$index}:I{$plus}")
                ->mergeCells("J{$index}:J{$plus}")
                ->mergeCells("K{$index}:M{$plus}")
                ->mergeCells("N{$index}:N{$plus}")
                ->mergeCells("O{$index}:T{$plus}")
        ;
        $k = (float) ($row['room_rent'] * $row['room_deposit']);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "月額賃料")
                ->setCellValue("C{$index}", "{$row['room_rent']}")
                ->setCellValue("F{$index}", "円")
                ->setCellValue("G{$index}", "敷　金")
                ->setCellValue("I{$index}", "{$row['room_deposit']}")
                ->setCellValue("J{$index}", "ヶ月")
                ->setCellValue("K{$index}", "$k")
                ->setCellValue("N{$index}", "円")
                ->setCellValue("O{$index}", "契約期間")
        ;

        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:E{$plus}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("I{$index}:I{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:M{$plus}")->applyFromArray($yellowColor);
        $objPHPExcel->getActiveSheet()->getStyle("P{$index}:S{$plus}")->applyFromArray($greenColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:E{$plus}")
                ->mergeCells("F{$index}:F{$plus}")
                ->mergeCells("G{$index}:H{$plus}")
                ->mergeCells("I{$index}:I{$plus}")
                ->mergeCells("J{$index}:J{$plus}")
                ->mergeCells("K{$index}:M{$plus}")
                ->mergeCells("N{$index}:N{$plus}")
                ->mergeCells("O{$index}:O{$plus}")
                ->mergeCells("P{$index}:S{$plus}")
                ->mergeCells("T{$index}:T{$plus}")
        ;
        $k = (float) ($row['room_key_money'] * 0);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "月額管理費")
                ->setCellValue("C{$index}", "{$row['room_key_money']}")
                ->setCellValue("F{$index}", "円")
                ->setCellValue("G{$index}", "礼　金")
                ->setCellValue("I{$index}", "0")
                ->setCellValue("J{$index}", "ヶ月")
                ->setCellValue("K{$index}", "$k")
                ->setCellValue("N{$index}", "円")
                ->setCellValue("O{$index}", "自")
                ->setCellValue("P{$index}", @date('d/m/Y',$row['contract_period_from']))
                ->setCellValue("T{$index}", "～")
        ;

        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("I{$index}:I{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:M{$plus}")->applyFromArray($yellowColor);
        $objPHPExcel->getActiveSheet()->getStyle("P{$index}:S{$plus}")->applyFromArray($greenColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:C{$plus}")
                ->mergeCells("D{$index}:D{$plus}")
                ->mergeCells("E{$index}:E{$plus}")
                ->mergeCells("F{$index}:F{$plus}")
                ->mergeCells("G{$index}:H{$plus}")
                ->mergeCells("K{$index}:M{$plus}")
                ->mergeCells("P{$index}:S{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "入居者人員")
                ->setCellValue("C{$index}", "")
                ->setCellValue("D{$index}", "名")
                ->setCellValue("D{$index}", "取引形態")
                ->setCellValue("F{$index}", "仲介")
                ->setCellValue("G{$index}", "保証金")
                ->setCellValue("I{$index}", "0")
                ->setCellValue("J{$index}", "ヶ月")
                ->setCellValue("K{$index}", "")
                ->setCellValue("N{$index}", "円")
                ->setCellValue("O{$index}", "自")
                ->setCellValue("P{$index}", @date('d/m/Y',$row['contract_period_to']))
                ->setCellValue("T{$index}", "迄")
        ;

        //6
        $index = $plus + 1;
        $plus = $index + 3;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "取引報酬")
        ;

        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:M{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("S{$index}:T{$plus}")->applyFromArray($redColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:C{$plus}")
                ->mergeCells("D{$index}:F{$plus}")
                ->mergeCells("G{$index}:G{$plus}")
                ->mergeCells("H{$index}:J{$plus}")
                ->mergeCells("K{$index}:M{$plus}")
                ->mergeCells("N{$index}:N{$plus}")
                ->mergeCells("O{$index}:R{$plus}")
                ->mergeCells("S{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "　収　入")
                ->setCellValue("C{$index}", "貸主")
                ->setCellValue("D{$index}", "")
                ->setCellValue("G{$index}", "円")
                ->setCellValue("H{$index}", "解約予告")
                ->setCellValue("K{$index}", "")
                ->setCellValue("N{$index}", "前")
                ->setCellValue("O{$index}", "保証金償却")
                ->setCellValue("S{$index}", "")
        ;

        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("D{$index}:F{$plus}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("J{$index}:K{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("R{$index}:R{$plus}")->applyFromArray($redColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:C{$plus}")
                ->mergeCells("D{$index}:F{$plus}")
                ->mergeCells("G{$index}:G{$plus}")
                ->mergeCells("H{$index}:I{$plus}")
                ->mergeCells("J{$index}:K{$plus}")
                ->mergeCells("L{$index}:N{$plus}")
                ->mergeCells("O{$index}:Q{$plus}")
                ->mergeCells("R{$index}:R{$plus}")
                ->mergeCells("S{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "手数料")
                ->setCellValue("C{$index}", "借主")
                ->setCellValue("D{$index}", "")
                ->setCellValue("G{$index}", "円")
                ->setCellValue("H{$index}", "更新＝")
                ->setCellValue("J{$index}", "")
                ->setCellValue("L{$index}", "更新料")
                ->setCellValue("N{$index}", "")
                ->setCellValue("O{$index}", "新賃料の")
                ->setCellValue("R{$index}", "")
                ->setCellValue("S{$index}", "ヶ月分")
        ;

        //7
        $index = $plus + 1;
        $plus = $index + 3;

        $objPHPExcel->getActiveSheet()->getStyle("B{$plus}:C{$plus}")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("S{$plus}:T{$plus}")->applyFromArray($greenColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "特約・備考")
        ;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("S{$plus}", "")
                ->setCellValue("T{$plus}", "")
        ;

        //8
        $index = $plus + 1;
        $plus = $index + 1;

        $objPHPExcel->getActiveSheet()->getStyle("C{$index}:G{$plus}")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("K{$index}:T{$plus}")->applyFromArray($redColor);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:G{$plus}")
                ->mergeCells("H{$index}:J{$plus}")
                ->mergeCells("K{$index}:T{$index}")
                ->mergeCells("K{$plus}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "仲介者")
                ->setCellValue("B{$index}", "商　号")
                ->setCellValue("C{$index}", "株式会社アンビション・ルームピア")
                ->setCellValue("H{$index}", "住　所")
                ->setCellValue("K{$index}", "東京都千代田区外神田1-3-10")
                ->setCellValue("K{$plus}", "藤原ビル1F")
        ;

        //9
        $index = $plus + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:T{$index}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "＊重説を当社で作成した場合は原本必要/重説を当社で行った場合は重説前に謄本取得（以下に手書き表示）")
        ;

        //10
        $index = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("D{$index}:E{$index}")
                ->mergeCells("H{$index}:I{$index}")
                ->mergeCells("J{$index}:K{$index}")
                ->mergeCells("L{$index}:M{$index}")
                ->mergeCells("N{$index}:P{$index}")
                ->mergeCells("Q{$index}:S{$index}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C{$index}", "重説")
                ->setCellValue("D{$index}", "当社･他社")
                ->setCellValue("H{$index}", "経理検印")
                ->setCellValue("J{$index}", "経理検印")
                ->setCellValue("L{$index}", "経理検印")
                ->setCellValue("N{$index}", "店舗経理印")
                ->setCellValue("Q{$index}", "店長検印")
        ;

        //11
        $index = $index + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("D{$index}:E{$index}")
                ->mergeCells("H{$index}:I{$plus}")
                ->mergeCells("J{$index}:K{$plus}")
                ->mergeCells("L{$index}:M{$plus}")
                ->mergeCells("N{$index}:P{$plus}")
                ->mergeCells("Q{$index}:S{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C{$index}", "原本")
                ->setCellValue("D{$index}", "必要・不要")
        ;

        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function exportPage1($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.*,ag.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_agent AS ag ON ag.id = u.agent_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = @date('d/m/Y');
        $order_date = @date('d/m/Y', $row['order_day_create']);

        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "申込報告書";

        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        );
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        
        //Top
        $objPHPExcel->getActiveSheet()->getStyle("A2:I35")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("A37:D42")->applyFromArray($border);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:C1")
                ->mergeCells("G1:H1")
                ->mergeCells("A2:A7")
                
                ->mergeCells("B2:B3")
                ->mergeCells("C2:I3")
                
                ->mergeCells("B4:B5")
                ->mergeCells("C4:I5")
                
                ->mergeCells("B6:B7")
                ->mergeCells("C6:I7")
                
                ->mergeCells("A8:B9")
                ->mergeCells("C8:I9")
                ->mergeCells("A10:B11")
                ->mergeCells("C10:I11")
                ->mergeCells("A12:B13")
                ->mergeCells("C12:F13")
                ->mergeCells("G12:G13")
                ->mergeCells("H12:I13")
                
                ->mergeCells("A14:B15")
                ->mergeCells("C14:I15")
                
                ->mergeCells("A16:B17")
                ->mergeCells("C16:I17")
                ->mergeCells("A18:B19")
                ->mergeCells("C18:F19")
                ->mergeCells("G18:G19")
                ->mergeCells("H18:I19")
                ->mergeCells("A20:A27")
                ->mergeCells("B20:C21")
                ->mergeCells("D20:I21")
                ->mergeCells("B22:C23")
                ->mergeCells("D22:I23")
                ->mergeCells("B24:C25")
                ->mergeCells("D24:I25")
                ->mergeCells("B26:C27")
                ->mergeCells("D26:I27")
                ->mergeCells("A28:A31")
                ->mergeCells("B28:C29")
                ->mergeCells("D28:I29")
                ->mergeCells("B30:C31")
                ->mergeCells("D30:I31")
                ->mergeCells("A32:A35")
                ->mergeCells("B32:I35")
                ->mergeCells("A37:A38")
                ->mergeCells("B37:B38")
                ->mergeCells("C37:C38")
                ->mergeCells("D37:D38")
                ->mergeCells("A39:A42")
                ->mergeCells("B39:B42")
                ->mergeCells("C39:C42")
                ->mergeCells("D39:D42")
        ;
        $house = new HOMEHouse();
        if ($house->isSerialized($row['client_address'])) {
            $house_address_serialize = unserialize($row['client_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = $house_address_serialize['client_address'];
            $row['client_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        if ($house->isSerialized($row['house_address'])) {
            $house_address_serialize = unserialize($row['house_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = $house_address_serialize['house_address'];
            $row['house_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", $row['agent_name'])
                ->setCellValue("D1", "店")
                ->setCellValue("F1", "担当者")
                ->setCellValue("G1", $row['user_lname'].' '.$row['user_fname'])//
                //**************
                ->setCellValue("A2", "借主")
                ->setCellValue("B2", "契約者")
                ->setCellValue("C2", $row['client_name']) //
                ->setCellValue("B4", "現住所")
                ->setCellValue("C4", $row['client_address']) //
                ->setCellValue("B6", "ＴＥＬ")
                ->setCellValue("C6", $row['client_phone']) //
                //**************
                ->setCellValue("A8", "申込年月日")
                ->setCellValue("C8", @date('d/m/Y',$row['contract_application_date'])) //
                ->setCellValue("A10", "契約予定日")
                ->setCellValue("C10", @date('d/m/Y',$row['contract_signature_day'])) //
                ->setCellValue("A12", "元付業者")
                ->setCellValue("C12", $row['broker_company_name']) //
                ->setCellValue("G12", "担当者")
                ->setCellValue("H12", "") //
                ->setCellValue("A14", "ＴＥＬ")
                ->setCellValue("C14", $row['broker_company_phone']) //
                ->setCellValue("A16", "物件所在地")
                ->setCellValue("C16", $row['house_address']) //
                ->setCellValue("A18", "物件名称")
                ->setCellValue("C18", $row['house_name']) //
                ->setCellValue("G18", "号室")
                ->setCellValue("H18", $row['room_number']) //
                //************
                ->setCellValue("A20", "賃貸条件")
                ->setCellValue("B20", "賃料")
                ->setCellValue("D20", $row['contract_cost'].' 円') //
                ->setCellValue("B22", "管理費")
                ->setCellValue("D22", rtrim($row['room_administrative_expense'],'円').' 円') //
                ->setCellValue("B24", "保証金")
                ->setCellValue("D24", $row['contract_deposit_1'].' 円') //
                ->setCellValue("B26", "償却")
                ->setCellValue("D26", $row['contract_deposit_2'].' 円') //
                //**************
                ->setCellValue("A28", "取引報酬")
                ->setCellValue("B28", "仲介手数料")
                ->setCellValue("D28", $row['contract_broker_fee'].' 円') //
                ->setCellValue("B30", "業務委託料")
                ->setCellValue("D30", "") //
                //**************
                ->setCellValue("A32", "備考")
                ->setCellValue("B32", "") //
                //**************
                ->setCellValue("A37", "店長")
                ->setCellValue("B37", "") //
                ->setCellValue("C37", "経理")
                ->setCellValue("D37", "") //
        ;

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function exportPage2($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.*,ag.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    LEFT JOIN home_agent AS ag ON ag.id = u.agent_id
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "契 約 金 精 算 書";

        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        );
        $border_double = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                )
            )
        );

        $greenColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'CCFFCC')
            )
        );
        $yellowColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFF99')
            )
        );

        $redColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF99CC')
            )
        );
        $greyColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '969696')
            ),
            'font'  => array(
                'color'  => array('rgb' => 'FFFFFF')
            )
        );
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $bigfont = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 18,
                'underline' => true
            ));
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        //add Style
        $objPHPExcel->getActiveSheet()->getStyle("I1")->applyFromArray($greyColor);
        $objPHPExcel->getActiveSheet()->getStyle("A5:G6")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("A8:I9")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("B8:D9")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("F8:F9")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("H8:I9")->applyFromArray($border_double);
        
        $objPHPExcel->getActiveSheet()->getStyle("A11:I11")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("C11")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("F11")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("H11:I11")->applyFromArray($border_double);
        
        $objPHPExcel->getActiveSheet()->getStyle("F13:I13")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("G13")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("I13")->applyFromArray($border_double);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F5")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B8")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C11")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F11")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I11")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("G13")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I13")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle("A15:A18")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A26:D28")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A30")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A43")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($bigfont);
        $objPHPExcel->getActiveSheet()->getStyle("B5")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("F5")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("B8")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("B15:B18")->getFont()->setSize(8);
        
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(8.25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        
        $objPHPExcel->getActiveSheet()->getStyle("A13:D28")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("F17:I28")->applyFromArray($border);
        
        $objPHPExcel->getActiveSheet()->getStyle("C26")->applyFromArray($yellowColor);
        $objPHPExcel->getActiveSheet()->getStyle("C27")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("C28")->applyFromArray($yellowColor);
        $objPHPExcel->getActiveSheet()->getStyle("A30")->applyFromArray($redColor);
        
        $objPHPExcel->getActiveSheet()->getStyle("G38:I41")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("A30:D34")->applyFromArray($border);
        
        //mergeCells
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:G1")
                ->mergeCells("A2:C3")
                ->mergeCells("F2:I2")
                ->mergeCells("F3:I3")
                ->mergeCells("A5:A6")
                ->mergeCells("B5:E6")
                ->mergeCells("F5:F6")
                ->mergeCells("G5:G6")
                ->mergeCells("A8:A9")
                ->mergeCells("B8:D9")
                ->mergeCells("E8:E9")
                ->mergeCells("F8:F9")
                ->mergeCells("G8:G9")
                ->mergeCells("H8:I9")
                ->mergeCells("A11:B11")
                ->mergeCells("D11:E11")
                ->mergeCells("G11:H11")
                ->mergeCells("A13:B13")
                ->mergeCells("C13:D13")
                ->mergeCells("A14:B14")
                ->mergeCells("C14:D14")
                ->mergeCells("F14:I14")
                ->mergeCells("F15:I15")
                ->mergeCells("F16:I16")

        ;
        for ($i = 13;$i <= 28 ; $i ++) {
            $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:D{$i}");
            if($i < 15 || $i > 18){
                $objPHPExcel->getActiveSheet()->mergeCells("A{$i}:B{$i}");
            }
            if($i >= 17){
                $objPHPExcel->getActiveSheet()->mergeCells("G{$i}:I{$i}");
            }
        }
        //Values
        //date time
        $date2 = strtotime('+1 month', $row['contract_period_from']) ;
        $daysleft1 = cal_days_in_month(CAL_GREGORIAN, date('m',$row['contract_period_from']), date('Y',$row['contract_period_from'])) -  date('d',$row['contract_period_from']) + 1;
        $daysleft2 = cal_days_in_month(CAL_GREGORIAN, date('m',$date2), date('Y',$date2));

        $cost1 = round($row['contract_cost'] * $daysleft1/cal_days_in_month(CAL_GREGORIAN, date('m',$row['contract_period_from']), date('Y',$row['contract_period_from'])));
        $fee1 = 0;//round(0 * $daysleft1/cal_days_in_month(CAL_GREGORIAN, date_format($date,'m'), date_format($date,'Y')));
        $cost2 = $row['contract_cost'];
        $fee2 = 0;
                
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", "契 約 金 精 算 書")
                ->setCellValue("I1", "貸主様用")
                ->setCellValue("F2", "【契約予定日】")
                //**************
                ->setCellValue("A2", $row['broker_company_name'])
                ->setCellValue("D3", "様")
                ->setCellValue("F3", @date('d/m/Y',$row['contract_signature_day']))//
                //**************
                ->setCellValue("A5", "物件名")
                ->setCellValue("B5", $row['house_name']) //
                ->setCellValue("F5", $row['room_number']) //
                ->setCellValue("G5", "号　室")
                ->setCellValue("A8", "月額賃料")
                ->setCellValue("B8", '¥'.number_format($row['contract_cost'])) //
                ->setCellValue("E8", "共益（管理）費")
                ->setCellValue("F8", '¥'.number_format((float)$row['room_administrative_expense'])) //
                ->setCellValue("G8", "駐車場")
                ->setCellValue("H8", '無') //select box
                ->setCellValue("A11", "敷金（ヶ月）")
                ->setCellValue("C11", (float)($row['contract_deposit_1']/$row['contract_cost'])) //
                ->setCellValue("D11", "礼金（ヶ月）")
                ->setCellValue("F11", '¥'.number_format((float)$row['contract_key_money'])) //
                ->setCellValue("G11", "保証金")
                ->setCellValue("I11", (float)($row['contract_deposit_2']/$row['contract_cost'])) //
                ->setCellValue("A13", "敷 金")
                ->setCellValue("C13", '¥'.number_format($row['contract_deposit_1'])) //
                ->setCellValue("F13", "月日数")
                ->setCellValue("G13", $daysleft2) //
                ->setCellValue("H13", "日割日数")
                ->setCellValue("I13", $daysleft1) //
                ->setCellValue("A14", "保　証　金")
                ->setCellValue("C14", '¥'.number_format($row['contract_deposit_2'])) //
                ->setCellValue("F14", '※'.(date('Y',$row['contract_period_from'])).'年'.date('m',$row['contract_period_from']).'月'.date('d',$row['contract_period_from']).'日より、契約開始') //
                ->setCellValue("A15", date('m',$row['contract_period_from']))
                ->setCellValue("B15", "月分 日割家賃")
                ->setCellValue("C15", '¥'.number_format($cost1))
                ->setCellValue("A16", date('m',$row['contract_period_from']))
                ->setCellValue("B16", "月分 日割共益費")
                ->setCellValue("C16", '¥'.number_format($fee1))
                ->setCellValue("A17", date('m',$date2))
                ->setCellValue("B17", "月分 日割家賃")
                ->setCellValue("C17", '¥'.number_format($row['contract_cost']))
                ->setCellValue("A18", date('m',$date2))
                ->setCellValue("B18", "月分 日割共益費")
                ->setCellValue("C18", '¥'.number_format(0))
                ->setCellValue("F16", "【貸主様への提出書類】")
        ;


        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A26", "合 計")
                ->setCellValue("C26", '¥'.number_format($total = $row['contract_deposit_1'] + $row['contract_deposit_2'] + $cost1 + $cost2 + $fee1 + $fee2))//
                ->setCellValue("A27", "広告料+仲介手数料")
                ->setCellValue("C27", '¥'.number_format($fee = (float)($row['contract_ads_fee'] + $row['contract_broker_fee'])))//
                ->setCellValue("A28", "業務委託料、差引合計")
                ->setCellValue("C28", '¥'.number_format($total - $fee))//
        ;
        
        

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A30:D30")
                ->mergeCells("A31:D31")
                ->mergeCells("A32:D32")
                ->mergeCells("A33:D33")
                ->mergeCells("A34:D34")
                ->mergeCells("F30:I30")
                ->mergeCells("F31:I31")
                ->mergeCells("F32:I32")
                ->mergeCells("F33:I33")
                ->mergeCells("F34:I34")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A30", "精算金、お振込先")//
                ->setCellValue("A31", "")
                ->setCellValue("A32", "")
                ->setCellValue("F32", "【お問い合わせ先】")
                ->setCellValue("A33", "")
                ->setCellValue("F33", "株式会社　アンビション・ルームピア")
        ;
                //Bottom Right
        $objPHPExcel->getActiveSheet()
                ->mergeCells("F36:I36")
                ->mergeCells("G37:I37")
                ->mergeCells("G39:G41")
                ->mergeCells("H39:H41")
                ->mergeCells("I39:I41")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("F36", "（ＴＥＬ）{$row['agent_phone']}")//
                ->setCellValue("G37", "＊検印なきものは無効。")
                ->setCellValue("G38", "店長")
                ->setCellValue("H38", "経理")
                ->setCellValue("I38", "担当")
        ;
        //footer
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A43:I43")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A43", "※弊社は事故防止のため、現金でのお届けは行っておりません。すべてお振込みとなります。")//
        ;
                
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    public function exportPage3($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.*,ag.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    LEFT JOIN home_agent AS ag ON ag.id = u.agent_id
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "精算書入居者用";

        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        );
        $border_double = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                )
            )
        );

        $greenColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'CCFFCC')
            )
        );
        $yellowColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFF99')
            )
        );

        $redColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF99CC')
            )
        );
        $greyColor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '969696')
            ),
            'font'  => array(
                'color'  => array('rgb' => 'FFFFFF')
            )
        );
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $bigfont = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 18,
                'underline' => true
            ));
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        //add Style
        $objPHPExcel->getActiveSheet()->getStyle("I1")->applyFromArray($greyColor);
        $objPHPExcel->getActiveSheet()->getStyle("A5:G6")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("A8:I9")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("B8:D9")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("F8:F9")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("H8:I9")->applyFromArray($border_double);
        
        $objPHPExcel->getActiveSheet()->getStyle("A11:I11")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("C11")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("F11")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("H11:I11")->applyFromArray($border_double);
        
        $objPHPExcel->getActiveSheet()->getStyle("F13:I13")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("G13")->applyFromArray($border_double);
        $objPHPExcel->getActiveSheet()->getStyle("I13")->applyFromArray($border_double);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F5")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B8")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C11")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F11")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I11")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("G13")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("I13")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle("A15:A18")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A25:D28")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("F30:I34")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A30")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A43")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($bigfont);
        $objPHPExcel->getActiveSheet()->getStyle("B5")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("F5")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("B8")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("B15:B18")->getFont()->setSize(8);
        
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(8.20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        
        $objPHPExcel->getActiveSheet()->getStyle("A13:D28")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("F17:I28")->applyFromArray($border);
        
        $objPHPExcel->getActiveSheet()->getStyle("C25")->applyFromArray($yellowColor);
        $objPHPExcel->getActiveSheet()->getStyle("C27")->applyFromArray($greenColor);
        $objPHPExcel->getActiveSheet()->getStyle("C28")->applyFromArray($yellowColor);
        $objPHPExcel->getActiveSheet()->getStyle("A30")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("F30")->applyFromArray($redColor);
        $objPHPExcel->getActiveSheet()->getStyle("F31")->applyFromArray($redColor);
        
        $objPHPExcel->getActiveSheet()->getStyle("G38:I43")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("A30:D34")->applyFromArray($border);
        
        //mergeCells
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:G1")
                ->mergeCells("A2:C3")
                ->mergeCells("F2:I2")
                ->mergeCells("F3:I3")
                ->mergeCells("A5:A6")
                ->mergeCells("B5:E6")
                ->mergeCells("F5:F6")
                ->mergeCells("G5:G6")
                ->mergeCells("A8:A9")
                ->mergeCells("B8:D9")
                ->mergeCells("E8:E9")
                ->mergeCells("F8:F9")
                ->mergeCells("G8:G9")
                ->mergeCells("H8:I9")
                ->mergeCells("A11:B11")
                ->mergeCells("D11:E11")
                ->mergeCells("G11:H11")
                ->mergeCells("A13:B13")
                ->mergeCells("C13:D13")
                ->mergeCells("A14:B14")
                ->mergeCells("C14:D14")
                ->mergeCells("F14:I14")
                ->mergeCells("F15:I15")
                ->mergeCells("F16:I16")

        ;
        for ($i = 13;$i <= 28 ; $i ++) {
            $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:D{$i}");
            if($i < 15 || $i > 18){
                $objPHPExcel->getActiveSheet()->mergeCells("A{$i}:B{$i}");
            }
            if($i >= 17){
                $objPHPExcel->getActiveSheet()->mergeCells("G{$i}:I{$i}");
            }
        }
        //Values
        //date time
        $date = $row['contract_period_from'];
        $date2 = strtotime('+1 month', $date) ;
        
        $daysleft1 = cal_days_in_month(CAL_GREGORIAN, date('m',$date), date('Y',$date)) -  date('d',$date) + 1;
        $daysleft2 = cal_days_in_month(CAL_GREGORIAN, date('m',$date2), date('Y',$date2));

        $cost1 = round($row['contract_cost'] * $daysleft1/cal_days_in_month(CAL_GREGORIAN, date('m',$date), date('Y',$date)));
        $fee1 = 0;//round(0 * $daysleft1/cal_days_in_month(CAL_GREGORIAN, date_format($date,'m'), date_format($date,'Y')));
        $cost2 = $row['contract_cost'];
        $fee2 = 0;
                
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", "契 約 金 明 細 書 (□参考■確定)")
                ->setCellValue("I1", "契約者様用")
                ->setCellValue("F2", "【契約予定日】")
                //**************
                ->setCellValue("A2", $row['client_name'])
                ->setCellValue("D3", "様")
                ->setCellValue("F3", date('d/m/Y',$row['contract_signature_day']))//
                //**************
                ->setCellValue("A5", "物件名")
                ->setCellValue("B5", $row['house_name']) //
                ->setCellValue("F5", $row['room_number']) //
                ->setCellValue("G5", "号 室")
                ->setCellValue("A8", "月額賃料")
                ->setCellValue("B8", '¥'.number_format($row['contract_cost'])) //
                ->setCellValue("E8", "共益（管理）費")
                ->setCellValue("F8", '¥'.number_format((float)$row['room_administrative_expense'])) //
                ->setCellValue("G8", " ")
                ->setCellValue("H8", ' ') 
                ->setCellValue("A11", "敷金(ヶ月)")
                ->setCellValue("C11", (float)($row['contract_deposit_1']/$row['contract_cost'])) //
                ->setCellValue("D11", "礼金(ヶ月)")
                ->setCellValue("F11", '¥'.number_format((float)$row['contract_key_money'])) //
                ->setCellValue("G11", "保証金(ヶ月)")
                ->setCellValue("I11", (float)($row['contract_deposit_2']/$row['contract_cost'])) //
                ->setCellValue("A13", "敷 金")
                ->setCellValue("C13", '¥'.number_format($row['contract_deposit_1'])) //
                ->setCellValue("F13", "月日数")
                ->setCellValue("G13", $daysleft2) //
                ->setCellValue("H13", "日割日数")
                ->setCellValue("I13", $daysleft1) //
                ->setCellValue("A14", "礼 金")
                ->setCellValue("C14", '¥'.number_format($row['contract_deposit_2'])) //
                ->setCellValue("F14", '※'.(date('Y',$date)).'年'.date('m',$date).'月'.date('d',$date).'日より、契約開始') //
                ->setCellValue("A15", date('m',$date))
                ->setCellValue("B15", "月分 日割家賃")
                ->setCellValue("C15", '¥'.number_format($cost1))
                ->setCellValue("A16", date('m',$date))
                ->setCellValue("B16", "月分 日割共益費")
                ->setCellValue("C16", '¥'.number_format($fee1))
                ->setCellValue("A17", date('m',$date2))
                ->setCellValue("B17", "月分 日割家賃")
                ->setCellValue("C17", '¥'.number_format($row['contract_cost']))
                ->setCellValue("A18", date('m',$date2))
                ->setCellValue("B18", "月分 日割共益費")
                ->setCellValue("C18", '¥'.number_format(0))
                ->setCellValue("F16", "【契約時必要書類（追加の場合あり）】")
        ;


        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A25", "仲介手数料（税込）")
                ->setCellValue("C25", '¥'.number_format($row['contract_broker_fee']))//
                ->setCellValue("A26", "契約金合計")
                ->setCellValue("C26", '¥'.number_format($total = $row['contract_deposit_1'] + $row['contract_deposit_2'] + $cost1 + $cost2 + $fee1 + $fee2))//
                ->setCellValue("A27", "申込時、お預かり金")
                ->setCellValue("C27", '¥0')//
                ->setCellValue("A28", "お申込金を差し引いた契約残金")
                ->setCellValue("C28", '¥'.number_format($total))//
        ;
        
        

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A30:D30")
                ->mergeCells("A31:D31")
                ->mergeCells("A32:D32")
                ->mergeCells("A33:D33")
                ->mergeCells("A34:D34")
                ->mergeCells("F30:I30")
                ->mergeCells("F31:I31")
                ->mergeCells("F32:I33")
                ->mergeCells("F34:I34")
                ->mergeCells("F36:I36")
                ->mergeCells("F37:I38")
                ->mergeCells("F39:I39")
                ->mergeCells("G41:G43")
                ->mergeCells("H41:H43")
                ->mergeCells("I41:I43")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A30", "申込金、契約金のお振込先")//
                ->setCellValue("F30", "※お振込期限をご確認ください。")
                ->setCellValue("A31", "")
                ->setCellValue("F31", date('Y\年m\月d\日\ま\で',$date))
                ->setCellValue("A32", "")
                ->setCellValue("F32", "※弊社は事故防止のため、現金でのお預かりは固くお断り申し上げます。")
                ->setCellValue("A33", "")
                ->setCellValue("F34", "※振込手数料はお客様負担にてお願い致します。")
                ->setCellValue("F36", "【仲介会社】")
                ->setCellValue("F37", "株式会社アンビション・ルームピア")
                ->setCellValue("F39", "＊検印なきものは無効。")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("G40", "店長")
                ->setCellValue("H40", "経理")
                ->setCellValue("I40", "担当")
        ;
                
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    public function exportPage4($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "申込報告書";

        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        );
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1:I15")->applyFromArray($style);
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(11);
        
        
        //Top
        $objPHPExcel->getActiveSheet()->getStyle("A5:C5")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("A11:I15")->applyFromArray($border);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:I1")
                ->mergeCells("A5:C5")
                ->mergeCells("A7:I7")
                ->mergeCells("A8:I8")
                
                ->mergeCells("A11:B11")
                ->mergeCells("A12:B12")
                ->mergeCells("C11:C12")
                ->mergeCells("D11:F12")
                ->mergeCells("G11:G12")
                ->mergeCells("H11:I12")
                ->mergeCells("A13:B13")
                ->mergeCells("A14:B15")
                ->mergeCells("C13:I13")
                ->mergeCells("C14:E14")
                ->mergeCells("F14:I14")
                ->mergeCells("C15:E15")
                ->mergeCells("F15:I15")
                
                ->mergeCells("A21:I21")
                ->mergeCells("A22:I22")
                ->mergeCells("A23:I24")
                ->mergeCells("A25:I26")
                ->mergeCells("A27:I27")
                ->mergeCells("A28:I28")
                ->mergeCells("A29:I29")
                ->mergeCells("A30:I30")
                ->mergeCells("A31:I31")
                ->mergeCells("A32:I32")
                ->mergeCells("A33:I33")
                ->mergeCells("A34:I34")
                ->mergeCells("A35:I36")
                ->mergeCells("A37:I37")
                ->mergeCells("A38:I40")
                ->mergeCells("A41:I41")
                ->mergeCells("A42:I43")
                ->mergeCells("A44:I44")
                ->mergeCells("A45:I45")
                ->mergeCells("A49:I49")

        ;
        //Value
        $date = $row['contract_period_from'];
        $date2 = strtotime('+1 month', $date) ;
        
        $daysleft1 = cal_days_in_month(CAL_GREGORIAN, date('m',$date), date('Y',$date)) -  date('d',$date) + 1;
        $daysleft2 = cal_days_in_month(CAL_GREGORIAN, date('m',$date2), date('Y',$date2));
        
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", "入居者対応補助業務発注申込書")
                ->setCellValue("F3", "申込日")
                ->setCellValue("G3",date('Y').'年')//
                ->setCellValue("H3",date('m\月'))//
                ->setCellValue("I3",date('d\日'))//
                ->setCellValue("A5", "株式会社アンビション・ルームピア 御中")
                ->setCellValue("A7", "弊社はこの申込書により、以下に記載する対象建物（１）物件について、本書に定める")
                ->setCellValue("A8", '入居対応補助業務（以下「本業務」という）の実施を貴社に発注申し込みます。')
                //**************
                ->setCellValue("A11", "現住所")
                ->setCellValue("A12", '（以下「本建物」という）')
                ->setCellValue("C11", "物件名")
                ->setCellValue("D11", $row['house_name'])//
                ->setCellValue("G11", '住居番号')
                ->setCellValue("H11", $row['room_number'])//
                ->setCellValue("A13", '本業務実施機関（２）')
                ->setCellValue("C13", date('Y',$date).date('\年 m\月 d\日\(\対\象\物\件\の\契\約\開\始\日\）',$date))//
                ->setCellValue("A14", '本業務手数料（３）')
                ->setCellValue("C14", '報酬額')
                ->setCellValue("F14", '支払予定日')
                ->setCellValue("C15", '¥'.number_format($row['contract_ads_fee']))
                ->setCellValue("F15", @date('d/m/Y',$row['contract_payment_date_to']))//
                //**************
                ->setCellValue("A20", "発注内容")
                ->setCellValue("A21", '１．本業務実施期間について')
                ->setCellValue("A22", "　　①本業務の期間は、頭書（２）記載の期間とします。")
                ->setCellValue("A23", '　　②本業務期間中に弊社と、本貸室の賃借人（以下「借主」という）との賃貸借契約が解約又は解除された場合は、')
                ->setCellValue("A24", "　　　本業務も同時に終了します。")
                ->setCellValue("A25", '　　③本業務期間中であっても、本貸室が火災・地震その他不可抗力によって甚大なる損害を被り賃貸借契約の')
                ->setCellValue("A26", "　　　続行・住居・使用が不可能となった場合は、本委託業務は終了します。")
                ->setCellValue("A27", "２．依頼する本業務の範囲について") 
                ->setCellValue("A28", "　　①入居時説明：")
                ->setCellValue("A29", '　　　　電気・ガス・水道等の入居時開栓について、借主に連絡先を通知し説明を行う')
                ->setCellValue("A30", "　　②建物設備の苦情の一次対応：")
                ->setCellValue("A31", '　　　　借主から建物・設備等に不具合についての苦情があった場合には、これを聴取し貸主に連絡する') 
                ->setCellValue("A32", "　　③借主からの苦情等の一次対応：")
                ->setCellValue("A33", '　　　　借主又は近隣在住者からの苦情等の申し出があった場合には、これを聴取し貸主に連絡する')
                ->setCellValue("A34", "　　④賃貸借契約に基づく弊社と借主との間の連絡調整：")
                ->setCellValue("A35", '　　　　入居中に、借主から賃貸借契約内容等に関して貸主・借主間の調整が必要な内容の申し出を受けた場合には')
                ->setCellValue("A36", '　　　　これを聴取し貸主に連絡する')
                ->setCellValue("A37", '３．本業務委託料について')
                ->setCellValue("A38", '　　　　弊社は、貴社に対し頭書（２）の期間中の報酬として頭書（３）記載の本業務委託料を一括で支払いします。')
                ->setCellValue("A39", '　　　　なお、頭書（２）の期間満了前に本業務が終了したといえども、貴社に対し実施報酬の返還を求めることは')
                ->setCellValue("A40", '　　　　いたしません。')
                ->setCellValue("A41", '４．発注の中止について')
                ->setCellValue("A42", '　　①貴社が本書に定める義務の履行に関してその本旨に従った履行をしない場合には、弊社より相当の期間を')
                ->setCellValue("A43", '　　　定めて履行を催告し、その期間内に履行がないときは、この発注を中止する場合があります。')
                ->setCellValue("A44", "５．規程外事項について")
                ->setCellValue("A45", "　　　本書に定のない事項は、関係法令・不動産取引の慣行に従い、弊社と貴社で協議の上、決定します。")
                ->setCellValue("A49", '本業務の申込につきまして、対象物件の賃貸借契約後速やかに別紙請求書により、弊社宛請求をお願いします。')
                ->setCellValue("B52", "社名")
                ->setCellValue("B55", '住所')
                ->setCellValue("I52", "ご署名")
                ->setCellValue("I53", 'ご捺印の上')
                ->setCellValue("I54", "ＦＡＸ")
                ->setCellValue("I55",'お願いします')
        ;

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function exportPage5($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.*,ag.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    LEFT JOIN home_agent AS ag ON ag.id = u.agent_id
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        $house = new HOMEHouse();
        if ($house->isSerialized($row['agent_address'])) {
            $house_address_serialize = unserialize($row['agent_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['agent_address'];
            $row['agent_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        
        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "請求書";

        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        );
        $border_bold = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                )
            )
        );
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setSize(16);
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $objPHPExcel->getActiveSheet()->getRowDimension('23')->setRowHeight(20);
        
        //Top
        $objPHPExcel->getActiveSheet()->getStyle("A4:F5")->applyFromArray($border_bold);
        $objPHPExcel->getActiveSheet()->getStyle("A15:G19")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("A20:G20")->applyFromArray($border_bold);
        $objPHPExcel->getActiveSheet()->getStyle("E23:H23")->applyFromArray($border_bold);
//        $objPHPExcel->getActiveSheet()->getStyle("B28:E32")->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle("F53:H56")->applyFromArray($border);

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:I1")
                ->mergeCells("A4:F5")
                ->mergeCells("A8:C8")
                ->mergeCells("D10:F10")
                ->mergeCells("D12:F12")
                
                ->mergeCells("A15:C15")
                ->mergeCells("D15:E15")
                ->mergeCells("F15:G15")
                ->mergeCells("A16:C16")
                ->mergeCells("D16:E16")
                ->mergeCells("F16:G16")
                ->mergeCells("A17:C17")
                ->mergeCells("D17:E17")
                ->mergeCells("F17:G17")
                ->mergeCells("A18:C18")
                ->mergeCells("D18:E18")
                ->mergeCells("F18:G18")
                ->mergeCells("A19:C19")
                ->mergeCells("D19:E19")
                ->mergeCells("F19:G19")
                ->mergeCells("A20:C20")
                ->mergeCells("D20:E20")
                ->mergeCells("F20:G20")
                ->mergeCells("A21:C22")
                ->mergeCells("E23:H23")
                ->mergeCells("C23:D23")
                
                ->mergeCells("C31:D31")
                
                ->mergeCells("F44:I44")
                ->mergeCells("F46:I46")
                ->mergeCells("F48:I48")
                ->mergeCells("F50:I50")
                ->mergeCells("F52:H52")
                ->mergeCells("F54:F56")
                ->mergeCells("G54:G56")
                ->mergeCells("H54:H56")
        ;
        //Value
        $date = $row['contract_signature_day'];
        $date2 = strtotime('+1 month', $date) ;
        
        $daysleft1 = cal_days_in_month(CAL_GREGORIAN, date('m',$date), date('Y',$date)) -  date('d',$date) + 1;
        $daysleft2 = cal_days_in_month(CAL_GREGORIAN, date('m',$date2), date('Y',$date2));
        
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", "請求書")
                ->setCellValue("E3", "発行日")
                ->setCellValue("F3",(date('Y')).'年')//
                ->setCellValue("G3",date('m\月'))//
                ->setCellValue("H3",date('d\日'))//
                ->setCellValue("A4", $row['broker_company_name'])
                ->setCellValue("A8", '下記の通りご請求申し上げます。')
                ->setCellValue("B10", "物件名・号室")
                ->setCellValue("D10", $row['house_name'].' '.$row['room_number'])
                ->setCellValue("B12", "賃借人")
                ->setCellValue("D12", $row['client_name'])//
                
                ->setCellValue("A15", '請求内容')
                ->setCellValue("D15", '金額')
                ->setCellValue("F15", '備考')
                ->setCellValue("A16", '広告料として')//
                ->setCellValue("D16", '¥'.number_format($row['contract_ads_fee']))
                ->setCellValue("A20", '合計')
                ->setCellValue("D20", '¥'.number_format($row['contract_ads_fee']))
                //**************
                ->setCellValue("C23", "ご請求金額")
                ->setCellValue("E23", '¥'.number_format($row['contract_ads_fee']))
                ->setCellValue("B28", "振込先")
                ->setCellValue("C30", '普通口座')
                ->setCellValue("C31", '')
                
                ->setCellValue("E44", '住所')
                ->setCellValue("F44", $row['agent_address'])
                ->setCellValue("E46", '社名')
                ->setCellValue("F46", $row['agent_name'])
                ->setCellValue("E48", '電話番号')
                ->setCellValue("F48", $row['agent_phone'])
                ->setCellValue("E50",'担当者')
                ->setCellValue("F50",$row['user_lname'].' '.$row['user_fname'])//
                
                ->setCellValue("F52", '*検印なきものは無効。')
                ->setCellValue("F53", '店長')
                ->setCellValue("G53", '店長')
                ->setCellValue("H53", '担当')
        ;

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function exportPage6($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.*,ag.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    LEFT JOIN home_agent AS ag ON ag.id = u.agent_id
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        $house = new HOMEHouse();
        if ($house->isSerialized($row['house_address'])) {
            $house_address_serialize = unserialize($row['house_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['house_address'];
            $row['house_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        if ($house->isSerialized($row['broker_company_address'])) {
            $house_address_serialize = unserialize($row['broker_company_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['broker_company_address'];
            $row['broker_company_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        
        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "業務委託料";

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:I1")
                ->mergeCells("A5:C5")
                ->mergeCells("A7:G7")
                ->mergeCells("A8:G8")
                
                ->mergeCells("C11:G11")
                ->mergeCells("C14:G14")
                ->mergeCells("C17:G17")
                ->mergeCells("C20:G20")
                ->mergeCells("C23:G23")
                
                ->mergeCells("C32:G32")
                ->mergeCells("C35:G35")
                ->mergeCells("C38:G38")
                
                
        ;
        //Value
        $date = $row['contract_signature_day'];
        
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", "広告宣伝費支払承諾書")
                ->setCellValue("E3", "申込日")
                ->setCellValue("F3",date('Y').'年')//
                ->setCellValue("G3",date('m\月'))//
                ->setCellValue("H3",date('d\日'))//
                ->setCellValue("A5", '株式会社アンビション・ルームピア 御中')
                ->setCellValue("A7", '弊社はこの申込書により、以下に記載する対象建物物件について、本書に定める')
                ->setCellValue("A8", "広告宣伝費を支払う事を承諾致します。")
                
                ->setCellValue("B11", '物件住所')
                ->setCellValue("C11", $row['house_address'])
                ->setCellValue("B14", '物件名')
                ->setCellValue("C14", $row['house_name'])//
                ->setCellValue("B17", '号室')
                ->setCellValue("C17", $row['room_number'])
                ->setCellValue("B20", '広告宣伝費')
                ->setCellValue("C20", $row['contract_ads_fee'])
                ->setCellValue("B23", '支払予定日')
                ->setCellValue("C23", date('m\月   d\日',$date))//
                ->setCellValue("B32", '社名')
                ->setCellValue("C32", $row['broker_company_name'])//
                ->setCellValue("B35", '住所')
                ->setCellValue("C35", $row['broker_company_address'])//
                ->setCellValue("B38", '担当者名')
                ->setCellValue("C38", $row['broker_company_undertake'])//
//                ->setCellValue("C38", $row['user_lname'].' '.$row['user_fname'])//

                //**************
                ->setCellValue("H34", "ご署名")
                ->setCellValue("H35", "ご捺印の上")
                ->setCellValue("H36", "ＦＡＸ")
                ->setCellValue("H37", "お願いします")

        ;

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function exportPage7($order_id = null) {

        global $database;

        $select = " SELECT o.*, c.*, u.*,d.*,h.*,ho.*,bk.*,r.*,rd.*,rt.*,ag.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    LEFT JOIN home_contract AS t ON o.id = t.order_id
                    LEFT JOIN home_contract_detail AS d ON t.id = d.contract_id
                    LEFT JOIN home_house AS h ON h.id = o.house_id
                    LEFT JOIN home_house_owner AS ho ON ho.id = h.house_owner_id
                    LEFT JOIN home_broker_company AS bk ON bk.id = o.broker_id
                    LEFT JOIN home_room AS r ON r.id = o.room_id AND r.house_id = o.house_id
                    LEFT JOIN home_room_detail AS rd ON rd.id = r.room_detail_id
                    LEFT JOIN house_room_type AS rt ON rt.id = rd.room_type
                    LEFT JOIN home_agent AS ag ON ag.id = u.agent_id
                    WHERE o.id = {$order_id}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if (empty($row)) {
            return 0;
        }
        //date_default_timezone_set("Asia/Bangkok");

        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        $house = new HOMEHouse();
        if ($house->isSerialized($row['house_address'])) {
            $house_address_serialize = unserialize($row['house_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['house_address'];
            $row['house_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        if ($house->isSerialized($row['broker_company_address'])) {
            $house_address_serialize = unserialize($row['broker_company_address']);
            $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
            $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
            $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
            $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
            $house_address = @$house_address_serialize['broker_company_address'];
            $row['broker_company_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
        }
        
        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "広告料";

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Brad")
                ->setLastModifiedBy("Brad")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords($title)
                ->setCategory("Report");

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        

        $objPHPExcel->getActiveSheet()
                ->mergeCells("A1:I1")
                ->mergeCells("A5:C5")
                ->mergeCells("A7:G7")
                ->mergeCells("A8:G8")
                
                ->mergeCells("C11:G11")
                ->mergeCells("C14:G14")
                ->mergeCells("C17:G17")
                ->mergeCells("C20:G20")
                ->mergeCells("C23:G23")
                
                ->mergeCells("C32:G32")
                ->mergeCells("C35:G35")
                ->mergeCells("C38:G38")
                
                
        ;
        //Value
        $date = $row['contract_signature_day'];
        
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", "広告料支払承諾書")
                ->setCellValue("E3", "申込日")
                ->setCellValue("F3",date('Y').'年')//
                ->setCellValue("G3",date('m\月'))//
                ->setCellValue("H3",date('d\日'))//
                ->setCellValue("A5", '株式会社アンビション・ルームピア御中')
                ->setCellValue("A7", '弊社はこの申込書により、以下に記載する対象建物物件について、本書に定める')
                ->setCellValue("A8", "広告料を支払う事を承諾致します。")
                
                ->setCellValue("B11", '物件住所')
                ->setCellValue("C11", $row['house_address'])
                ->setCellValue("B14", '物件名')
                ->setCellValue("C14", $row['house_name'])//
                ->setCellValue("B17", '号室')
                ->setCellValue("C17", $row['room_number'])
                ->setCellValue("B20", '広告料')
                ->setCellValue("C20", $row['contract_ads_fee'])
                ->setCellValue("B23", '支払予定日')
                ->setCellValue("C23", date('m\月   d\日',$date))//
                ->setCellValue("B32", '社名')
                ->setCellValue("C32", $row['broker_company_name'])//
                ->setCellValue("B35", '住所')
                ->setCellValue("C35", $row['broker_company_address'])//
                ->setCellValue("B38", '担当者名')
                ->setCellValue("C38", $row['user_lname'].' '.$row['user_fname'])//

                //**************
                ->setCellValue("H34", "ご署名")
                ->setCellValue("H35", "ご捺印の上")
                ->setCellValue("H36", "ＦＡＸ")
                ->setCellValue("H37", "お願いします")

        ;

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$title}.xls'");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 2015 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    /**
     * 
     * @global type $database
     * @return array 
     */
    public function getAllGroups() {
        global $database;
        $select = "SELECT g.* FROM home_group g 
                  ORDER BY g.group_name ASC";
        $result = $database->database_query($select);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }
/**
 * 
 * @global type $database
 * @param type $id
 * @return null
 */
    public function getUsersByGroup($id = null) {
        if (empty($id)) {
            return null;
        }
        global $database;
        /*
          $select = " SELECT u.*,a.*,h.* FROM home_user as u
          INNER JOIN  home_agent as a ON u.agent_id = a.id
          LEFT JOIN home_order as o ON o.user_id = u.id
          LEFT JOIN home_history_log as h ON h.order_id = o.id
          WHERE u.agent_id = {$id}
          ";
         * 
         */
        $select = " SELECT u.* FROM home_user as u
            WHERE u.group_id = {$id}
            ";

        $result = $database->database_query($select);
        $arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }
    public function getGroupInfo($id = null) {
        if (empty($id)) {
            return null;
        }
        global $database;
        $select = "SELECT * FROM home_group WHERE id = '{$id}' LIMIT 1";
        $result = $database->database_query($select);
        return $database->database_fetch_assoc($result);
    }
     private function getRevisit($where = ''){
         global $database;
        //more info on today
        $select = "SELECT count(*) AS revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            INNER JOIN home_broker_company c  ON o.broker_id = c.id
            INNER JOIN home_history_revisit rv  ON rv.history_id = h.id
            WHERE $where
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        return $row[0];
        
     }
}
