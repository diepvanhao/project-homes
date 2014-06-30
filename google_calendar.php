<?php

include "header.php";
$page = "google_calendar";
echo "<link rel='stylesheet' href='{$url->url_base}include/Calendar/lib/cupertino/jquery-ui.min.css' />";
echo "<link href='{$url->url_base}include/Calendar/fullcalendar.css' rel='stylesheet' />";
echo "<link href='{$url->url_base}include/Calendar/fullcalendar.print.css' rel='stylesheet' media='print' />";
echo "<script src='{$url->url_base}include/Calendar/lib/moment.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lib/jquery.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lib/jquery-ui.custom.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/fullcalendar.min.js'></script>";
echo "<script src='{$url->url_base}include/Calendar/lang-all.js'></script>";
//echo "<script src='{$url->url_base}include/Calendar/fullcalendar.js'></script>";



//fetch events
$order=new HOMEOrder();

$events=$order->fetchEvents($user->user_info['id']);//var_dump($events);
$smarty->assign('events',$events);
include "footer.php";

