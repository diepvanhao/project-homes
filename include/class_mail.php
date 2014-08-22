<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mail {

    private function _config($smtp = false) {

        date_default_timezone_set("Asia/Bangkok");
        include 'PHPMailer/PHPMailerAutoload.php';

        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = "mail.evolableasia.vn";
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = 25;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = $smtp;
        //Username to use for SMTP authentication
        $mail->Username = "mainv@evolableasia.vn";
        //Password to use for SMTP authentication
        $mail->Password = "Aa123456@";
        
        $mail->CharSet  =  'utf-8';
        
        $mail->IsHTML(true);

        //send the message, check for errors
        return $mail;
    }

    public function broker($from, $to, $subject, $body) {
        $mail = $this->_config(true);
        //Set who the message is to be sent from
        $mail->setFrom($mail->Username);
        //Set an alternative reply-to address
        $mail->addReplyTo($to);
        //Set who the message is to be sent to
        $mail->addAddress($to);
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML($body, dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->AltBody = $body;
        //Attach an image file
//        $mail->addAttachment('images/phpmailer_mini.png');
        if (!$mail->send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return true;
        }
    }

    public function order($order_id = null) {
        if (empty($order_id)) {
            return false;
        }
        global $user;

        include('class_detail.php');
        $detail = new HOMEDetail();
        $order = $detail->getOrder($order_id);
        $agent = HOMEAgent::getAgentByUserId($order['user_id']);
        $order_detail = $this->_getOrderDetail($order_id);
        
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        
        $subject = mb_encode_mimeheader (mb_convert_encoding('[Mail 申込FM]',"UTF-8", "Shift-JIS"));
        
        $body = "<div style='max-width: 1000px; margin: auto;'>
                <div style='width:100%; background-color: #000;'>
                    <img src='http://{$_SERVER['SERVER_NAME']}/include/images/logo.png' title='AMBITION LOGO' alt='AMBITION LOGO' height='150'>
                </div>
                <div>
                    <h3>".mb_convert_encoding($subject, "UTF-8", "Shift-JIS")."</h3>
                    <h3>".mb_convert_encoding('_________________[申込速報]_________________', "UTF-8", "Shift-JIS")."</h3>
                </div>
                <div>
                    <div>".($year-1988).mb_convert_encoding("年{$month}月{$day}日", "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding('おめでとうございます！', "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("只今、{$agent['agent_name']}", "UTF-8", "Shift-JIS")."</div> 
                    <div>".mb_convert_encoding("{$user->user_info['user_fname']} {$user->user_info['user_lname']} さんが申込入りました！！", "UTF-8", "Shift-JIS")."</div> 
                    <div>++++++++++++++++++++</div>
                    <div>".mb_convert_encoding('[内容]', "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("売上済: {$order_detail['already_recorded']} 円", "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("未契約: {$order_detail['unsigned']} 円", "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("申込本数: {$order_detail['is_transaction']} 件", "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("成約本数: {$order_detail['is_unsigned']} 件", "UTF-8", "Shift-JIS")."</div>
                        <br>
                    <div>".mb_convert_encoding('[付帯]', "UTF-8", "Shift-JIS")."</div>
                    NGU
                    <br>
                    <div>".mb_convert_encoding('[井口 紘人さんの総合順位]', "UTF-8", "Shift-JIS")."</div>
                    <br>
                    <br>
                    <div>".mb_convert_encoding('[井口 紘人さん今月累計(達成率)]', "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("売上済: {$order_detail['month_already_recorded']} 円 ", "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding("未契約:  {$order_detail['month_unsigned']}円 ", "UTF-8", "Shift-JIS")."</div>
                    <div>".mb_convert_encoding(">申込本数: {$order_detail['month_transaction']} 件 ", "UTF-8", "Shift-JIS")."</div
                    <div>".mb_convert_encoding("成約本数: {$order_detail['month_unsigned']}件 ", "UTF-8", "Shift-JIS")."</div>
                </div>
            </div>";
        $mail = $this->_config(true);
        //Set who the message is to be sent from
        $mail->setFrom($mail->Username);
        //Set an alternative reply-to address
//        $mail->addReplyTo($user->info['user_email']);
        //Set who the message is to be sent to
        $mail->addAddress($user->user_info['user_email']);
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(mb_convert_encoding($body, "UTF-8", "Shift-JIS"), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->AltBody = $body;
        //Attach an image file
//        $mail->addAttachment('images/phpmailer_mini.png');
        if (!$mail->send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        } else {
            return "Mail Sent";
        }
    }
    
    
    /**
     * 
     * @global type $database
     * @param type $order_id
     * @return null
     */
    private function _getOrderDetail($order_id = NULL) {
        if (empty($order_id)) {
            return null;
        }
        date_default_timezone_set("Asia/Bangkok");
        
        global $database,$user;
        $user_id = $user->user_info['id'];
        
        $return = array(
            'already_recorded' => 0.00,
            'unsigned' => 0.00,
            'is_transaction' => 0,
            'is_unsigned' => 0,
            'month_already_recorded' => 0.00,
            'month_unsigned' => 0.00,
            'month_transaction' => 0,
            'month_unsigned' => 0,
        );
        //this order
        $query = "SELECT o.id AS order_id, 
                    o.user_id AS user_id, 
                    d.id AS detail_id,
                    d.contract_broker_fee AS broker_fee, 
                    d.contract_ads_fee AS ads_fee,  
                    d.contract_application AS application, 
                    d.contract_signature_day AS signature_date,
                    d.contract_transaction_finish AS transaction
            FROM home_order AS o
            INNER JOIN home_contract c  ON o.id = c.order_id
            INNER JOIN home_contract_detail d  ON c.id = d.contract_id
            WHERE o.id = '{$order_id}'
                ";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        if (!empty($row) && !empty($row['application'])) {
            $return['is_transaction'] = (int) $row['transaction'];
            $return['is_unsigned'] = (int) $row['signature_date'];
            if (!empty($row['transaction'])) {
                $return['already_recorded'] = $row['broker_fee'] + $row['ads_fee'];
            } elseif (!empty($row['signature_date'])) {
                $return['already_recorded'] = $row['broker_fee'];
                $return['unsigned'] = $row['ads_fee'];
            } else {
                $return['unsigned'] = $row['broker_fee'] + $row['ads_fee'];
            }
        }
        //month
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
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m') = '". date('Y-m') ."'";

        $result = $database->database_query($select);
        while ($row = $database->database_fetch_assoc($result)) {
            if (empty($row['application'])) {
                continue;
            }
            $return['month_transaction'] += (int) $row['transaction'];
            $return['month_unsigned'] += (int) $row['signature_date'];
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

}
