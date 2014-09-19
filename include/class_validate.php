<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HOMEValidate {

    var $error = array();

    function validate($source = array()) {
        if (!empty($source)) {
            foreach ($source as $key => $val) {
                switch ($key) {
                    case('email'):
                        $this->checkEmail($val);
                        break;
                    case('username'):
                        $this->checkEmpty('ユーザー', $val);
                        break;
                    case('firstname'):
                        $this->checkEmpty('名前', $val);
                        break;
                    case('lastname'):
                        $this->checkEmpty('名字', $val);
                        break;
                    case('address'):
                        $this->checkEmpty('住所', $val);
                        break;
                    case ('password'):
                        $this->checkPassword($val);
                        break;
                    case('agent_name'):
                        $this->checkEmpty('店舗名', $val);
                        break;
                    case('agent_address'):
                        $this->checkEmpty('住所', $val);
                        break;
                    case('agent_phone'):
                        $this->checkEmpty('店舗の電話番号', $val);
                        break;
                    case('agent_email'):
                        $this->checkEmailAgent($val);
                        break;
                    case('house_name'):
                        $this->checkEmpty('物件名', $val);
                        break;
                    case('house_address'):
                        $this->checkEmpty('番地', $val);
                        break;
                    case('house_size'):
                        $this->checkEmpty('House size', $val);
                        break;
                    case('house_area'):
                        $this->checkEmpty('エリア', $val);
                        break;
                    case('house_original_price'):
                        $this->checkEmpty('House price', $val);
                        break;
                    case('house_owner_name'):
                        $this->checkEmpty('オーナー名', $val);
                        break;
                    case("broker_company_name"):
                        $this->checkEmpty('管理会社', $val);
                        break;
                    case('broker_company_address'):
                        $this->checkEmpty('番地', $val);
                        break;
                    case('broker_company_phone'):
                        $this->checkEmpty('電話番号', $val);
                        break;
                    case('broker_company_email'):
                        $this->checkEmailBroker($val);
                        break;
                    case("room_number"):
                        $this->checkEmpty('部屋番号', $val);
                        break;
                    case("room_type"):
                        $this->checkEmpty('間取り', $val);
                        break;
                    case("room_size"):
                        $this->checkEmpty('面積', $val);
                        break;
                    case("room_rent"):
                        $this->checkEmpty('賃料', $val);
                        break;
                     case("house_id"):
                        $this->checkEmpty('物件名を選択してください。', $val);
                        break;
                     case("broker_id"):
                        $this->checkEmpty('管理会社', $val);
                        break;
                    case("source_name"):
                        $this->checkEmpty('媒体', $val);
                        break;
                    case("group_name"):
                        $this->checkEmpty('グループ', $val);
                        break;
                    case("city_name"):
                        $this->checkEmpty('都道府県', $val);
                        break;
                    case("district_name"):
                        $this->checkEmpty('市区町村', $val);
                        break;
                    case("city_id"):
                        $this->checkEmpty('都道府県', $val);
                        break;
                    case("street_name"):
                        $this->checkEmpty('大字・通称', $val);
                        break;
                    case("district_id"):
                        $this->checkEmpty('市区町村', $val);
                        break;
                    case("ward_name"):
                        $this->checkEmpty('字・丁目', $val);
                        break;
                    case("street_id"):
                        $this->checkEmpty('大字・通称', $val);
                        break;
                    default :
                        break;
                }
            }
        }
        return $this->error;
    }

    function checkEmail($val) {
        if (!empty($val)) {
            $email = trim($val['email']);

            if (empty($email)) {
                $this->error[] = "Eメールは必須です";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error[] = "Eメールが有効ではありません。";
            } else {
                global $database;

                $email = trim($email);
                $query = "select * from home_user where user_email='{$email}'";
                if (isset($val['user_id']) && !empty($val['user_id']))
                    $query.=" and id<>{$val['user_id']}";
                $result = $database->database_query($query);
                if ($database->database_num_rows($result)) {
                    $this->error[] = "既に使用されているEメールです。メールアドレスを入力してください。";
                }
            }
        }
    }

    function checkEmpty($key, $val) {
        if (!empty($val) && $key == 'ユーザー') {
            global $database;
            $username = trim($val['username']);
            if (empty($username))
                $this->error[] = $key . " が必要です。";
            else {
                $query = "select * from home_user where user_username='{$username}'";
                if (isset($val['user_id']) && !empty($val['user_id']))
                    $query.=" and id<>{$val['user_id']}";

                $result = $database->database_query($query);
                if ($database->database_num_rows($result)) {
                    $this->error[] = $key . " in used. Please enter a new username.";
                }
            }
        }elseif($key !='Username'){
            if(empty($val))
                $this->error[] = $key . " が必要です。";
        }
    }

    function checkPassword($password) {
        // CHECK FOR EMPTY PASSWORDS

        if (!trim($password['pass']) || !trim($password['confirm_pass'])) {
            $this->error[] = "パスワードは必須です";
            return;
        }


// CHECK FOR OLD PASSWORD MATCH
//        if ($check_old && $this->user_password_crypt($password_old) != $this->user_info['user_password'])
//            $this->is_error = 701;
// MAKE SURE BOTH PASSWORDS ARE IDENTICAL

        if ($password['pass'] != $password['confirm_pass']) {
            $this->error[] = "パスワードは不正です。";
            return;
        }


// MAKE SURE PASSWORD IS LONGER THAN 5 CHARS

        if (trim($password['pass']) && strlen($password['pass']) < 6) {
            $this->error[] = "パスワードはミニマム6字で";
            return;
        }


// MAKE SURE PASSWORD IS ALPHANUMERIC
//        if (ereg('[^A-Za-z0-9]', $password))
//            $this->is_error = 704;
    }

    function checkEmailAgent($val) {
        if (!empty($val)) {

            $email = trim($val['agent_email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error[] = "Eメールが有効ではありません。";
            } else {
                global $database;

                $query = "select * from home_agent where agent_email='{$email}'";
                if (isset($val['agent_id']) && !empty($val['agent_id']))
                    $query.=" and id<>{$val['agent_id']}";
                $result = $database->database_query($query);
                if ($database->database_num_rows($result)) {
                    $this->error[] = "既に使用されているEメールです。メールアドレスを入力してください。";
                }
            }
        }
    }

    function checkEmailBroker($val) {
        if (!empty($val)) {

            $email = trim($val['broker_company_email']);
            if (empty($email)) {
                $this->error[] = "Eメールは必須です";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error[] = "Eメールが有効ではありません。";
            } else {
                global $database;

                $query = "select * from home_broker_company where broker_company_email='{$email}'";
                if (isset($val['broker_id']) && !empty($val['broker_id']))
                    $query.=" and id<>{$val['broker_id']}";
                $result = $database->database_query($query);
                if ($database->database_num_rows($result)) {
                    $this->error[] = "既に使用されているEメールです。メールアドレスを入力してください。";
                }
            }
        }
    }

}
