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

}
