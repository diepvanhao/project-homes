<?php

include 'header.php';
$page = 'report';


include 'include/class_report.php';
$report = new Report();

$post = $_POST;
extract($post);
if (!empty($post)) {
    $smarty->assign('params', $post);
}
if(!empty($agent_id)){
    $agent = $report->getAgentInfo($agent_id);
    $agent_name = $agent['agent_name'];
}else{
    $agent_name = '';
}
$smarty->assign('users', $report->getUsersByAgent($agent_id));
$smarty->assign('agents', $report->getAllAgents());
$smarty->assign('agent', $report->getAgentInfo($agent_id));
$smarty->assign('agent_name', $agent_name);
$smarty->assign('report', $report);
$smarty->assign('today', array(
    'cost' => 0,
    'unsigned' => 0,
    'signboard' => 0, 
    'introduction' => 0, 
    'tel_status' => 0,
    'mail_status' => 0,
    'track_record' => 0,
    'tel' => 0,
    'mail' => 0,
    'tontact' => 0,
    'revisit' => 0,
    'application' => 0,
    'cancel' => 0,
    'change' => 0,
    'agreement' => 0,
    'done' => 0,
));
$smarty->assign('month', array(
    'target' => 0,
    'cost' => 0,
    'unsigned' => 0,
    'cost_previous' => 0,
    'signboard' => 0, 
    'introduction' => 0, 
    'tel_status' => 0,
    'mail_status' => 0,
    'track_record' => 0,
    'tel' => 0,
    'mail' => 0,
    'tontact' => 0,
    'revisit' => 0,
    'application' => 0,
    'cancel' => 0,
    'change' => 0,
    'agreement' => 0,
    'done' => 0,
));
include 'footer.php';
