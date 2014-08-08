<?php

include 'header.php';
$page = 'mail_broker';

include 'include/class_mail.php';
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}
$broker_class = new HOMEBroker();

$id = $_GET['id'];
$post = $_POST;

$broker = $broker_class->getBrokerById($id);

$from = $user->user_info['user_email'];
$to = $broker['broker_company_email'];
$subject = '';
$body = '';
$error = array();

if(!empty($post['submit'])){
    $subject = $post['subject'];
    $body = $post['body'];
    $from = $post['from'];
    $to = $post['to'];
    if(empty($from) || empty($to) || empty($subject)){
        $error[] = " From, To, Subject can not be empty!";
    }
    if(empty($error)){
        $mail  = new Mail();
        $message = $mail->broker($from, $to, $subject, $body);
        if($message !== true){
            $error = $message;
        }
    }
    if(!count($error)){
        header("Location: notify.php?content=Email Sent!!!&url_return=manage_broker.php");
    }
}
$smarty->assign('error', $error);
$smarty->assign('from', $from);
$smarty->assign('to', $to);
$smarty->assign('subject', $subject);
$smarty->assign('body', $body);
include 'footer.php';
