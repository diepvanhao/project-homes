<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "class_ajax.php";
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}

extract($_POST);
if (!empty($application) && !empty($transaction) && !empty($order_id)) {
    include('class_mail.php');
    $mail = new Mail();
    echo $mail->order($order_id, $application, $transaction);
} else {
    echo "Cannot send mail! Please save this order and try again!!";
}

