<?php

include 'header.php';
$page = 'report';

date_default_timezone_set("Asia/Bangkok");
include 'include/class_report.php';
$report = new Report();
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
if($user->user_info['user_locked']){
    header('Location: ./locked.php');
    exit();
}

$post = $_POST;
extract($post);
if (!empty($post)) {
    $smarty->assign('params', $post);
}
if (!empty($agent_id)) {
    $agent = $report->getAgentInfo($agent_id);
    $agent_name = $agent['agent_name'];
} else {
    $agent_id = null;
    $agent_name = '';
}
if (empty($date)) {
    $date = null;
}
if (empty($fromdate)) {
    $fromdate = null;
}
$error = array();
if(!empty($post)){
    if(empty($date) || empty($fromdate)){
        $error[] = "「日から」は「日まで」より大きくすることは禁止です。"; 
    }else{
        $arr = explode('/',$date);
        $time = mktime(23, 59, 59, $arr[0], $arr[1], $arr[2]);
        $arr = explode('/',$fromdate);
        $fromtime = mktime(0, 0, 0, $arr[0], $arr[1], $arr[2]);
        if($fromtime > $time){
            $error[] = "「日から」と「日まで」には空にすることは禁止です。"; 
        }
//        if($fromdate > time() || $time > time()){
//            $error[] = "From Date and To Date can not in future"; 
//        }
    }
//    if($user->user_info['user_authorities'] > 2 && $agent_id != $user->user_info['agent_id']){
//        $error[] = "You can not view other agent info"; 
//    }
}
$smarty->assign('date', $date);
$smarty->assign('agent_id', $agent_id);
$smarty->assign('fromdate', $fromdate);
$smarty->assign('error', $error);
$smarty->assign('users', $users = $report->getUsersByAgent($agent_id));
$smarty->assign('agents', $agents = $report->getAllAgents());
$smarty->assign('agent', $agent = $report->getAgentInfo($agent_id));
$smarty->assign('agent_name', $agent_name);
$smarty->assign('report', $report);
$smarty->assign('today', $today = array(
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
    'chart_cost' => 0,
    'chart_target' => 0,
));
$smarty->assign('month', $month = array(
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
if (!empty($post['export']) && empty($error)) {
    require_once 'include/PHPExcel.php';
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    if (empty($date)) {
        $date = date("m/d/Y");
    }
    $title = "Report - {$date}";

    $styleArray = array(
        'borders' => array(
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM
          )
        )
      );
    $index = 2;
// Set document properties
    $objPHPExcel->getProperties()->setCreator("Brad")
            ->setLastModifiedBy("Brad")
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription("Report document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords($title)
            ->setCategory("Report");

    $objPHPExcel->getActiveSheet()
            ->mergeCells("B{$index}:D{$index}")
            ->mergeCells("E{$index}:F{$index}")
            ->mergeCells("G{$index}:I{$index}")
            ->mergeCells("K{$index}:M{$index}")
            ->mergeCells("N{$index}:P{$index}")
            ->mergeCells("Q{$index}:S{$index}")
            ->mergeCells("T{$index}:V{$index}")
    ;
// Add some data
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B{$index}", "氏名")
            ->setCellValue("E{$index}", "役職")
            ->setCellValue("G{$index}", "今月目標")
            ->setCellValue("J{$index}", "計")
            ->setCellValue("K{$index}", "売上済み")
            ->setCellValue("N{$index}", "未契約")
            ->setCellValue("Q{$index}", "次月に繰越")
            ->setCellValue("T{$index}", "前月からの繰越")
            ->setCellValue("W{$index}", "フリー")
            ->setCellValue("X{$index}", "紹介")
            ->setCellValue("Y{$index}", "呼")
            ->setCellValue("Z{$index}", "呼")
            ->setCellValue("AA{$index}", "呼率")
            ->setCellValue("AB{$index}", "呼率")
            ->setCellValue("AC{$index}", "呼実")
            ->setCellValue("AD{$index}", "呼率")
            ->setCellValue("AE{$index}", "反響")
            ->setCellValue("AF{$index}", "反響")
            ->setCellValue("AG{$index}", "反響合計")
            ->setCellValue("AH{$index}", "再来店")
            ->setCellValue("AI{$index}", "申込")
            ->setCellValue("AK{$index}", "申率")
            ->setCellValue("AL{$index}", "キャ")
            ->setCellValue("AM{$index}", "振替")
            ->setCellValue("AN{$index}", "成約率")
            ->setCellValue("AO{$index}", "自社申込")
    ;
    $index ++;
    $month_target = 0.00;
    $today_cost = 0.00;
    $today_signboard = 0.00;
    $today_introduction = 0.00;
    $today_tel_status = 0.00;
    $today_mail_status = 0.00;
    $today_track_record = 0.00;
    $today_tel = 0.00;
    $today_mail = 0.00;
    $today_revisit = 0.00;
    $today_application = 0.00;
    $today_cancel = 0.00;
    $today_change = 0.00;
    $today_agreement = 0.00;
    $today_done = 0.00;

    $month_cost = 0.00;
    $month_cost_previous = 0.00;
    $month_signboard = 0.00;
    $month_introduction = 0.00;
    $month_tel_status = 0.00;
    $month_mail_status = 0.00;
    $month_track_record = 0.00;
    $month_tel = 0.00;
    $month_mail = 0.00;
    $month_revisit = 0.00;
    $month_application = 0.00;
    $month_cancel = 0.00;
    $month_change = 0.00;
    $month_agreement = 0.00;
    $month_done = 0.00;

    foreach ($users as $key => $item) {
        $plus = $index + 1;
        $info = $report->getUserInfo($item['id'], $date,$fromdate);
        $user_target = (int) $report ->getUserTarget($item['id'],$date,$fromdate);
        $month_target = $month_target + $user_target;
        $today_signboard = $today_signboard + (int) ($info['today_shop_sign'] + $info['today_local_sign']);
        $today_introduction = $today_introduction + (int) $info['today_introduction'];
        $today_tel_status = $today_tel_status + (int) $info['today_tel_status'];
        $today_mail_status = $today_mail_status + (int) $info['today_mail_status'];
        $today_track_record = $today_track_record + (int) $info['today_tel_status'] + (int) $info['today_mail_status'];
        $today_tel = $today_tel + (int) $info['today_tel'];
        $today_mail = $today_mail + (int) $info['today_mail'];
        $today_revisit = $today_revisit + (int) $info['today_revisit'];
        $today_application = $today_application + (int) $info['today_application'];
        $today_cancel = $today_cancel + (int) $info['today_cancel'];
        $today_change = $today_change + (int) $info['today_change'];
        $today_agreement = $today_agreement + (int) $info['today_agreement'];
        $today_done = $today_done + (int) $info['today_agreement'];

        $month_cost = $month_cost + (int) $info['cost_month'];
        $month_cost_previous = $month_cost_previous + $info['cost_previous_month'] - $user_target;
        $month_signboard = $month_signboard + (int) ($info['month_shop_sign'] + $info['month_local_sign']);
        $month_introduction = $month_introduction + (int) $info['month_introduction'];
        $month_tel_status = $month_tel_status + (int) $info['month_tel_status'];
        $month_mail_status = $month_mail_status + (int) $info['month_mail_status'];
        $month_track_record = $month_track_record + (int) $info['month_tel_status'] + (int) $info['month_mail_status'];
        $month_tel = $month_tel + (int) $info['month_tel'];
        $month_mail = $month_mail + (int) $info['month_mail'];
        $month_revisit = $month_revisit + (int) $info['month_revisit'];
        $month_application = $month_application + (int) $info['month_application'];
        $month_cancel = $month_cancel + (int) $info['month_cancel'];
        $month_change = $month_change + (int) $info['month_change'];
        $month_agreement = $month_agreement + (int) $info['month_agreement'];
        $month_done = $month_done + (int) $info['month_agreement'];


        $objPHPExcel->getActiveSheet()
                ->mergeCells("A{$index}:A{$plus}")
                ->mergeCells("B{$index}:D{$plus}")
                ->mergeCells("E{$index}:F{$plus}")
                ->mergeCells("G{$index}:I{$plus}")
                ->mergeCells("K{$index}:M{$index}")
                ->mergeCells("K{$plus}:M{$plus}")
                ->mergeCells("N{$index}:P{$index}")
                ->mergeCells("N{$plus}:P{$plus}")
                ->mergeCells("Q{$index}:S{$index}")
                ->mergeCells("Q{$plus}:S{$plus}")
                ->mergeCells("T{$index}:V{$index}")
                ->mergeCells("T{$plus}:V{$plus}")
        ;
        $tmp = (int) ($info['today_shop_sign'] + $info['today_local_sign']) + (int) $info['today_introduction'] + (int) $info['today_tel_status'] + (int) $info['today_mail_status'];
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$index}", $key + 1)
                ->setCellValue("B{$index}", $item['user_fname'] . ' ' . $item['user_lname'])
                ->setCellValue("E{$index}", $item['user_position'])
                ->setCellValue("G{$index}", $user_target)
                ->setCellValue("J{$index}", "当日")
                ->setCellValue("K{$index}", $info['cost_today'])
                ->setCellValue("W{$index}", $info['today_shop_sign'] + $info['today_local_sign'])
                ->setCellValue("X{$index}", $info['today_introduction'])
                ->setCellValue("Y{$index}", $info['today_tel_status'])
                ->setCellValue("Z{$index}", $info['today_mail_status'])
                ->setCellValue("AA{$index}", ( $info['today_tel'] ? round($info['today_tel_status'] * 100 / $info['today_tel'], 2) : 0 ) . '%')
                ->setCellValue("AB{$index}", ($info['today_mail'] ? round($info['today_mail_status'] * 100 / $info['today_mail'], 2) : 0) . '%')
                ->setCellValue("AC{$index}", $info['today_tel_status'] + $info['today_mail_status'])
                ->setCellValue("AD{$index}", round(($info['today_tel'] + $info['today_mail']) ? (100 * ($info['today_tel_status'] + $info['today_mail_status']) / ($info['today_tel'] + $info['today_mail'])) : 0, 2) . '%')
                ->setCellValue("AE{$index}", $info['today_tel'])
                ->setCellValue("AF{$index}", $info['today_mail'])
                ->setCellValue("AG{$index}", (int) ($info['today_tel'] + $info['today_mail']))
                ->setCellValue("AH{$index}", $info['today_revisit'])
                ->setCellValue("AI{$index}", $info['today_application'])
                ->setCellValue("AJ{$index}", round(($tmp > 0) ? (100 * ($info['today_application']) / $tmp) : 0, 2) . '%')
                ->setCellValue("AK{$index}", $info['today_cancel'])
                ->setCellValue("AL{$index}", $info['today_change'])
                ->setCellValue("AM{$index}", $info['today_agreement'])
                ->setCellValue("AN{$index}", round(($tmp > 0) ? (100 * ($info['today_agreement']) / $tmp) : 0, 2) . '%')
                ->setCellValue("AO{$index}", $info['today_agreement'])
                ->setCellValue("J{$plus}", "累計")
                ->setCellValue("K{$plus}", $info['cost_month'])
                ->setCellValue("T{$plus}", $info['cost_previous_month'] - $user_target)
                ->setCellValue("W{$plus}", $info['month_shop_sign'] + $info['month_local_sign'])
                ->setCellValue("X{$plus}", $info['month_introduction'])
                ->setCellValue("Y{$plus}", $info['month_tel_status'])
                ->setCellValue("Z{$plus}", $info['month_mail_status'])
                ->setCellValue("AA{$plus}", ( $info['month_tel'] ? round($info['month_tel_status'] * 100 / $info['month_tel'], 2) : 0 ) . '%')
                ->setCellValue("AB{$plus}", ($info['month_mail'] ? round($info['month_mail_status'] * 100 / $info['month_mail'], 2) : 0) . '%')
                ->setCellValue("AC{$plus}", $info['month_tel_status'] + $info['month_mail_status'])
                ->setCellValue("AD{$plus}", round(($info['month_tel'] + $info['month_mail']) ? (100 * ($info['month_tel_status'] + $info['month_mail_status']) / ($info['month_tel'] + $info['month_mail'])) : 0, 2) . '%')
                ->setCellValue("AE{$plus}", $info['month_tel'])
                ->setCellValue("AF{$plus}", $info['month_mail'])
                ->setCellValue("AG{$plus}", (int) ($info['month_tel'] + $info['month_mail']))
                ->setCellValue("AH{$plus}", $info['month_revisit'])
                ->setCellValue("AI{$plus}", $info['month_application'])
                ->setCellValue("AJ{$plus}", round(($tmp > 0) ? (100 * ($info['month_application']) / $tmp) : 0, 2) . '%')
                ->setCellValue("AK{$plus}", $info['month_cancel'])
                ->setCellValue("AL{$plus}", $info['month_change'])
                ->setCellValue("AM{$plus}", $info['month_agreement'])
                ->setCellValue("AN{$plus}", round(($tmp > 0) ? (100 * ($info['month_agreement']) / $tmp) : 0, 2) . '%')
                ->setCellValue("AO{$plus}", $info['month_agreement'])
        ;
    }
    $index = $plus + 1;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:F{$plus}")
            ->mergeCells("G{$index}:I{$plus}")
            ->mergeCells("K{$index}:M{$index}")
            ->mergeCells("K{$plus}:M{$plus}")
            ->mergeCells("N{$index}:P{$index}")
            ->mergeCells("N{$plus}:P{$plus}")
            ->mergeCells("Q{$index}:S{$index}")
            ->mergeCells("Q{$plus}:S{$plus}")
            ->mergeCells("T{$index}:V{$index}")
            ->mergeCells("T{$plus}:V{$plus}")
    ;

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "合　計")
            ->setCellValue("G{$index}", $month_target)
            ->setCellValue("J{$index}", "当日")
            ->setCellValue("K{$index}", $today_cost)
            ->setCellValue("W{$index}", $today_signboard)
            ->setCellValue("X{$index}", $today_introduction)
            ->setCellValue("Y{$index}", $today_tel_status)
            ->setCellValue("Z{$index}", $today_mail_status)
            ->setCellValue("AA{$index}", ( $today_tel ? round($today_tel_status * 100 / $today_tel, 2) : 0 ) . '%')
            ->setCellValue("AB{$index}", ($today_mail ? round($today_mail_status * 100 / $today_mail, 2) : 0) . '%')
            ->setCellValue("AC{$index}", $today_tel_status + $today_mail_status)
            ->setCellValue("AD{$index}", round(($today_tel + $today_mail) ? (100 * ($today_tel_status + $today_mail_status) / ($today_tel + $today_mail)) : 0, 2) . '%')
            ->setCellValue("AE{$index}", $today_tel)
            ->setCellValue("AF{$index}", $today_mail)
            ->setCellValue("AG{$index}", (int) ($today_tel + $today_mail))
            ->setCellValue("AH{$index}", $today_revisit)
            ->setCellValue("AI{$index}", $today_application)
            ->setCellValue("AJ{$index}", round(($tmp > 0) ? (100 * ($today_application) / $tmp) : 0, 2) . '%')
            ->setCellValue("AK{$index}", $today_cancel)
            ->setCellValue("AL{$index}", $today_change)
            ->setCellValue("AM{$index}", $today_agreement)
            ->setCellValue("AN{$index}", round(($tmp > 0) ? (100 * ($today_agreement) / $tmp) : 0, 2) . '%')
            ->setCellValue("AO{$index}", $today_agreement)
            ->setCellValue("J{$plus}", "累計")
            ->setCellValue("K{$plus}", $month_cost)
            ->setCellValue("T{$plus}", $month_cost_previous)
            ->setCellValue("W{$plus}", $month_signboard)
            ->setCellValue("X{$plus}", $month_introduction)
            ->setCellValue("Y{$plus}", $month_tel_status)
            ->setCellValue("Z{$plus}", $month_mail_status)
            ->setCellValue("AA{$plus}", ( $month_tel ? round($month_tel_status * 100 / $month_tel, 2) : 0 ) . '%')
            ->setCellValue("AB{$plus}", ($month_mail ? round($month_mail_status * 100 / $month_mail, 2) : 0) . '%')
            ->setCellValue("AC{$plus}", $month_tel_status + $month_mail_status)
            ->setCellValue("AD{$plus}", round(($month_tel + $month_mail) ? (100 * ($month_tel_status + $month_mail_status) / ($month_tel + $month_mail)) : 0, 2) . '%')
            ->setCellValue("AE{$plus}", $month_tel)
            ->setCellValue("AF{$plus}", $month_mail)
            ->setCellValue("AG{$plus}", (int) ($month_tel + $month_mail))
            ->setCellValue("AH{$plus}", $month_revisit)
            ->setCellValue("AI{$plus}", $month_application)
            ->setCellValue("AJ{$plus}", round(($tmp > 0) ? (100 * ($month_application) / $tmp) : 0, 2) . '%')
            ->setCellValue("AK{$plus}", $month_cancel)
            ->setCellValue("AL{$plus}", $month_change)
            ->setCellValue("AM{$plus}", $month_agreement)
            ->setCellValue("AN{$plus}", round(($tmp > 0) ? (100 * ($month_agreement) / $tmp) : 0, 2) . '%')
            ->setCellValue("AO{$plus}", $month_agreement)
    ;
            
    //border
    $border = array(
        'borders' => array(
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
          )
        )
      );
    $objPHPExcel->getActiveSheet()->getStyle("A2:AO{$plus}")->applyFromArray($border);
            
    //2
    $index = $index + 3;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:J{$index}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "昨年実績");


    $index = $index + 2;
    $index_tmp = $index;
    
    //Border
    $objPHPExcel->getActiveSheet()->getStyle("A{$index}:N".($index + 18))->applyFromArray($border);
    
    $objPHPExcel->getActiveSheet()
            ->mergeCells("B{$index}:E{$index}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B{$index}", "媒体")
            ->setCellValue("F{$index}", "計")
            ->setCellValue("G{$index}", "反響")
            ->setCellValue("H{$index}", "呼実")
            ->setCellValue("I{$index}", "フリー")
            ->setCellValue("J{$index}", "再来店")
            ->setCellValue("K{$index}", "申込")
            ->setCellValue("L{$index}", "キャ")
            ->setCellValue("M{$index}", "自社")
            ->setCellValue("N{$index}", "台帳")
    ;

    $yearReport = $report->getLastyearInfo($agent_id, $date,$fromdate);

    $index = $index + 1;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "1")
            ->setCellValue("B{$index}", "インターネット（メール）")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todaymail_mail'] + $yearReport['todaymail_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todaymail_tel_status'] + $yearReport['todaymail_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todaymail_shop_sign'] + $yearReport['todaymail_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todaymail_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todaymail_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todaymail_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todaymail_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todaymail_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearmail_mail'] + $yearReport['yearmail_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearmail_tel_status'] + $yearReport['yearmail_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearmail_shop_sign'] + $yearReport['yearmail_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearmail_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearmail_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearmail_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearmail_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearmail_agreement'])
    ;

    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "2")
            ->setCellValue("B{$index}", "インターネット（電話）")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todayphone_mail'] + $yearReport['todayphone_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todayphone_tel_status'] + $yearReport['todayphone_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todayphone_shop_sign'] + $yearReport['todayphone_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todayphone_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todayphone_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todayphone_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todayphone_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todayphone_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearphone_mail'] + $yearReport['yearphone_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearphone_tel_status'] + $yearReport['yearphone_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearphone_shop_sign'] + $yearReport['yearphone_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearphone_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearphone_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearphone_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearphone_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearphone_agreement'])
    ;

    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "3")
            ->setCellValue("B{$index}", "定借(TEL、メール）")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todaydiscount_mail'] + $yearReport['todaydiscount_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todaydiscount_tel_status'] + $yearReport['todaydiscount_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todaydiscount_shop_sign'] + $yearReport['todaydiscount_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todaydiscount_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todaydiscount_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todaydiscount_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todaydiscount_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todaydiscount_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yeardiscount_mail'] + $yearReport['yeardiscount_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yeardiscount_tel_status'] + $yearReport['yeardiscount_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yeardiscount_shop_sign'] + $yearReport['yeardiscount_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yeardiscount_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yeardiscount_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yeardiscount_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yeardiscount_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yeardiscount_agreement'])
    ;


    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "4")
            ->setCellValue("B{$index}", "ローカルののサイン")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todaylocalsign_mail'] + $yearReport['todaylocalsign_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todaylocalsign_tel_status'] + $yearReport['todaylocalsign_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todaylocalsign_shop_sign'] + $yearReport['todaylocalsign_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todaylocalsign_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todaylocalsign_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todaylocalsign_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todaylocalsign_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todaylocalsign_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearlocalsign_mail'] + $yearReport['yearlocalsign_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearlocalsign_tel_status'] + $yearReport['yearlocalsign_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearlocalsign_shop_sign'] + $yearReport['yearlocalsign_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearlocalsign_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearlocalsign_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearlocalsign_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearlocalsign_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearlocalsign_agreement'])
    ;

    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "5")
            ->setCellValue("B{$index}", "紹介")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todayintroduction_mail'] + $yearReport['todayintroduction_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todayintroduction_tel_status'] + $yearReport['todayintroduction_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todayintroduction_shop_sign'] + $yearReport['todayintroduction_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todayintroduction_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todayintroduction_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todayintroduction_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todayintroduction_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todayintroduction_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearintroduction_mail'] + $yearReport['yearintroduction_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearintroduction_tel_status'] + $yearReport['yearintroduction_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearintroduction_shop_sign'] + $yearReport['yearintroduction_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearintroduction_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearintroduction_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearintroduction_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearintroduction_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearintroduction_agreement'])
    ;


    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "6")
            ->setCellValue("B{$index}", "店看板")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todayshopsign_mail'] + $yearReport['todayshopsign_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todayshopsign_tel_status'] + $yearReport['todayshopsign_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todayshopsign_shop_sign'] + $yearReport['todayshopsign_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todayshopsign_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todayshopsign_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todayshopsign_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todayshopsign_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todayshopsign_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearshopsign_mail'] + $yearReport['yearshopsign_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearshopsign_tel_status'] + $yearReport['yearshopsign_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearshopsign_shop_sign'] + $yearReport['yearshopsign_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearshopsign_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearshopsign_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearshopsign_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearshopsign_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearshopsign_agreement'])
    ;

    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "7")
            ->setCellValue("B{$index}", "チラシ")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todayflyer_mail'] + $yearReport['todayflyer_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todayflyer_tel_status'] + $yearReport['todayflyer_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todayflyer_shop_sign'] + $yearReport['todayflyer_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todayflyer_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todayflyer_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todayflyer_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todayflyer_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todayflyer_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearflyer_mail'] + $yearReport['yearflyer_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearflyer_tel_status'] + $yearReport['yearflyer_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearflyer_shop_sign'] + $yearReport['yearflyer_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearflyer_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearflyer_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearflyer_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearflyer_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearflyer_agreement'])
    ;

    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:A{$plus}")
            ->mergeCells("B{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "8")
            ->setCellValue("B{$index}", "ライン")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) ($yearReport['todayline_mail'] + $yearReport['todayline_tel']))
            ->setCellValue("H{$index}", (int) ($yearReport['todayline_tel_status'] + $yearReport['todayline_mail_status']))
            ->setCellValue("I{$index}", (int) ($yearReport['todayline_shop_sign'] + $yearReport['todayline_local_sign']))
            ->setCellValue("J{$index}", (int) $yearReport['todayline_revisit'])
            ->setCellValue("K{$index}", (int) $yearReport['todayline_application'])
            ->setCellValue("L{$index}", (int) $yearReport['todayline_cancel'])
            ->setCellValue("M{$index}", (int) $yearReport['todayline_change'])
            ->setCellValue("N{$index}", (int) $yearReport['todayline_agreement'])
            ->setCellValue("G{$plus}", (int) ($yearReport['yearline_mail'] + $yearReport['yearline_tel']))
            ->setCellValue("H{$plus}", (int) ($yearReport['yearline_tel_status'] + $yearReport['yearline_mail_status']))
            ->setCellValue("I{$plus}", (int) ($yearReport['yearline_shop_sign'] + $yearReport['yearline_shop_sign']))
            ->setCellValue("J{$plus}", (int) $yearReport['yearline_revisit'])
            ->setCellValue("K{$plus}", (int) $yearReport['yearline_application'])
            ->setCellValue("L{$plus}", (int) $yearReport['yearline_cancel'])
            ->setCellValue("M{$plus}", (int) $yearReport['yearline_change'])
            ->setCellValue("N{$plus}", (int) $yearReport['yearline_agreement'])
    ;
             
    $index = $index + 2;
    $plus = $index + 1;
    $objPHPExcel->getActiveSheet()
            ->mergeCells("A{$index}:E{$plus}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A{$index}", "合　計")
            ->setCellValue("F{$index}", "当日")
            ->setCellValue("F{$plus}", "累計")
            ->setCellValue("G{$index}", (int) (
                    $yearReport['todaymail_mail'] + $yearReport['todaymail_tel'] +
                    $yearReport['todayphone_mail'] + $yearReport['todayphone_tel'] + 
                    $yearReport['todaydiscount_mail'] + $yearReport['todaydiscount_tel'] +
                    $yearReport['todaylocalsign_mail'] + $yearReport['todaylocalsign_tel'] +
                    $yearReport['todayintroduction_mail'] + $yearReport['todayintroduction_tel'] +
                    $yearReport['todayshopsign_mail'] + $yearReport['todayshopsign_tel'] +
                    $yearReport['todayflyer_mail'] + $yearReport['todayflyer_tel'] +
                    $yearReport['todayline_mail'] + $yearReport['todayline_tel'] 
                    ))
            ->setCellValue("H{$index}", (int) (
                    $yearReport['todaymail_mail_status'] + $yearReport['todaymail_tel_status'] +
                    $yearReport['todayphone_mail_status'] + $yearReport['todayphone_tel_status'] + 
                    $yearReport['todaydiscount_mail_status'] + $yearReport['todaydiscount_tel_status'] +
                    $yearReport['todaylocalsign_mail_status'] + $yearReport['todaylocalsign_tel_status'] +
                    $yearReport['todayintroduction_mail_status'] + $yearReport['todayintroduction_tel_status'] +
                    $yearReport['todayshopsign_mail_status'] + $yearReport['todayshopsign_tel_status'] +
                    $yearReport['todayflyer_mail_status'] + $yearReport['todayflyer_tel_status'] +
                    $yearReport['todayline_tel_status'] + $yearReport['todayline_mail_status']
                    ))
            ->setCellValue("I{$index}", (int) (
                    $yearReport['todaymail_shop_sign'] + $yearReport['todaymail_local_sign'] +
                    $yearReport['todayphone_shop_sign'] + $yearReport['todayphone_local_sign'] + 
                    $yearReport['todaydiscount_shop_sign'] + $yearReport['todaydiscount_local_sign'] +
                    $yearReport['todaylocalsign_shop_sign'] + $yearReport['todaylocalsign_local_sign'] +
                    $yearReport['todayintroduction_shop_sign'] + $yearReport['todayintroduction_local_sign'] +
                    $yearReport['todayshopsign_shop_sign'] + $yearReport['todayshopsign_local_sign'] +
                    $yearReport['todayflyer_shop_sign'] + $yearReport['todayflyer_local_sign'] +
                    $yearReport['todayline_shop_sign'] + $yearReport['todayline_local_sign']
                    ))
            ->setCellValue("J{$index}", (int) (
                    $yearReport['todaymail_revisit'] +
                    $yearReport['todayphone_revisit'] + 
                    $yearReport['todaydiscount_revisit'] +
                    $yearReport['todaylocalsign_revisit'] +
                    $yearReport['todayintroduction_revisit'] +
                    $yearReport['todayshopsign_revisit'] +
                    $yearReport['todayflyer_revisit'] +
                    $yearReport['todayline_revisit']
                    ))
            ->setCellValue("K{$index}", (int) (
                    $yearReport['todaymail_application'] +
                    $yearReport['todayphone_application'] + 
                    $yearReport['todaydiscount_application'] +
                    $yearReport['todaylocalsign_application'] +
                    $yearReport['todayintroduction_application'] +
                    $yearReport['todayshopsign_application'] +
                    $yearReport['todayflyer_application'] +
                    $yearReport['todayline_application']
                    ))
            ->setCellValue("L{$index}", (int) (
                    $yearReport['todaymail_cancel'] +
                    $yearReport['todayphone_cancel'] + 
                    $yearReport['todaydiscount_cancel'] +
                    $yearReport['todaylocalsign_cancel'] +
                    $yearReport['todayintroduction_cancel'] +
                    $yearReport['todayshopsign_cancel'] +
                    $yearReport['todayflyer_cancel'] +
                    $yearReport['todayline_cancel']
                    ))
            ->setCellValue("M{$index}", (int) (
                    $yearReport['todaymail_change'] +
                    $yearReport['todayphone_change'] + 
                    $yearReport['todaydiscount_change'] +
                    $yearReport['todaylocalsign_change'] +
                    $yearReport['todayintroduction_change'] +
                    $yearReport['todayshopsign_change'] +
                    $yearReport['todayflyer_change'] +
                    $yearReport['todayline_change']
                    ))
            ->setCellValue("N{$index}", (int) (
                    $yearReport['todaymail_agreement'] +
                    $yearReport['todayphone_agreement'] + 
                    $yearReport['todaydiscount_agreement'] +
                    $yearReport['todaylocalsign_agreement'] +
                    $yearReport['todayintroduction_agreement'] +
                    $yearReport['todayshopsign_agreement'] +
                    $yearReport['todayflyer_agreement'] +
                    $yearReport['todayline_agreement']
                    ))
            ->setCellValue("G{$plus}", (int) (
                    $yearReport['yearmail_mail'] + $yearReport['yearmail_tel'] +
                    $yearReport['yearphone_mail'] + $yearReport['yearphone_tel'] + 
                    $yearReport['yeardiscount_mail'] + $yearReport['yeardiscount_tel'] +
                    $yearReport['yearlocalsign_mail'] + $yearReport['yearlocalsign_tel'] +
                    $yearReport['yearintroduction_mail'] + $yearReport['yearintroduction_tel'] +
                    $yearReport['yearshopsign_mail'] + $yearReport['yearshopsign_tel'] +
                    $yearReport['yearflyer_mail'] + $yearReport['yearflyer_tel'] +
                    $yearReport['yearline_mail'] + $yearReport['yearline_tel']
                    ))
            ->setCellValue("H{$plus}", (int) (
                    $yearReport['yearmail_mail_status'] + $yearReport['yearmail_tel_status'] +
                    $yearReport['yearphone_mail_status'] + $yearReport['yearphone_tel_status'] + 
                    $yearReport['yeardiscount_mail_status'] + $yearReport['yeardiscount_tel_status'] +
                    $yearReport['yearlocalsign_mail_status'] + $yearReport['yearlocalsign_tel_status'] +
                    $yearReport['yearintroduction_mail_status'] + $yearReport['yearintroduction_tel_status'] +
                    $yearReport['yearshopsign_mail_status'] + $yearReport['yearshopsign_tel_status'] +
                    $yearReport['yearflyer_mail_status'] + $yearReport['yearflyer_tel_status'] +
                    $yearReport['yearline_tel_status'] + $yearReport['yearline_mail_status']
                    ))
            ->setCellValue("I{$plus}", (int) (
                    $yearReport['yearmail_shop_sign'] + $yearReport['yearmail_local_sign'] +
                    $yearReport['yearphone_shop_sign'] + $yearReport['yearphone_local_sign'] + 
                    $yearReport['yeardiscount_shop_sign'] + $yearReport['yeardiscount_local_sign'] +
                    $yearReport['yearlocalsign_shop_sign'] + $yearReport['yearlocalsign_local_sign'] +
                    $yearReport['yearintroduction_shop_sign'] + $yearReport['yearintroduction_local_sign'] +
                    $yearReport['yearshopsign_shop_sign'] + $yearReport['yearshopsign_local_sign'] +
                    $yearReport['yearflyer_shop_sign'] + $yearReport['yearflyer_local_sign'] +
                    $yearReport['yearline_shop_sign'] + $yearReport['yearline_shop_sign']
                    ))
            ->setCellValue("J{$plus}", (int) (
                    $yearReport['yearmail_revisit'] +
                    $yearReport['yearphone_revisit'] + 
                    $yearReport['yeardiscount_revisit'] +
                    $yearReport['yearlocalsign_revisit'] +
                    $yearReport['yearintroduction_revisit'] +
                    $yearReport['yearshopsign_revisit'] +
                    $yearReport['yearflyer_revisit'] +
                    $yearReport['yearline_revisit']
                    ))
            ->setCellValue("K{$plus}", (int) (
                    $yearReport['yearmail_application'] +
                    $yearReport['yearphone_application'] + 
                    $yearReport['yeardiscount_application'] +
                    $yearReport['yearlocalsign_application'] +
                    $yearReport['yearintroduction_application'] +
                    $yearReport['yearshopsign_application'] +
                    $yearReport['yearflyer_application'] +
                    $yearReport['yearline_application']
                    ))
            ->setCellValue("L{$plus}", (int) (
                    $yearReport['yearmail_cancel'] +
                    $yearReport['yearphone_cancel'] + 
                    $yearReport['yeardiscount_cancel'] +
                    $yearReport['yearlocalsign_cancel'] +
                    $yearReport['yearintroduction_cancel'] +
                    $yearReport['yearshopsign_cancel'] +
                    $yearReport['yearflyer_cancel'] +
                    $yearReport['yearline_cancel']
                    ))
            ->setCellValue("M{$plus}", (int) (
                    $yearReport['yearmail_change'] +
                    $yearReport['yearphone_change'] + 
                    $yearReport['yeardiscount_change'] +
                    $yearReport['yearlocalsign_change'] +
                    $yearReport['yearintroduction_change'] +
                    $yearReport['yearshopsign_change'] +
                    $yearReport['yearflyer_change'] +
                    $yearReport['yearline_change']
                    ))
            ->setCellValue("N{$plus}", (int) (
                    $yearReport['yearmail_agreement'] +
                    $yearReport['yearphone_agreement'] + 
                    $yearReport['yeardiscount_agreement'] +
                    $yearReport['yearlocalsign_agreement'] +
                    $yearReport['yearintroduction_agreement'] +
                    $yearReport['yearshopsign_agreement'] +
                    $yearReport['yearflyer_agreement'] +
                    $yearReport['yearline_agreement']
                    ))
    ;

    // Detail of Web
    $objPHPExcel->getActiveSheet()
            ->mergeCells("V{$index_tmp}:AA{$index_tmp}")
    ;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("V{$index_tmp}", "インターネット詳細")
            ->setCellValue("AB{$index_tmp}", "累計")
            ->setCellValue("AC{$index_tmp}", "反響")
            ->setCellValue("AD{$index_tmp}", "呼実")
            ->setCellValue("AE{$index_tmp}", "フリー")
            ->setCellValue("AF{$index_tmp}", "再来店")
            ->setCellValue("AG{$index_tmp}", "申込")
            ->setCellValue("AH{$index_tmp}", "キャ")
            ->setCellValue("AI{$index_tmp}", "自社")
            ->setCellValue("AJ{$index_tmp}", "台帳")
    ;
    $webs = $report->getAllSource();
    $idx = $index_tmp;
    $index_tmp = $index_tmp - 1;
    
    $today_feedback = 0.00;
    $today_track_record = 0.00;
    $today_signboard = 0.00;
    $today_signboard = 0.00;
    $today_introduction = 0.00;
    $today_tel_status = 0.00;
    $today_mail_status = 0.00;
    $today_track_record = 0.00;
    $today_tel = 0.00;
    $today_mail = 0.00;
    $today_revisit = 0.00;
    $today_application = 0.00;
    $today_cancel = 0.00;
    $today_change = 0.00;
    $today_agreement = 0.00;
    $today_done = 0.00;

    $month_cost = 0.00;
    $month_cost_previous = 0.00;
    $month_signboard = 0.00;
    $month_introduction = 0.00;
    $month_tel_status = 0.00;
    $month_mail_status = 0.00;
    $month_track_record = 0.00;
    $month_tel = 0.00;
    $month_mail = 0.00;
    $month_revisit = 0.00;
    $month_application = 0.00;
    $month_cancel = 0.00;
    $month_change = 0.00;
    $month_agreement = 0.00;
    $month_done = 0.00;
    
    if (count($webs)) {
        foreach ($webs as $key => $web) {
            $com_info = $report->getSourceInfo($web['id'], $date,$fromdate);
            $index_tmp = $index_tmp + 2;
            $plus = $index_tmp + 1;
            $objPHPExcel->getActiveSheet()
                    ->mergeCells("U{$index_tmp}:U{$plus}")
                    ->mergeCells("V{$index_tmp}:AA{$plus}")
            ;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("U{$index_tmp}", $key + 1)
                    ->setCellValue("V{$index_tmp}", $web['source_name'])
                    ->setCellValue("AB{$index_tmp}", "当日")
                    ->setCellValue("AB{$plus}", "累計")
                    ->setCellValue("AC{$index_tmp}", (int) ($com_info['today_tel'] + $com_info['today_mail']))
                    ->setCellValue("AD{$index_tmp}", (int) ($com_info['today_tel_status'] + $com_info['today_mail_status']))
                    ->setCellValue("AE{$index_tmp}", (int) ($com_info['today_shop_sign'] + $com_info['today_shop_sign']))
                    ->setCellValue("AF{$index_tmp}", (int) $com_info['today_revisit'])
                    ->setCellValue("AG{$index_tmp}", (int) $com_info['today_application'])
                    ->setCellValue("AH{$index_tmp}", (int) $com_info['today_cancel'])
                    ->setCellValue("AI{$index_tmp}", (int) $com_info['today_change'])
                    ->setCellValue("AJ{$index_tmp}", (int) $com_info['today_agreement'])
                    ->setCellValue("AC{$plus}", (int) ($com_info['month_mail'] + $com_info['month_tel']))
                    ->setCellValue("AD{$plus}", (int) ($com_info['month_tel_status'] + $com_info['month_mail_status']))
                    ->setCellValue("AE{$plus}", (int) ($com_info['month_shop_sign'] + $com_info['month_shop_sign']))
                    ->setCellValue("AF{$plus}", (int) $com_info['month_revisit'])
                    ->setCellValue("AG{$plus}", (int) $com_info['month_application'])
                    ->setCellValue("AH{$plus}", (int) $com_info['month_cancel'])
                    ->setCellValue("AI{$plus}", (int) $com_info['month_change'])
                    ->setCellValue("AJ{$plus}", (int) $com_info['month_agreement'])
            ;
        }
    }
    //Border
    $objPHPExcel->getActiveSheet()->getStyle("U{$idx}:AJ{$plus}")->applyFromArray($border);
    
// Rename worksheet
//    $objPHPExcel->getActiveSheet()->setTitle('Simple');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename='{$title}.xls'");
    header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
include 'footer.php';
