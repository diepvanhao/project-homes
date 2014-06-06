<?php

include 'header.php';
$page = 'report';

date_default_timezone_set("Asia/Bangkok");
include 'include/class_report.php';
$report = new Report();

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
$smarty->assign('date', $date);
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
if (!empty($post['export'])) {
    require_once 'include/PHPExcel.php';
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    if (empty($date)) {
        $date = date("m/d/Y");
    }
    $title = "Report - {$date}";

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
            ->setCellValue("B{$index}", "Name")
            ->setCellValue("E{$index}", "Position")
            ->setCellValue("G{$index}", "Monthly Target")
            ->setCellValue("J{$index}", "Total")
            ->setCellValue("K{$index}", "Already recorded")
            ->setCellValue("N{$index}", "Unsigned")
            ->setCellValue("Q{$index}", "carry-over to the next month")
            ->setCellValue("T{$index}", "carry-over from the previous month")
            ->setCellValue("W{$index}", "signboard")
            ->setCellValue("X{$index}", "Introduction")
            ->setCellValue("Y{$index}", "TEL")
            ->setCellValue("Z{$index}", "MAIL")
            ->setCellValue("AA{$index}", "Percentage of call")
            ->setCellValue("AB{$index}", "Percentage of mail")
            ->setCellValue("AC{$index}", "track record ")
            ->setCellValue("AD{$index}", "Call rate")
            ->setCellValue("AE{$index}", "Feedback by TEL")
            ->setCellValue("AF{$index}", "Feedback by MAIL")
            ->setCellValue("AG{$index}", "Contact Total number")
            ->setCellValue("AH{$index}", "Re-visit")
            ->setCellValue("AI{$index}", "Application")
            ->setCellValue("AK{$index}", "Application rate")
            ->setCellValue("AL{$index}", "Cancel")
            ->setCellValue("AM{$index}", "Change")
            ->setCellValue("AN{$index}", "Contact ratio")
            ->setCellValue("AO{$index}", "Company Registeration")
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
        $info = $report->getUserInfo($item['id'], $date);

        $month_target = $month_target + $item['user_target'];
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
        $month_cost_previous = $month_cost_previous + $info['cost_previous_month'] - $item['user_target'];
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
                ->setCellValue("G{$index}", $item['user_target'])
                ->setCellValue("J{$index}", "Today")
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
                        
                ->setCellValue("J{$plus}", "Total")
                ->setCellValue("K{$plus}", $info['cost_month'])
                ->setCellValue("T{$plus}", $info['cost_previous_month'] - $item['user_target'])
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
                ->setCellValue("A{$index}", "Total")
                ->setCellValue("G{$index}", $month_target)
                ->setCellValue("J{$index}", "Today")
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
                        
                ->setCellValue("J{$plus}", "Total")
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
