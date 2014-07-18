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
                        $this->checkEmpty('Username', $val);
                        break;
                    case('firstname'):
                        $this->checkEmpty('First name', $val);
                        break;
                    case('lastname'):
                        $this->checkEmpty('Last name', $val);
                        break;
                    case('address'):
                        $this->checkEmpty('Address', $val);
                        break;
                    case ('password'):
                        $this->checkPassword($val);
                        break;
                    case('agent_name'):
                        $this->checkEmpty('Agent name', $val);
                        break;
                    case('agent_address'):
                        $this->checkEmpty('Number address', $val);
                        break;
                    case('agent_phone'):
                        $this->checkEmpty('Agent phone number', $val);
                        break;
                    case('agent_email'):
                        $this->checkEmailAgent($val);
                        break;
                    case('house_name'):
                        $this->checkEmpty('House name', $val);
                        break;
                    case('house_address'):
                        $this->checkEmpty('House number', $val);
                        break;
                    case('house_size'):
                        $this->checkEmpty('House size', $val);
                        break;
                    case('house_area'):
                        $this->checkEmpty('House area', $val);
                        break;
                    case('house_original_price'):
                        $this->checkEmpty('House price', $val);
                        break;
                    case('house_owner_name'):
                        $this->checkEmpty('House owner name', $val);
                        break;
                    case("broker_company_name"):
                        $this->checkEmpty('Broker company name', $val);
                        break;
                    case('broker_company_address'):
                        $this->checkEmpty('House number', $val);
                        break;
                    case('broker_company_phone'):
                        $this->checkEmpty('Phone number', $val);
                        break;
                    case('broker_company_email'):
                        $this->checkEmailBroker($val);
                        break;
                    case("room_number"):
                        $this->checkEmpty('Room number', $val);
                        break;
                    case("room_type"):
                        $this->checkEmpty('Room type', $val);
                        break;
                    case("room_size"):
                        $this->checkEmpty('Room size', $val);
                        break;
                    case("room_rent"):
                        $this->checkEmpty('Room rent', $val);
                        break;
                     case("house_id"):
                        $this->checkEmpty('Select house', $val);
                        break;
                     case("broker_id"):
                        $this->checkEmpty('Broker company', $val);
                        break;
                    case("source_name"):
                        $this->checkEmpty('Source name', $val);
                        break;
                    case("city_name"):
                        $this->checkEmpty('City name', $val);
                        break;
                    case("district_name"):
                        $this->checkEmpty('District name', $val);
                        break;
                    case("city_id"):
                        $this->checkEmpty('City', $val);
                        break;
                    case("street_name"):
                        $this->checkEmpty('Street name', $val);
                        break;
                    case("district_id"):
                        $this->checkEmpty('District', $val);
                        break;
                    case("ward_name"):
                        $this->checkEmpty('Ward name', $val);
                        break;
                    case("street_id"):
                        $this->checkEmpty('Street', $val);
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
        if (!empty($val) && $key == 'Username') {
            global $database;
            $username = trim($val['username']);
            if (empty($username))
                $this->error[] = $key . " is required";
            else {
                $query = "select * from home_user where user_username='{$username}'";
                if (isset($val['user_id']) && !empty($val['user_id']))
                    $query.=" and id<>{$val['user_id']}";

                $result = $database->database_query($query);
                if ($database->database_num_rows($result)) {
                    $this->error[] = $key . " in use. Please enter a new username.";
                }
            }
        }elseif($key !='Username'){
            if(empty($val))
                $this->error[] = $key . " is required";
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
            $this->error[] = "Passwords do not match";
            return;
        }


// MAKE SURE PASSWORD IS LONGER THAN 5 CHARS

        if (trim($password['pass']) && strlen($password['pass']) < 6) {
            $this->error[] = "Password 6 characters minimum ";
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
