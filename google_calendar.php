<?php

include "header.php";
$page = "google_calendar";
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
echo "<link rel='stylesheet' href='{$url->url_base}include/Calendar/lib/cupertino/jquery-ui.min.css' />";
echo "<link href='{$url->url_base}include/Calendar/fullcalendar.css' rel='stylesheet' />";
echo "<link href='{$url->url_base}include/Calendar/fullcalendar.print.css' rel='stylesheet' media='print' />";
echo "<script src='{$url->url_base}include/Calendar/lib/moment.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lib/jquery.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lib/jquery-ui.custom.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lib/jquery.cookie.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/fullcalendar.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lang-all.js'></script>";
//echo "<script src='{$url->url_base}include/Calendar/fullcalendar.js'></script>";


$user_target=  getTarget($user->user_info['id']);

//fetch events
$order=new HOMEOrder();

$events=$order->fetchEvents($user->user_info['id']);//var_dump($events);die();
$smarty->assign('events',$events);
$smarty->assign('user_target',$user_target);
include "footer.php";

