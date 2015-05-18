<?php

include "header.php";
$page = "fetch_email";
ini_set('max_execution_time', 3000);
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
if (!@HOMEOrder::checkPermisson('email')) {
    header('Location: ./restrict.php');
    exit();
}

if ($user->user_info['user_locked']) {
    header('Location: ./locked.php');
    exit();
}


//get content email homes, sumo and athome
$hostname = '{mail.roompia.jp}';
$username = "";
$password = "";
$error = "";
$account = array(
    array(
        'agent' => '渋谷店',
        'username' => 'shibuya_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '池袋店',
        'username' => 'ikebukuro_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '上野店',
        'username' => 'ueno_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '新宿店',
        'username' => 'shinjyuku_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '中目黒店',
        'username' => 'nakameguro_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '渋谷道玄坂店',
        'username' => 'dougenzaka_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '高田馬場店',
        'username' => 'takadanobaba_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '早稲田店',
        'username' => 'waseda_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '相模大野店',
        'username' => 'sagamioono_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '六本木駅前店',
        'username' => 'roppongiekimae_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '秋葉原店',
        'username' => 'akihabara_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => 'WEB課',
        'username' => 'corporate_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '錦糸町店',
        'username' => 'kinshicho_info@roompia.jp',
        'password' => 'ambad001'
    ),
    array(
        'agent' => '銀座店',
        'username' => 'ginza_info@roompia.jp',
        'password' => 'ambad001'
    )
);

$orderClass = new HOMEOrder();

//get agent user account
$agent = new HOMEAgent();

$agent_account = $agent->getAgentById($user->user_info['agent_id']);

//get account email respective agent
for ($i = 0; $i < count($account); $i++) {
    if ($agent_account['agent_name'] == $account[$i]['agent']) {
        $username = $account[$i]['username'];
        $password = $account[$i]['password'];
        break;
    }
}


if (isset($_POST['get_new'])) {
    $order = array();
    //for ($i = 0; $i < count($account); $i++) {
    if ($username && $password) {
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect : ' . imap_last_error());
        $emails[] = imap_search($inbox, 'FROM support@homes.co.jp');
        $emails[] = imap_search($inbox, 'FROM jds_system@jds.jutakujoho.jp');
        $emails[] = imap_search($inbox, 'FROM mailtofax@athome.jp');


        /* if emails are returned, cycle through each... */
        if ($emails) {

            /* put the newest emails on top */
            //rsort($emails);
            /* for every email... */
            foreach ($emails as $email_number) {
                if ($email_number) {
                    foreach ($email_number as $email) {
                        /* get information specific to this email */

                        $overview = imap_fetch_overview($inbox, $email, 0);
                        $message = mb_convert_encoding(imap_fetchbody($inbox, $email, 1), "UTF-8", "iso-2022-jp");

                        // $message = imap_fetchbody($inbox, $email, 1);var_dump($message);die();
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

                        if (preg_match('%support@homes.co.jp%', $message, $source)) {
                            $fetch_array['source'] = "Homes";
                        } elseif (preg_match('%mailcenter@athome.co.jp%', $message, $source)) {
                            $fetch_array['source'] = "Athome";
                        } elseif (preg_match('%jds_support@r.recruit.co.jp%', $message, $source)) {
                            $fetch_array['source'] = "SUMO";
                        }
                        //date sent
                        if ($overview[0]->date) {
                            $date_sent = explode(',', $overview[0]->date);
                            $date_sent = trim($date_sent[1]);
                            $date_sent = explode('+', $date_sent);
                            $date_sent = trim($date_sent[0]);
                            $fetch_array['date_sent'] = strtotime($date_sent);
                        } else {
                            $fetch_array['date_sent'] = "";
                        }

                        $order[] = $fetch_array;
                    }
                }
            }
        }
        /* close the connection */
        imap_close($inbox);
        $result = $orderClass->create_fetch_email($order, $agent_account['id']);
    } else {
        $error = "Your account is not add to agent yet.";
    }
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
$max = 50;
$totalItem = $orderClass->get_message_total($search, $agent_account['id']);

$totalPage = floor($totalItem / $max);

if ($totalItem % $max > 0)
    $totalPage = $totalPage + 1;
if ($page_number > $totalPage)
    $page_number = 1;

$offset = $page_number * $max - $max;
$length = $max;


// get message
$messageTotal = array();
$messageTotal = $orderClass->get_message($search, $offset, $length, $agent_account['id']);

$smarty->assign('create', $create);
$smarty->assign('search', $search);
$smarty->assign('error', $error);
$smarty->assign('page_number', $page_number);
$smarty->assign('totalPage', $totalPage);
$smarty->assign('messages', $messageTotal);

include "footer.php";
?>

