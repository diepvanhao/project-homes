<?php

$page = "home";
include "header.php";
if (!$user->user_exists) {

    header('Location: ./user_login.php');

    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}
//$orderClass = new HOMEOrder();
//
//$smarty->assign('messages', $orderClass->getHomeMessages($user->user_info['agent_id']));

include 'include/class_report.php';
$report = new Report();
//init date
$d = new DateTime('first day of this month');
$first = $d->format('Y/m/d');
$d = new DateTime('last day of this month');
$last = $d->format('Y/m/d');
//for owner
$user_id = $user->user_info['id'];
$user_revenue = $report->userCommission($user_id, $last, $first);
$smarty->assign('user_revenue', number_format($user_revenue['month_already_recorded'],2).'円');
//for agents
$smarty->assign('agents', $agents = $report->getAllAgents());
$smarty->assign('report', $report);

include "footer.php";
