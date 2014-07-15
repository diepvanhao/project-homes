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
    public function getUserTarget($user_id = 0,  $todate = null , $fromdate = null){
        global $database;
        if (empty($user_id)) {
            return array();
        }
        if (empty($todate)) {
            $time = time();
        } else {
            $arr = explode('/', $todate);
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }
        $select = "SELECT SUM(t.target) as sum FROM home_user_target AS t 
                 WHERE t.user_id = {$user_id} AND DATE_FORMAT(  t.create_date ,'%Y-%m') <= '" . date('Y-m', $time) . "' AND DATE_FORMAT( t.create_date ,'%Y-%m') >= '" . date('Y-m', $fromtime) . "'";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        return  $row[0];
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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }
        $return = array();

        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";
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
        $select = "SELECT SUM(d.contract_total) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date("Y-m", strtotime(date('Y-m', $time) . " -1 months")) . "'";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['cost_previous_month'] = (int) $row[0];

        //more info on today
        $select = "SELECT SUM(log_shop_sign) AS today_shop_sign, SUM(log_local_sign) AS today_local_sign, SUM(log_introduction) AS today_introduction, SUM(log_tel) AS today_tel, 
            SUM(log_mail) AS today_mail, SUM(log_flyer) AS today_flyer, SUM(log_line) AS today_line, SUM(log_contact_head_office) AS today_contact_head_office,
            SUM(log_tel_status) AS today_tel_status,SUM(log_mail_status) AS today_mail_status, SUM(log_revisit) AS today_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //more info on this month 
        $select = "SELECT SUM(log_shop_sign) AS month_shop_sign, SUM(log_local_sign) AS month_local_sign, SUM(log_introduction) AS month_introduction, SUM(log_tel) AS month_tel, 
            SUM(log_mail) AS month_mail, SUM(log_flyer) AS month_flyer, SUM(log_line) AS month_line, SUM(log_contact_head_office) AS month_contact_head_office,
            SUM(log_tel_status) AS month_tel_status,SUM(log_mail_status) AS month_mail_status, SUM(log_revisit) AS month_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND o.user_id = {$user_id} AND  {$month}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND o.user_id = {$user_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND o.user_id = {$user_id} AND o.order_status = 1 AND {$month} ";
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
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$month} ";
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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }
        $return = array();

        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $year = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y')= '" . date('Y', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m') . "'";
//        $year = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y')= '" . date('Y') . "'";
        /**
         * Mail report
         */
        //track record
        $select = "SELECT SUM(log_shop_sign) AS todaymail_shop_sign, SUM(log_local_sign) AS todaymail_local_sign, SUM(log_introduction) AS todaymail_introduction, SUM(log_tel) AS todaymail_tel, 
            SUM(log_mail) AS todaymail_mail, SUM(log_flyer) AS todaymail_flyer, SUM(log_line) AS todaymail_line, SUM(log_contact_head_office) AS todaymail_contact_head_office,
            SUM(log_tel_status) AS todaymail_tel_status,SUM(log_mail_status) AS todaymail_mail_status, SUM(log_revisit) AS todaymail_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        $select = "SELECT SUM(log_shop_sign) AS yearmail_shop_sign, SUM(log_local_sign) AS yearmail_local_sign, SUM(log_introduction) AS yearmail_introduction, SUM(log_tel) AS yearmail_tel, 
            SUM(log_mail) AS yearmail_mail, SUM(log_flyer) AS yearmail_flyer, SUM(log_line) AS yearmail_line, SUM(log_contact_head_office) AS yearmail_contact_head_office,
            SUM(log_tel_status) AS yearmail_tel_status,SUM(log_mail_status) AS yearmail_mail_status, SUM(log_revisit) AS yearmail_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_mail = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaymail_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaymail_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_mail = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearmail_agreement'] = (int) $row[0];

        /**
         * Phone report
         */
        $select = "SELECT SUM(log_shop_sign) AS todayphone_shop_sign, SUM(log_local_sign) AS todayphone_local_sign, SUM(log_introduction) AS todayphone_introduction, SUM(log_tel) AS todayphone_tel, 
            SUM(log_mail) AS todayphone_mail, SUM(log_flyer) AS todayphone_flyer, SUM(log_line) AS todayphone_line, SUM(log_contact_head_office) AS todayphone_contact_head_office,
            SUM(log_tel_status) AS todayphone_tel_status,SUM(log_mail_status) AS todayphone_mail_status, SUM(log_revisit) AS todayphone_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);
        $select = "SELECT SUM(log_shop_sign) AS yearphone_shop_sign, SUM(log_local_sign) AS yearphone_local_sign, SUM(log_introduction) AS yearphone_introduction, SUM(log_tel) AS yearphone_tel, 
            SUM(log_mail) AS yearphone_mail, SUM(log_flyer) AS yearphone_flyer, SUM(log_line) AS yearphone_line, SUM(log_contact_head_office) AS yearphone_contact_head_office,
            SUM(log_tel_status) AS yearphone_tel_status,SUM(log_mail_status) AS yearphone_mail_status, SUM(log_revisit) AS yearphone_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_tel = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayphone_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayphone_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_tel = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearphone_agreement'] = (int) $row[0];
        /*
         * house discount
         */
        $select = "SELECT SUM(log_shop_sign) AS todaydiscount_shop_sign, SUM(log_local_sign) AS todaydiscount_local_sign, SUM(log_introduction) AS todaydiscount_introduction, SUM(log_tel) AS todaydiscount_tel, 
            SUM(log_mail) AS todaydiscount_mail, SUM(log_flyer) AS todaydiscount_flyer, SUM(log_line) AS todaydiscount_line, SUM(log_contact_head_office) AS todaydiscount_contact_head_office,
            SUM(log_tel_status) AS todaydiscount_tel_status,SUM(log_mail_status) AS todaydiscount_mail_status, SUM(log_revisit) AS todaydiscount_revisit
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

        $select = "SELECT SUM(log_shop_sign) AS yeardiscount_shop_sign, SUM(log_local_sign) AS yeardiscount_local_sign, SUM(log_introduction) AS yeardiscount_introduction, SUM(log_tel) AS yeardiscount_tel, 
            SUM(log_mail) AS yeardiscount_mail, SUM(log_flyer) AS yeardiscount_flyer, SUM(log_line) AS yeardiscount_line, SUM(log_contact_head_office) AS yeardiscount_contact_head_office,
            SUM(log_tel_status) AS yeardiscount_tel_status,SUM(log_mail_status) AS yeardiscount_mail_status, SUM(log_revisit) AS yeardiscount_revisit
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

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_room r  ON r.id = o.room_id AND r.house_id = o.house_id
            INNER JOIN home_room_detail d  ON d.id = r.room_detail_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
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
            WHERE d.contract_application = 1 AND d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE d.room_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaydiscount_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_house hh  ON hh.id = o.house_id
            WHERE hh.house_discount > 0 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yeardiscount_agreement'] = (int) $row[0];
        /*
         * Local sign
         */
        $select = "SELECT SUM(log_shop_sign) AS todaylocalsign_shop_sign, SUM(log_local_sign) AS todaylocalsign_local_sign, SUM(log_introduction) AS todaylocalsign_introduction, SUM(log_tel) AS todaylocalsign_tel, 
            SUM(log_mail) AS todaylocalsign_mail, SUM(log_flyer) AS todaylocalsign_flyer, SUM(log_line) AS todaylocalsign_line, SUM(log_contact_head_office) AS todaylocalsign_contact_head_office,
            SUM(log_tel_status) AS todaylocalsign_tel_status,SUM(log_mail_status) AS todaylocalsign_mail_status, SUM(log_revisit) AS todaylocalsign_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_shop_sign) AS yearlocalsign_shop_sign, SUM(log_local_sign) AS yearlocalsign_local_sign, SUM(log_introduction) AS yearlocalsign_introduction, SUM(log_tel) AS yearlocalsign_tel, 
            SUM(log_mail) AS yearlocalsign_mail, SUM(log_flyer) AS yearlocalsign_flyer, SUM(log_line) AS yearlocalsign_line, SUM(log_contact_head_office) AS yearlocalsign_contact_head_office,
            SUM(log_tel_status) AS yearlocalsign_tel_status,SUM(log_mail_status) AS yearlocalsign_mail_status, SUM(log_revisit) AS yearlocalsign_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_local_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaylocalsign_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todaylocalsign_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_local_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearlocalsign_agreement'] = (int) $row[0];

        /*
         * Introduction
         */
        $select = "SELECT SUM(log_shop_sign) AS todayintroduction_shop_sign, SUM(log_local_sign) AS todayintroduction_local_sign, SUM(log_introduction) AS todayintroduction_introduction, SUM(log_tel) AS todayintroduction_tel, 
            SUM(log_mail) AS todayintroduction_mail, SUM(log_flyer) AS todayintroduction_flyer, SUM(log_line) AS todayintroduction_line, SUM(log_contact_head_office) AS todayintroduction_contact_head_office,
            SUM(log_tel_status) AS todayintroduction_tel_status,SUM(log_mail_status) AS todayintroduction_mail_status, SUM(log_revisit) AS todayintroduction_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_shop_sign) AS yearintroduction_shop_sign, SUM(log_local_sign) AS yearintroduction_local_sign, SUM(log_introduction) AS yearintroduction_introduction, SUM(log_tel) AS yearintroduction_tel, 
            SUM(log_mail) AS yearintroduction_mail, SUM(log_flyer) AS yearintroduction_flyer, SUM(log_line) AS yearintroduction_line, SUM(log_contact_head_office) AS yearintroduction_contact_head_office,
            SUM(log_tel_status) AS yearintroduction_tel_status,SUM(log_mail_status) AS yearintroduction_mail_status, SUM(log_revisit) AS yearintroduction_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_introduction = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayintroduction_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayintroduction_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_introduction = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearintroduction_agreement'] = (int) $row[0];
        /*
         * Shop sign
         */
        $select = "SELECT SUM(log_shop_sign) AS todayshopsign_shop_sign, SUM(log_local_sign) AS todayshopsign_local_sign, SUM(log_introduction) AS todayshopsign_introduction, SUM(log_tel) AS todayshopsign_tel, 
            SUM(log_mail) AS todayshopsign_mail, SUM(log_flyer) AS todayshopsign_flyer, SUM(log_line) AS todayshopsign_line, SUM(log_contact_head_office) AS todayshopsign_contact_head_office,
            SUM(log_tel_status) AS todayshopsign_tel_status,SUM(log_mail_status) AS todayshopsign_mail_status, SUM(log_revisit) AS todayshopsign_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_shop_sign) AS yearshopsign_shop_sign, SUM(log_local_sign) AS yearshopsign_local_sign, SUM(log_introduction) AS yearshopsign_introduction, SUM(log_tel) AS yearshopsign_tel, 
            SUM(log_mail) AS yearshopsign_mail, SUM(log_flyer) AS yearshopsign_flyer, SUM(log_line) AS yearshopsign_line, SUM(log_contact_head_office) AS yearshopsign_contact_head_office,
            SUM(log_tel_status) AS yearshopsign_tel_status,SUM(log_mail_status) AS yearshopsign_mail_status, SUM(log_revisit) AS yearshopsign_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_shop_sign = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayshopsign_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayshopsign_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_shop_sign = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL  AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearshopsign_agreement'] = (int) $row[0];
        /*
         * Log Flyer
         */
        $select = "SELECT SUM(log_shop_sign) AS todayflyer_shop_sign, SUM(log_local_sign) AS todayflyer_local_sign, SUM(log_introduction) AS todayflyer_introduction, SUM(log_tel) AS todayflyer_tel, 
            SUM(log_mail) AS todayflyer_mail, SUM(log_flyer) AS todayflyer_flyer, SUM(log_line) AS todayflyer_line, SUM(log_contact_head_office) AS todayflyer_contact_head_office,
            SUM(log_tel_status) AS todayflyer_tel_status,SUM(log_mail_status) AS todayflyer_mail_status, SUM(log_revisit) AS todayflyer_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_shop_sign) AS yearflyer_shop_sign, SUM(log_local_sign) AS yearflyer_local_sign, SUM(log_introduction) AS yearflyer_introduction, SUM(log_tel) AS yearflyer_tel, 
            SUM(log_mail) AS yearflyer_mail, SUM(log_flyer) AS yearflyer_flyer, SUM(log_line) AS yearflyer_line, SUM(log_contact_head_office) AS yearflyer_contact_head_office,
            SUM(log_tel_status) AS yearflyer_tel_status,SUM(log_mail_status) AS yearflyer_mail_status, SUM(log_revisit) AS yearflyer_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_flyer = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayflyer_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayflyer_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_flyer = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$year} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['yearflyer_agreement'] = (int) $row[0];

        /*
         * Line
         */
        $select = "SELECT SUM(log_shop_sign) AS todayline_shop_sign, SUM(log_local_sign) AS todayline_local_sign, SUM(log_introduction) AS todayline_introduction, SUM(log_tel) AS todayline_tel, 
            SUM(log_mail) AS todayline_mail, SUM(log_flyer) AS todayline_flyer, SUM(log_line) AS todayline_line, SUM(log_contact_head_office) AS todayline_contact_head_office,
            SUM(log_tel_status) AS todayline_tel_status,SUM(log_mail_status) AS todayline_mail_status, SUM(log_revisit) AS todayline_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        $select = "SELECT SUM(log_shop_sign) AS yearline_shop_sign, SUM(log_local_sign) AS yearline_local_sign, SUM(log_introduction) AS yearline_introduction, SUM(log_tel) AS yearline_tel, 
            SUM(log_mail) AS yearline_mail, SUM(log_flyer) AS yearline_flyer, SUM(log_line) AS yearline_line, SUM(log_contact_head_office) AS yearline_contact_head_office,
            SUM(log_tel_status) AS yearline_tel_status,SUM(log_mail_status) AS yearline_mail_status, SUM(log_revisit) AS yearline_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            WHERE h.log_line = 1 AND o.order_status = 1 AND u.agent_id = {$agent_id} AND  {$year}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayline_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND {$year} ";
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
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['todayline_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_user u  ON o.user_id = u.id
            INNER JOIN home_history_log h  ON o.id = h.order_id
            WHERE h.log_line = 1 AND u.agent_id = {$agent_id} AND o.order_status = 1 AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND  {$year} ";
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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }
        $return = array();

//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m',$time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date('Y-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }

        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }
        $return = array();

        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

//        
//        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m') . "'";
//        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date('Y-m') . "'";
        //more info on today
        $select = "SELECT SUM(log_shop_sign) AS today_shop_sign, SUM(log_local_sign) AS today_local_sign, SUM(log_introduction) AS today_introduction, SUM(log_tel) AS today_tel, 
            SUM(log_mail) AS today_mail, SUM(log_flyer) AS today_flyer, SUM(log_line) AS today_line, SUM(log_contact_head_office) AS today_contact_head_office,
            SUM(log_tel_status) AS today_tel_status,SUM(log_mail_status) AS today_mail_status, SUM(log_revisit) AS today_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //more info on this month 
        $select = "SELECT SUM(log_shop_sign) AS month_shop_sign, SUM(log_local_sign) AS month_local_sign, SUM(log_introduction) AS month_introduction, SUM(log_tel) AS month_tel, 
            SUM(log_mail) AS month_mail, SUM(log_flyer) AS month_flyer, SUM(log_line) AS month_line, SUM(log_contact_head_office) AS month_contact_head_office,
            SUM(log_tel_status) AS month_tel_status,SUM(log_mail_status) AS month_mail_status, SUM(log_revisit) AS month_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND  {$month}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //Application
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_broker_company b  ON o.user_id = b.user_id
            INNER JOIN home_contract_detail d ON c.id = d.contract_id
            WHERE d.contract_application = 1 AND o.order_status = 1 AND b.id = {$company_id}  AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_application'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id}  AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_application'] = (int) $row[0];

        //Cancel
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 1 AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_cancel'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 1 AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_cancel'] = (int) $row[0];
        //change
        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND {$today} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_change'] = (int) $row[0];

        $select = "SELECT SUM(o.change) FROM home_order o
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND {$month} ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['month_change'] = (int) $row[0];

        //agreement
        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
            WHERE o.order_status = 1 AND c.id = {$company_id} AND d.contract_cancel = 0 AND  d.contract_signature_day IS NOT NULL AND  d.contract_signature_day <> '' AND {$today} ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_array($result);
        $return['today_agreement'] = (int) $row[0];

        $select = "SELECT COUNT(*) FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON d.contract_id = c.id
            INNER JOIN home_broker_company c  ON o.user_id = c.user_id
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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }

        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";


        //more info on today
        $select = "SELECT SUM(log_shop_sign) AS today_shop_sign, SUM(log_local_sign) AS today_local_sign, SUM(log_introduction) AS today_introduction, SUM(log_tel) AS today_tel, 
            SUM(log_mail) AS today_mail, SUM(log_flyer) AS today_flyer, SUM(log_line) AS today_line, SUM(log_contact_head_office) AS today_contact_head_office,
            SUM(log_tel_status) AS today_tel_status,SUM(log_mail_status) AS today_mail_status, SUM(log_revisit) AS today_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND  {$today}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

        //more info on this month 
        $select = "SELECT SUM(log_shop_sign) AS month_shop_sign, SUM(log_local_sign) AS month_local_sign, SUM(log_introduction) AS month_introduction, SUM(log_tel) AS month_tel, 
            SUM(log_mail) AS month_mail, SUM(log_flyer) AS month_flyer, SUM(log_line) AS month_line, SUM(log_contact_head_office) AS month_contact_head_office,
            SUM(log_tel_status) AS month_tel_status,SUM(log_mail_status) AS month_mail_status, SUM(log_revisit) AS month_revisit
            FROM home_history_log h
            INNER JOIN home_order o  ON o.id = h.order_id
            WHERE o.order_status = 1 AND h.source_id = {$source_id} AND  {$month}
            ";
        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        $return = array_merge($return, $row);

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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }


        $today = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m')= '" . date('Y-d-m', $time) . "'";
        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";
        $return = array(
            'today_already_recorded' => 0.00,
            'today_unsigned' => 0.00,
            'month_already_recorded' => 0.00,
            'month_unsigned' => 0.00,
        );
        //fee today
        $select = "SELECT o.id AS order_id, 
                    o.user_id AS user_id, 
                    d.id AS detail_id,
                    d.contract_broker_fee AS broker_fee, 
                    d.contract_ads_fee AS ads_fee,  
                    d.contract_application AS application, 
                    d.contract_signature_day AS signature_date,
                    d.contract_transaction_finish AS transaction
            FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$today}";

        $result = $database->database_query($select);

        while ($row = $database->database_fetch_assoc($result)) {
            if (empty($row['application'])) {
                continue;
            }
            $select_partner = "SELECT p.partner_percent FROM home_contract_partner AS p
                WHERE p.contract_detail_id = '{$row['detail_id']}' AND p.partner_id = '{$user_id}'";
            $tmp = $database->database_fetch_assoc($database->database_query($select_partner));
            if (!empty($tmp['partner_percent']) && $tmp['partner_percent'] < 100 && $tmp['partner_percent'] > 0) {
                $row['broker_fee'] = $row['broker_fee'] * $tmp['partner_percent'] / 100;
                $row['ads_fee'] = $row['ads_fee'] * $tmp['partner_percent'] / 100;
            }

            if (!empty($row['transaction'])) {
                $return['today_already_recorded'] = $return['today_already_recorded'] + $row['broker_fee'] + $row['ads_fee'];
            } elseif (!empty($row['signature_date'])) {
                $return['today_already_recorded'] = $return['today_already_recorded'] + $row['broker_fee'];
                $return['today_unsigned'] = $return['today_unsigned'] + $row['ads_fee'];
            } else {
                $return['today_unsigned'] = $return['today_unsigned'] + $row['broker_fee'] + $row['ads_fee'];
            }
        }

        //fee of month
        $select = "SELECT o.id AS order_id, 
                    o.user_id AS user_id, 
                    d.id AS detail_id,
                    d.contract_broker_fee AS broker_fee, 
                    d.contract_ads_fee AS ads_fee,  
                    d.contract_application AS application, 
                    d.contract_signature_day AS signature_date,
                    d.contract_transaction_finish AS transaction
            FROM home_order o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  {$month}";

        $result = $database->database_query($select);

        while ($row = $database->database_fetch_assoc($result)) {
            if (empty($row['application'])) {
                continue;
            }
            $select_partner = "SELECT p.partner_percent FROM home_contract_partner AS pgetLastyearInfo
                WHERE p.contract_detail_id = '{$row['detail_id']}' AND p.partner_id = '{$user_id}'";
            $tmp = $database->database_fetch_assoc($database->database_query($select_partner));
            if (!empty($tmp['partner_percent']) && $tmp['partner_percent'] < 100 && $tmp['partner_percent'] > 0) {
                $row['broker_fee'] = $row['broker_fee'] * $tmp['partner_percent'] / 100;
                $row['ads_fee'] = $row['ads_fee'] * $tmp['partner_percent'] / 100;
            }

            if (!empty($row['transaction'])) {
                $return['month_already_recorded'] = $return['month_already_recorded'] + $row['broker_fee'] + $row['ads_fee'];
            } elseif (!empty($row['signature_date'])) {
                $return['month_already_recorded'] = $return['month_already_recorded'] + $row['broker_fee'];
                $return['month_unsigned'] = $return['month_unsigned'] + $row['ads_fee'];
            } else {
                $return['month_unsigned'] = $return['month_unsigned'] + $row['broker_fee'] + $row['ads_fee'];
            }
        }
        return $return;
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
            $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        }
        if (empty($fromdate)) {
            $fromtime = time();
        } else {
            $arr = explode('/', $fromdate);
            $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        }
        $return = array();

        $month = "DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m')= '" . date('Y-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') <= '" . date('Y-d-m', $time) . "' AND DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%d-%m') >= '" . date('Y-d-m', $fromtime) . "'";

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
        
        $select = " SELECT o.*, c.*, u.* FROM home_order AS o 
                    LEFT JOIN home_client AS c ON c.id = o.client_id
                    LEFT JOIN home_user AS u ON u.id = o.user_id
                    WHERE o.id = {$order_id}
            ";

        $result = $database->database_query($select);
        $row = $database->database_fetch_assoc($result);
        if(empty($row)){
            return 0;
        }
        date_default_timezone_set("Asia/Bangkok");
        
        $date = date('d/m/Y');
        $order_date = date('d/m/Y', $row['order_day_create']);

        require_once 'include/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $title = "Order Report";

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
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:C{$index}")
                ->mergeCells("H{$index}:J{$index}")
                ->mergeCells("M{$index}:N{$index}")
                ->mergeCells("O{$index}:R{$index}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Tên chi nhánh")
                ->setCellValue("H{$index}", "Person in charged")
                ->setCellValue("K{$index}", "{$row['user_fname']} {$row['user_lname']}")
                ->setCellValue("L{$index}", "Application date")
                ->setCellValue("M{$index}", "{$order_date}")
        ;

        $index ++;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:B{$index}")
                ->mergeCells("K{$index}:L{$index}")
                ->mergeCells("M{$index}:R{$index}")
        ;
                
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "Mã số sổ cái")
                ->setCellValue("C{$index}", "{$row['id']}")
//                ->setCellValue("D{$index}", "AAA")
                ->setCellValue("K{$index}", "Ngày xuất sổ cái")
                ->setCellValue("M{$index}", "$order_date")
        ;

        $index ++;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:H{$plus}")
                ->mergeCells("K{$index}:L{$index}")
                ->mergeCells("M{$index}:S{$index}")
                ->mergeCells("L{$plus}:Q{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "取引成立台帳（賃貸借）")
                ->setCellValue("K{$index}", "伝票№")
                ->setCellValue("M{$index}", "AAA")
                ->setCellValue("L{$plus}", "Ngày ký hợp đồng")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("C{$plus}:J{$plus}")
                ->mergeCells("L{$index}:Q{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$plus}", "Phiên âm")
                ->setCellValue("C{$plus}", "AAA")
                ->setCellValue("L{$index}", "AAA")
        ;
        //1
        $index = $plus + 1;
        $plus = $index + 6;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "Người thuê")
        ;

        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Người ký hợp đồng")
                ->setCellValue("C{$plus}", "AAA")
                ->setCellValue("K{$index}", "TEL")
                ->setCellValue("L{$index}", "AAA")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Address")
                ->setCellValue("C{$index}", "台東区今戸1-9-6ジェイアムズコート浅草406")
        ;
                
        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("C{$index}:J{$index}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Phiên âm")
                ->setCellValue("C{$index}", "ホンニン")
        ;

        $index = $index + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Người vào ở")
                ->setCellValue("C{$index}", "本人")
                ->setCellValue("K{$index}", "TEL")
                ->setCellValue("L{$index}", "AAA")
        ;

        //2
        $index = $plus + 1;
        $plus = $index + 3;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "Người cho thuê")
        ;

        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Tên nơi ở")
                ->setCellValue("C{$plus}", "東建ビル管理")
                ->setCellValue("K{$index}", "TEL")
                ->setCellValue("L{$index}", "052-232-8020")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Address")
                ->setCellValue("C{$index}", "愛知県名古屋市中区丸の内2-1-33　東建本社丸ノ内ビル13Ｆ")
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
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Công ty quản lý")
                ->setCellValue("C{$index}", "AaAAAAAAAAAA")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:J{$plus}")
                ->mergeCells("K{$index}:K{$plus}")
                ->mergeCells("L{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Người phụ trách")
                ->setCellValue("C{$plus}", "AAA")
                ->setCellValue("K{$index}", "TEL")
                ->setCellValue("L{$index}", "AAA")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Address")
                ->setCellValue("C{$index}", "足立区花畑6-13-13")
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
                ->setCellValue("B{$index}", "Địa chỉ hiện tại")
                ->setCellValue("C{$index}", "台東区元浅草4-2-6")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:N{$plus}")
                ->mergeCells("O{$index}:R{$plus}")
                ->mergeCells("S{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Building Name")
                ->setCellValue("C{$plus}", "AAA")
                ->setCellValue("O{$index}", "AA")
                ->setCellValue("S{$index}", "số phòng")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("B{$index}:B{$plus}")
                ->mergeCells("C{$index}:D{$plus}")
                ->mergeCells("F{$index}:G{$plus}")
                ->mergeCells("H{$index}:H{$plus}")
                ->mergeCells("I{$index}:J{$plus}")
                ->mergeCells("K{$index}:L{$plus}")
                ->mergeCells("M{$index}:O{$plus}")
                ->mergeCells("P{$index}:Q{$plus}")
                ->mergeCells("R{$index}:T{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Diện tích")
                ->setCellValue("C{$index}", "25")
                ->setCellValue("E{$index}", "㎡")
                ->setCellValue("F{$index}", "Loại phòng")
                ->setCellValue("H{$index}", "1")
                ->setCellValue("I{$index}", "K")
                ->setCellValue("K{$index}", "cấu tạo")
                ->setCellValue("M{$index}", "ＲＣ")
                ->setCellValue("P{$index}", "Mục đích sử dụng")
                ->setCellValue("R{$index}", "AAA")
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
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Tiền thuê hàng tháng")
                ->setCellValue("C{$index}", "AaAAAAAAAAAA")
                ->setCellValue("F{$index}", "円")
                ->setCellValue("G{$index}", "Tiền cọc")
                ->setCellValue("I{$index}", "0")
                ->setCellValue("J{$index}", "ヶ月")
                ->setCellValue("K{$index}", "AAA")
                ->setCellValue("N{$index}", "円")
                ->setCellValue("O{$index}", "Thời hạn hợp đồng")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
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
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("B{$index}", "Phí quản lý hàng tháng")
                ->setCellValue("C{$index}", "AaAAAAAAAAAA")
                ->setCellValue("F{$index}", "円")
                ->setCellValue("G{$index}", "tiền lễ")
                ->setCellValue("I{$index}", "0")
                ->setCellValue("J{$index}", "ヶ月")
                ->setCellValue("K{$index}", "AAA")
                ->setCellValue("N{$index}", "円")
                ->setCellValue("O{$index}", "自")
                ->setCellValue("P{$index}", "AAAA")
                ->setCellValue("T{$index}", "～")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
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
                ->setCellValue("B{$index}", "Số người ở")
                ->setCellValue("C{$index}", "1")
                ->setCellValue("D{$index}", "người")
                ->setCellValue("D{$index}", "Hình thức giao dịch")
                ->setCellValue("F{$index}", "仲介")
                ->setCellValue("G{$index}", "Tiền bảo chứng")
                ->setCellValue("I{$index}", "0")
                ->setCellValue("J{$index}", "ヶ月")
                ->setCellValue("K{$index}", "AAA")
                ->setCellValue("N{$index}", "円")
                ->setCellValue("O{$index}", "自")
                ->setCellValue("P{$index}", "AAAA")
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
                ->setCellValue("B{$index}", "thu nhập")
                ->setCellValue("C{$index}", "Chủ nhà")
                ->setCellValue("D{$index}", "AAAA")
                ->setCellValue("G{$index}", "円")
                ->setCellValue("H{$index}", "Thời gian báo hủy hợp đồng")
                ->setCellValue("K{$index}", "AAA")
                ->setCellValue("N{$index}", "trước")
                ->setCellValue("O{$index}", "Trả lại tiền bảo chứng")
                ->setCellValue("S{$index}", "AAAAAA")
        ;

        $index = $plus + 1;
        $plus = $index + 1;
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
                ->setCellValue("B{$index}", "Phí làm thủ tục")
                ->setCellValue("C{$index}", "Người thuê")
                ->setCellValue("D{$index}", "AAAA")
                ->setCellValue("G{$index}", "円")
                ->setCellValue("H{$index}", "cập nhật hợp đồng")
                ->setCellValue("J{$index}", "AAAAA")
                ->setCellValue("L{$index}", "Phí cập nhật")
                ->setCellValue("N{$index}", "trước")
                ->setCellValue("O{$index}", "Tiền  thuê nhà mới")
                ->setCellValue("R{$index}", "AAAAAA")
                ->setCellValue("S{$index}", "ヶ月分")
        ;

        //7
        $index = $plus + 1;
        $plus = $index + 3;
        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
        ;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", "特約・備考")
        ;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("S{$plus}", "AAAA")
                ->setCellValue("T{$plus}", "AAAA")
        ;

        //8
        $index = $plus + 1;
        $plus = $index + 1;
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

}
