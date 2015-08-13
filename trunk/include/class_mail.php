<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mail {

    private function _config($smtp = false) {

        include_once 'PHPMailer/PHPMailerAutoload.php';

//        mb_language("japanese");           //言語(日本語)
//        mb_internal_encoding("UTF-8");
        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        //$mail->isSMTP();
        $mail->setLanguage('ja');
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 2;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = "gs824.ggsv.jp";
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = 25;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = $smtp;
        //Username to use for SMTP authentication
        $mail->Username = "bm-info@am-bition.jp";
        //Password to use for SMTP authentication
        $mail->Password = "ambad001";

//        @$mail->CharSet = "iso-2022-jp";    //文字コード設定
        $mail->Encoding = "7bit";
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

    public function communication_client($from, $to, $subject, $body) {
        $mail = $this->_config(false);
        //Set who the message is to be sent from
        $mail->setFrom($from);
        //Set an alternative reply-to address
        //$mail->addReplyTo($to);
        //Set who the message is to be sent to
        $mail->addAddress($to);
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
            return true;
        }
    }

    public function order($order_id = null) {
        if (empty($order_id)) {
            return false;
        }
        global $user;

        include_once 'class_detail.php';
        $detail = new HOMEDetail();
        $order = $detail->getOrder($order_id);
        $agent = HOMEAgent::getAgentByUserId($order['user_id']);
        $order_detail = $this->_getOrderDetail($order_id);

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        mb_language("japanese");           //言語(日本語)
        mb_internal_encoding("UTF-8");

        $subject = mb_encode_mimeheader(mb_convert_encoding('[Mail 申込FM]', "UTF-8", "UTF-8"));

        $body = "<div style='max-width: 1000px; margin: auto;'>
                <div style='width:100%; background-color: #000;'>
                    <img src='http://{$_SERVER['SERVER_NAME']}/include/images/logo.png' title='AMBITION LOGO' alt='AMBITION LOGO' height='150'>
                </div>
                <div>
                    <h3>[" . mb_convert_encoding("Mail 申込FM", "JIS", "UTF-8") . "]</h3>
                    <h3>_________________[" . mb_convert_encoding("申込速報", "JIS", "UTF-8") . "]_________________</h3>
                </div>
                <div>
                    <div>" . ($year - 1988) . mb_convert_encoding("年{$month}月{$day}日", "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding('おめでとうございます！', "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("只今、{$agent['agent_name']}", "JIS", "UTF-8") . "</div> 
                    <div>" . mb_convert_encoding("{$user->user_info['user_fname']} {$user->user_info['user_lname']} さんが申込入りました！！", "JIS", "UTF-8") . "</div> 
                    <div>++++++++++++++++++++</div>
                    <div>" . mb_convert_encoding('[内容]', "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("売上済: {$order_detail['already_recorded']} 円", "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("未契約: {$order_detail['unsigned']} 円", "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("申込本数: {$order_detail['is_transaction']} 件", "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("成約本数: {$order_detail['is_unsigned']} 件", "JIS", "UTF-8") . "</div>
                        <br>
                    <div>" . mb_convert_encoding('[付帯]', "JIS", "UTF-8") . "</div>
                    NGU
                    <br>
                    <div>" . mb_convert_encoding('[井口 紘人さんの総合順位]', "JIS", "UTF-8") . "</div>
                    <br>
                    <br>
                    <div>" . mb_convert_encoding('[井口 紘人さん今月累計(達成率)]', "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("売上済: {$order_detail['month_already_recorded']} 円 ", "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("未契約:  {$order_detail['month_unsigned']}円 ", "JIS", "UTF-8") . "</div>
                    <div>" . mb_convert_encoding("申込本数: {$order_detail['month_transaction']} 件 ", "JIS", "UTF-8") . "</div
                    <div>" . mb_convert_encoding("成約本数: {$order_detail['month_unsigned']}件 ", "JIS", "UTF-8") . "</div>
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

        global $database, $user;
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
            WHERE o.user_id = {$user_id} AND o.order_status = 1 AND  DATE_FORMAT( FROM_UNIXTIME( o.order_day_create ) ,'%Y-%m') = '" . date('Y-m') . "'";

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

    /**
     * 
     * @global type $user
     * @param type $order_id
     * @return boolean|string
     */
    public function createOrder($order_id = null) {
        if (empty($order_id)) {
            return false;
        }
        try {
            global $user;

            include_once 'class_detail.php';
            $detail = new HOMEDetail();
            $order = $detail->getOrder($order_id);
            $order_detail = $this->_getOrderDetail($order_id);
            $house = new HOMEHouse();
            $housedetail = $house->getHouseById($order['house_id']);
            $client = Client::getClientId($order['client_id']);
            if ($house->isSerialized($housedetail['house_address'])) {
                $house_address_serialize = unserialize($housedetail['house_address']);
                $city_id_filter = $house->getNameCity($house_address_serialize['city_id']);
                $district_id_filter = $house->getNameDistrict($house_address_serialize['district_id']);
                $street_id_filter = $house->getNameStreet($house_address_serialize['street_id']);
                $ward_id_filter = $house->getNameWard($house_address_serialize['ward_id']);
                $house_address = $house_address_serialize['house_address'];
                $housedetail['house_address'] = $city_id_filter . " " . $district_id_filter . " " . $street_id_filter . " " . $ward_id_filter . " " . $house_address;
            }
            $year = date('Y');
            $month = date('m');
            $day = date('d');

            mb_language("japanese");           //言語(日本語)
            mb_internal_encoding("UTF-8");

            $subject = mb_encode_mimeheader(mb_convert_encoding('[ありがとう]', "UTF-8", "UTF-8"));

            $body = "<div style='max-width: 1000px; margin: auto;'>
                    <div style='width:100%; background-color: #000;'>
                        <img src='http://{$_SERVER['SERVER_NAME']}/include/images/logo.png' title='AMBITION LOGO' alt='AMBITION LOGO' height='150'>
                    </div>
                    <div>
                        <h3>_________________[" . mb_convert_encoding("ありがとう", "JIS", "UTF-8") . "]_________________</h3>
                    </div>
                    <div>
                        <div>" . ($year - 1988) . mb_convert_encoding("年{$month}月{$day}日", "JIS", "UTF-8") . "</div>
                        <div>" . mb_convert_encoding("名称: {$housedetail['house_name']}", "JIS", "UTF-8") . "</div>
                        <div>" . mb_convert_encoding("リンク: ", "JIS", "UTF-8")
                    . "<a href='http://{$_SERVER['SERVER_NAME']}/house_detail.php?house_id={$housedetail['id']}'>"
                    . "http://{$_SERVER['SERVER_NAME']}/house_detail.php?house_id={$housedetail['id']}</a></div>
                        <div>" . mb_convert_encoding("住所:  {$housedetail['house_address']}", "JIS", "UTF-8") . "</div>
                        <div>" . mb_convert_encoding("エリア: {$housedetail['house_area']}", "JIS", "UTF-8") . "</div
                        <div>" . mb_convert_encoding("間取り: {$housedetail['house_type']}", "JIS", "UTF-8") . "</div>
                        <div>" . mb_convert_encoding("備考: {$housedetail['house_description']}", "JIS", "UTF-8") . "</div>
                        <div>" . mb_convert_encoding("建物構造: {$housedetail['house_structure']}", "JIS", "UTF-8") . "</div>
                        <div>" . mb_convert_encoding("築年月: {$housedetail['house_build_time']}", "JIS", "UTF-8") . "</div>
                    </div>
                </div>";
            $mail = $this->_config(true);
            $mail->SMTPDebug = 0;
            //Set who the message is to be sent from
            $mail->setFrom($mail->Username);
            //Set an alternative reply-to address
            //        $mail->addReplyTo($user->info['user_email']);
            //Set who the message is to be sent to
            $mail->addAddress($client['client_email']);
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
        } catch (Exception $e) {
            
        }
    }

    /**
     * 
     * @global type $database
     * @param type $email
     * @return type
     */
    public function forgot($email = null) {
        if (empty($email)) {
            return array(
                'status' => false,
                'message' => 'Email can not empty!'
            );
        }
        global $database;

        //get User
        $result = $database->database_query("SELECT * FROM home_user WHERE user_email = '{$email}' LIMIT 1");
        $forgot = $database->database_fetch_assoc($result);
        if (empty($forgot)) {
            return array(
                'status' => false,
                'message' => 'A user account with that email was not found.'
            );
        }
        //Delete old item
        $database->database_query("DELETE FROM home_forgot WHERE user_id = '{$forgot['id']}' ");

        //Insert new row
        $key = rand(10000, 99999);
        $time = date('Y-m-d H:i:s');
        $database->database_query("INSERT INTO home_forgot VALUES('{$forgot['id']}','{$key}','{$time}')");
        //send mail
        $link = "http://{$_SERVER['SERVER_NAME']}/reset.php?id={$forgot['id']}&code={$key}";
        mb_language("japanese");
        mb_internal_encoding("UTF-8");

        $subject = mb_encode_mimeheader(mb_convert_encoding('Forgot password', "UTF-8", "UTF-8"));
        $body = "<div style='max-width: 1000px; margin: auto;'>
                    <div style='width:100%; background-color: #000;'>
                        <img src='http://{$_SERVER['SERVER_NAME']}/include/images/logo.png' title='AMBITION LOGO' alt='AMBITION LOGO' height='150'>
                    </div>
                    <div>
                        Please click <a href='{$link}'>HERE</a>  to reset your password.
                    </div>
                </div>";
        $mail = $this->_config(true);
        //Set who the message is to be sent from
        $mail->setFrom($mail->Username);
        //Set an alternative reply-to address
//        $mail->addReplyTo($user->info['user_email']);
        //Set who the message is to be sent to
        $mail->addAddress($email);
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(mb_convert_encoding($body, "UTF-8", "Shift-JIS"), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->AltBody = $body;
        //Attach an image file
        if (!$mail->send()) {
            return array(
                'status' => false,
                'message' => $mail->ErrorInfo
            );
        } else {
            return array(
                'status' => true,
                'message' => 'You have been sent an email with instructions how to reset your password. If the email does not arrive within several minutes, be sure to check your spam or junk mail folders.'
            );
        }
    }

    /**
     * 
     * @global type $database
     * @param type $id
     * @param type $code
     * @return type
     */
    public function checkCode($id, $code) {
        if (empty($id) || empty($code)) {
            return array(
                'status' => false,
                'message' => 'Empty Code'
            );
        }
        global $database;
        //check
        $result = $database->database_query("SELECT * FROM home_forgot WHERE user_id = '{$id}' AND code = '{$code}' LIMIT 1");
        $code = $database->database_fetch_assoc($result);
        if (empty($code)) {
            return array(
                'status' => false,
                'message' => 'Invalid Code'
            );
        } else {
            return array(
                'status' => true,
                'message' => 'OK'
            );
        }
    }

    public function reset($user_id, $pass = null, $cfpass = null) {
        if (empty($pass) || empty($cfpass) || empty($user_id)) {
            return array(
                'status' => false,
                'message' => 'Password and Confirm Password can not empty!'
            );
        }
        if ($pass !== $cfpass) {
            return array(
                'status' => false,
                'message' => "Password and Confirm Password don't match"
            );
        }
        global $database;

        $userClass = new HOMEUser();

        if ($userClass->reset($user_id, $pass)) {
            //Delete old item
            $database->database_query("DELETE FROM home_forgot WHERE user_id = '{$user_id}' ");
            return array(
                'status' => true,
                'message' => "Your password has been reset. Click <a href='user_login.php'>HERE</a> to sign-in."
            );
        } else {
            return array(
                'status' => false,
                'message' => "Something wrong. Please try again!"
            );
        }
    }

}
