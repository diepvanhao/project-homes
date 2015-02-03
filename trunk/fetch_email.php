<?php

include "header.php";
$page = "fetch_email";
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//check user login
if (!$user->user_exists) {
    header('Location: ./user_login.php');
    exit();
}
if ($user->user_info['user_authorities'] > 2) {
    header('Location: ./restrict.php');
    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}


//get content email homes, sumo and athome
$hostname = '{mail.roompia.jp}';
$account = array(
    array(
        'source' => 'Homes',
        'username' => 'h_web@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'source' => 'SUMO',
        'username' => 's_web@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'source' => 'Athome',
        'username' => 'a_web@roompia.jp',
        'password' => 'ambad001'
    )
);

$orderClass = new HOMEOrder();

//$str="物件種別：賃貸 マンション";
//$str=  str_replace("：", "?", $str);
//echo $str;
//$str=  explode("?", $str);
//var_dump($str);die();
//header('Content-Type: text/html; charset=iso-2022-jp');
/* try to connect */
if (isset($_POST['get_new'])) {
    $order = array();
    for ($i = 0; $i < count($account); $i++) {
        $inbox = imap_open($hostname, $account[$i]['username'], $account[$i]['password']) or die('Cannot connect to Gmail: ' . imap_last_error());
        if ($account[$i]['source'] == 'Homes') {
            $emails = imap_search($inbox, 'FROM support@homes.co.jp');
        } elseif ($account[$i]['source'] == 'SUMO') {
            $emails = imap_search($inbox, 'FROM jds_system@jds.jutakujoho.jp');
        } else {
            $emails = imap_search($inbox, 'FROM mailtofax@athome.jp');
        }
        /* if emails are returned, cycle through each... */
        if ($emails) {

            /* put the newest emails on top */
            rsort($emails);
            /* for every email... */
            foreach ($emails as $email_number) {

                /* get information specific to this email */

                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $message = mb_convert_encoding(imap_fetchbody($inbox, $email_number, 1), "UTF-8", "iso-2022-jp");
                //var_dump($message);
                // $message = imap_fetchbody($inbox, $email_number, 1);
                //house type

                if (preg_match('%物件種.*%', $message, $house_type)) {
                    $house_type = explode('：', $house_type[0]);
                    if (isset($house_type[1]))
                        $fetch_array['house_type'] = $house_type[1];
                    else
                        $fetch_array['house_type'] = "";
                }else {
                    $fetch_array['house_type'] = "";
                }
                //house name

                if (preg_match('%物件名.*%', $message, $house_name)) {
                    $house_name = explode('：', $house_name[0]);
                    if (isset($house_name[1]))
                        $fetch_array['house_name'] = $house_name[1];
                    else
                        $fetch_array['house_name'] = "";
                }elseif (preg_match('%建物名.*%', $message, $house_name)) {
                    $house_name = explode('：', $house_name[0]);
                    if (isset($house_name[1]))
                        $fetch_array['house_name'] = $house_name[1];
                    else
                        $fetch_array['house_name'] = "";
                }else {
                    $fetch_array['house_name'] = "";
                }
                //house address

                if (preg_match('%所在地.*%', $message, $house_address)) {
                    $house_address = explode('：', $house_address[0]);
                    if (isset($house_address[1]))
                        $fetch_array['house_address'] = $house_address[1];
                    else
                        $fetch_array['house_address'] = "";
                }else {
                    $fetch_array['house_address'] = "";
                }
                //rent cost

                if (preg_match('%賃料.*%', $message, $rent_cost)) {
                    $rent_cost = explode('：', $rent_cost[0]);
                    if (isset($rent_cost[1]))
                        $fetch_array['rent_cost'] = $rent_cost[1];
                    else
                        $fetch_array['rent_cost'] = "";
                }else {
                    $fetch_array['rent_cost'] = "";
                }
                //client name

                if (preg_match('%名前.*%', $message, $client_name)) {
                    $client_name = explode('：', $client_name[0]);
                    if (isset($client_name[1]))
                        $fetch_array['client_name'] = $client_name[1];
                    else
                        $fetch_array['client_name'] = "";
                }else {
                    $fetch_array['client_name'] = "";
                }
                //client read way

                if (preg_match('%カナ.*%', $message, $client_read_way)) {
                    $client_read_way = explode('：', $client_read_way[0]);
                    if (isset($client_read_way[1]))
                        $fetch_array['client_read_way'] = $client_read_way[1];
                    else
                        $fetch_array['client_read_way'] = "";
                }else {
                    $fetch_array['client_read_way'] = "";
                }
                //client address

                if (preg_match('%住所.*%', $message, $client_address)) {
                    $client_address = explode('：', $client_address[0]);
                    if (isset($client_address[1]))
                        $fetch_array['client_address'] = $client_address[1];
                    else
                        $fetch_array['client_address'] = "";
                }else {
                    $fetch_array['client_address'] = "";
                }
                //client email

                if (preg_match('%メールアドレス.*%', $message, $client_email)) {
                    $client_email = explode('：', $client_email[0]);
                    if (isset($client_email[1]))
                        $fetch_array['client_email'] = $client_email[1];
                    else
                        $fetch_array['client_email'] = "";
                }else {
                    $fetch_array['client_email'] = "";
                }
                //client phone

                if (preg_match('%電話番号.*%', $message, $client_phone)) {
                    $client_phone = explode('：', $client_phone[0]);
                    if (isset($client_phone[1]))
                        $fetch_array['client_phone'] = $client_phone[1];
                    else
                        $fetch_array['client_phone'] = "";
                }else {
                    $fetch_array['client_phone'] = "";
                }
                //source
                if ($account[$i]['source'] == 'Homes') {
                    $fetch_array['source'] = "Homes";
                } elseif ($account[$i]['source'] == 'SUMO') {
                    $fetch_array['source'] = "SUMO";
                } else {
                    $fetch_array['source'] = "Athome";
                }
                //date sent
                if ($overview[0]->date) {
                    $fetch_array['date_sent'] = $overview[0]->date;
                } else {
                    $fetch_array['date_sent'] = "";
                }

                $order[] = $fetch_array;
            }
        }
    }
    /* close the connection */
    imap_close($inbox);
    $result = $orderClass->create_fetch_email($order);
}
$create = "";
if (isset($_POST['create_new'])) {

    if (isset($_POST['message_id'])) {
        $message_id = $_POST['message_id'];
    } elseif (isset($_GET['message_id'])) {
        $message_id = $_GET['message_id'];
    } else {
        $message_id = "";
    }
    $result = $orderClass->create_order_fetch_email($message_id);
    $create = $result;
}

if (isset($_POST['page_number'])) {
    $page_number = $_POST['page_number'];
} elseif (isset($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
} else {
    $page_number = 1;
}
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
} elseif (isset($_GET['search'])) {
    $search = trim($_GET['search']);
} else {
    $search = "";
}
$max = 25;
$totalItem = $orderClass->get_message_total($search);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;

$offset = $page_number * $max - $max;
$length = $max;


// get message
$messageTotal = array();
$messageTotal = $orderClass->get_message($search, $offset, $length);

$smarty->assign('create', $create);
$smarty->assign('search', $search);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('messages', $messageTotal);

include "footer.php";
?>

