<?php

/* $Id: class_user.php 2014-2-27 Hao $ */

class HOMEUser {

// INITIALIZE VARIABLES

    var $is_error;   // DETERMINES WHETHER THERE IS AN ERROR OR NOT, CONTAINS RELEVANT ERROR CODE
    var $user_exists;  // DETERMINES WHETHER WE ARE EDITING AN EXISTING USER OR NOT
    var $user_info;   // CONTAINS USER'S INFORMATION FROM HOME_USERS TABLE
    var $user_salt;   // CONTAINS THE SALT USED TO ENCRYPT USER'S PASSWORD
    var $session_info; // CONTAINS THE PRIVACY LEVEL THAT IS ALLOWED TO MODERATE FOR THIS USER

//
    // THIS METHOD SETS INITIAL VARS SUCH AS USER INFO AND LEVEL INFO
//
    // INPUT:
//    $user_unique (OPTIONAL) REPRESENTING AN ARRAY:
//		$user_unique[0] REPRESENTS THE USER'S ID (user_id)
//		$user_unique[1] REPRESENTS THE USER'S USERNAME (user_username)
//		$user_unique[2] REPRESENTS THE USER'S EMAIL (user_email)
//	  $select_fields (OPTIONAL) REPRESENTING AN ARRAY:
//		$select_fields[0] REPRESENTS THE FIELDS TO SELECT FROM THE SE_USERS TABLE
//		$select_fields[1] REPRESENTS THE FIELDS TO SELECT FROM THE SE_PROFILEVALUES TABLE (QUERY WILL NOT RUN AT ALL IF VALUE IS LEFT BLANK)
//		$select_fields[2] REPRESENTS THE FIELDS TO SELECT FROM THE SE_LEVELS TABLE (QUERY WILL NOT RUN AT ALL IF VALUE IS LEFT BLANK)
//		$select_fields[3] REPRESENTS THE FIELDS TO SELECT FROM THE SE_SUBNETS TABLE (QUERY WILL NOT RUN AT ALL IF VALUE IS LEFT BLANK)
//	  
// OUTPUT: 
//    void
//

    function HOMEUser($user_unique = Array('0', '', ''), $select_fields = Array('*', '*', '*', '*')) {

        global $database;

// SET VARS

        $this->is_error = 0;

        $this->user_exists = 0;

        $this->user_info['id'] = 0;


        $user_unique_id = (!empty($user_unique[0]) ? $user_unique[0] : NULL );

        $user_unique_username = (!empty($user_unique[1]) ? $user_unique[1] : NULL );

        $user_unique_email = (!empty($user_unique[2]) ? $user_unique[2] : NULL );


// VERIFY USER_ID/USER_USERNAME/USER_EMAIL IS VALID AND SET APPROPRIATE OBJECT VARIABLES

        if ($user_unique_id || $user_unique_username || $user_unique_email) {

// SET USERNAME AND EMAIL TO LOWERCASE

            $user_username = strtolower($user_unique_username);

            $user_email = strtolower($user_unique_email);


// SELECT USER USING SPECIFIED SELECTION PARAMETER

            $sql_array = array();

            if (!empty($user_unique[0]))
                $sql_array[] = "SELECT {$select_fields[0]} FROM home_user WHERE id='{$user_unique_id}' LIMIT 1";


            if (!empty($user_unique[1]))
                $sql_array[] = "SELECT {$select_fields[0]} FROM home_user WHERE user_username='{$user_username}' LIMIT 1";


            if (!empty($user_unique[2]))
                $sql_array[] = "SELECT {$select_fields[0]} FROM home_user WHERE user_email='{$user_email}' LIMIT 1";


            if (count($sql_array) > 1)
                $sql = '(' . join(') UNION (', $sql_array) . ')';
            else
                $sql = $sql_array[0];


            $user = $database->database_query($sql);

            if ($database->database_num_rows($user) == 1) {

                $this->user_exists = 1;

                $this->user_info = $database->database_fetch_assoc($user);

// SET USER SALT

                $this->user_salt = $this->user_info['user_code'];


// SET DISPLAY NAME (BACKWARDS COMPAT)
//$this->user_displayname = $this->user_info['user_displayname'];
//  $this->user_displayname();
            }
        }
    }

// END SEUser() METHOD
//
    // THIS METHOD SETS A USER'S DISPLAY NAME
//
    // INPUT:
//    void
//
    // OUTPUT: 
//    void
//



    function user_displayname() {

// SET DISPLAY NAME

        if (!empty($this->user_info['user_displayname']) && trim($this->user_info['user_displayname']))
            $this->user_displayname = $this->user_info['user_displayname'];

        elseif (!empty($this->user_info['user_fname']) && !empty($this->user_info['user_lname']) && trim($this->user_info['user_fname']) && trim($this->user_info['user_lname']))
            $this->user_info['user_displayname'] = $this->user_displayname = $this->user_info['user_fname'] . ' ' . $this->user_info['user_lname'];

        elseif (!empty($this->user_info['user_fname']) && trim($this->user_info['user_fname']))
            $this->user_info['user_displayname'] = $this->user_displayname = $this->user_info['user_fname'];

        elseif (!empty($this->user_info['user_lname']) && trim($this->user_info['user_lname']))
            $this->user_info['user_displayname'] = $this->user_displayname = $this->user_info['user_lname'];

        elseif (!empty($this->user_info['user_username']) && trim($this->user_info['user_username']))
            $this->user_info['user_displayname'] = $this->user_displayname = $this->user_info['user_username'];
        else
            $this->user_info['user_displayname'] = $this->user_displayname = $this->user_info['id'];



        $this->user_displayname_short = (!empty($this->user_info['user_fname']) && trim($this->user_info['user_fname']) ? $this->user_info['user_fname'] : $this->user_info['user_username'] );
    }

// END user_displayname() METHOD
//
    // THIS METHOD UPDATES A USER'S DISPLAY NAME IN THE DATABASE
//
    // INPUT:
//    $mode   - Denotes the method used to generate the displayname
//
    // OUTPUT: 
//    void
//
    function user_auth_token_check() {

// We are already logged in? Why are we checking this?

        if ($this->user_exists) {

            return true;
        }

        return $this->user_auth_token_delete(null, true);
    }

    function user_auth_token_delete($id = null, $delete_cookie = true) {
        //detroy cookies
        if (isset($_COOKIE['user_id'])) {
            setcookie('user_id', $_COOKIE['user_id'], time() - 3600);
        }
        if (isset($_COOKIE['user_email'])) {
            setcookie('user_email', $_COOKIE['user_email'], time() - 3600);
        }
        if (isset($_COOKIE['user_pass'])) {

            setcookie('user_pass', $_COOKIE['user_pass'], time() - 3600);
        }
    }

    function user_checkCookies() {

        global $database;

        $session_object = & HOMESession::getInstance();



// Check if user exists

        $user_id = $session_object->get('user_id') ? $session_object->get('user_id') : isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;

        $user_email = $session_object->get('user_email') ? $session_object->get('user_email') : isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : null;

        $user_pass = $session_object->get('user_pass') ? $session_object->get('user_pass') : isset($_COOKIE['user_pass']) ? $_COOKIE['user_pass'] : null;


// Check for auth token

        if (!$user_id) {

            $this->user_auth_token_check();
        }



        if (isset($user_id) && isset($user_email) && isset($user_pass)) {

// Only create if not already exists to help with caching

            if (!$this->user_exists) {

                $this->HOMEUser(Array($user_id));
            }



// VERIFY USER EXISTS, LOGIN COOKIE VALUES ARE CORRECT, AND EMAIL HAS BEEN VERIFIED - ELSE RESET USER CLASS

            switch (TRUE) {

                case (!$this->user_exists ):

                case ( $user_email != $this->user_info['user_email']) :

                case ($user_pass != $this->user_info['user_password'] ):


                    $this->user_clear();

                    break;
            }
        }
    }

// END user_checkCookies() METHOD
// THIS METHOD TRIES TO LOG A USER IN IF THERE IS NO ERROR
// INPUT: $email REPRESENTING THE LOGIN EMAIL
//	  $password REPRESENTING THE LOGIN PASSWORD
//	  $javascript_disabled (OPTIONAL) A BOOLEAN REPRESENTING WHETHER JAVASCRIPT IS DISABLED OR NOT
//	  $persistent (OPTIONAL) A BOOLEAN SPECIFYING WHETHER COOKIES SHOULD BE PERSISTENT OR NOT
// OUTPUT: 

    function user_login($email, $password, $persistent = 0) {

        global $database;

        $this->HOMEUser(Array(0, 0, $email));

        $current_time = time();

        $login_result = 0;


// SHOW ERROR IF NO USER ROW FOUND
        if ($this->user_exists == 0) {
            $this->is_error = "User not exists !!!";
        }


// VALIDATE PASSWORD
        elseif (!trim($password) || $this->user_password_crypt($password) != $this->user_info['user_password']) {

            $this->is_error = "Password or email wrong !!!";
        } elseif ($this->user_info['user_locked'] == 1) {
            $this->is_error = "This account locked !!!";
        }


// INITIATE LOGIN AND ENCRYPT COOKIES
        else {

// SET LOGIN RESULT VAR

            $login_result = TRUE;

// LOG USER IN

            $this->user_setcookies($persistent);
        }
        return array('error' => $this->is_error, 'login' => $login_result);
    }

// END user_login() METHOD
//
    // THIS METHOD SETS USER LOGIN COOKIES
//
    // INPUT:
//    $persistent (OPTIONAL) REPRESENTING WHETHER THE COOKIES SHOULD BE PERSISTENT OR NOT
//
    // OUTPUT: 
//    void
//



    function user_setcookies($persistent = false) {

// TODO: PERSISTENT

        $session_object = & HOMESession::getInstance();



        $user_id = (!empty($this->user_info['id']) ? $this->user_info['id'] : '' );

        $user_email = (!empty($this->user_info['user_email']) ? $this->user_info['user_email'] : '' );

        $user_password = (!empty($this->user_info['user_password']) ? $this->user_info['user_password'] : '');



// We don't need to do this any more because of the auth tokens
// Set cookie parameters
//$cookie_lifetime = ( $persistent ? (60 * 60 * 24 * 31 * 6) : 0 );
//if( $cookie_lifetime )
//{
//  session_set_cookie_params(10);//$cookie_lifetime);
//}
// Get new id for security
        //    $session_object->copy();
// Set user login info

        $session_object->set('user_id', $user_id);

        $session_object->set('user_email', $user_email);

        $session_object->set('user_pass', $user_password);


        //setcookies
        if (isset($user_id) && isset($user_email) && isset($user_password)) {
            if ($user_id && $user_email && $user_password) {
                setcookie('user_id', $user_id, time() + 8 * 3600);
                setcookie('user_email', $user_email, time() +8 * 3600);
                setcookie('user_pass', $user_password, time() +8 * 3600);
            }
        }
// Create new key if logging in, delete old key if logging out

        if (!$user_id) {

            $this->user_auth_token_delete();
        }
    }

// END user_setcookies() METHOD
// THIS METHOD CLEARS ALL THE CURRENT OBJECT VARIABLES
// INPUT:
// OUTPUT:


    function user_clear() {

        $this->is_error = FALSE;

        $this->user_exists = FALSE;

        $this->user_info = array();
    }

// END user_clear() METHOD
// THIS METHOD LOGS A USER OUT
// INPUT:
// OUTPUT:


    function user_logout() {

        global $database;



        $session_object = & HOMESession::getInstance();



// REMOVE AUTH TOKEN
        // $this->user_auth_token_delete();
// CREATE PLAINTEXT USER EMAIL COOKIE WHILE LOGGED OUT

        setcookie("prev_email", $this->user_info['user_email'], time() + 99999999, "/");



        $this->user_clear();

        $this->user_setcookies();
    }

// END user_logout() METHOD
// THIS METHOD VALIDATES USER ACCOUNT INPUT
// INPUT: $email REPRESENTING THE DESIRED EMAIL
//	  $username REPRESENTING THE DESIRED USERNAME
// OUTPUT: 

    function user_password($password_old, $password, $password_confirm, $check_old = 1) {

// CHECK FOR EMPTY PASSWORDS

        if (!trim($password) || !trim($password_confirm) || ($check_old && !trim($password_old)))
            $this->is_error = 51;



// CHECK FOR OLD PASSWORD MATCH

        if ($check_old && $this->user_password_crypt($password_old) != $this->user_info['user_password'])
            $this->is_error = 701;



// MAKE SURE BOTH PASSWORDS ARE IDENTICAL

        if ($password != $password_confirm)
            $this->is_error = 702;



// MAKE SURE PASSWORD IS LONGER THAN 5 CHARS

        if (trim($password) && strlen($password) < 6)
            $this->is_error = 703;



// MAKE SURE PASSWORD IS ALPHANUMERIC

        if (ereg('[^A-Za-z0-9]', $password))
            $this->is_error = 704;
    }

// END user_password() METHOD
// THIS METHOD ENCRYPTS A USERS PASsWORD
// INPUT: UNENCRYPTED PASSWORD
// OUTPUT: ENCRYPTED PASSWORD


    function user_password_crypt($user_password) {

        global $setting;



        if (!$this->user_exists) {

            $method = 1;

            $this->user_salt = randomcode(8);
        } else {

            $method = 1;
        }

// For new methods       
        if ($method > 0) {

            if (!empty($this->user_salt)) {

                list($salt1, $salt2) = str_split($this->user_salt, ceil(strlen($this->user_salt) / 2));

                $salty_password = $salt1 . $user_password . $salt2;
            } else {

                $salty_password = $user_password;
            }
        }

        switch ($method) {

// crypt()

            default:

            case 0:

                $user_password_crypt = crypt($user_password, '$1$' . str_pad(substr($this->user_salt, 0, 8), 8, '0', STR_PAD_LEFT) . '$');

                break;

// md5()

            case 1:

                $user_password_crypt = md5($salty_password);

                break;



// sha1()

            case 2:

                $user_password_crypt = sha1($salty_password);

                break;



// crc32()

            case 3:

                $user_password_crypt = sprintf("%u", crc32($salty_password));

                break;
        }

        return $user_password_crypt;
    }

// END user_password_crypt() METHOD
// THIS METHOD RETURNS A SUBNETWORK ID DEPENDENT ON GIVEN INPUTS
// INPUT: $email (OPTIONAL) REPRESENTING THE USER'S EMAIL 
//	  $category (OPTIONAL) REPRESENTING THE USER'S PROFILE CATEGORY
//	  $profile_info (OPTIONAL) REPRESENTING THE USER'S PROFILE INFO
// OUTPUT: RETURNS AN ARRAY CONTAINING THE SUBNETWORK ID AND RESULT STRINGS

    function user_photo_upload($photo_name, $is_admin = FALSE, $user_id = "") {

        global $database, $url;


// ENSURE USER DIRECTORY IS ADDED

        $user_directory = $url->url_userdir($user_id);

        if ($is_admin) { 
            $user_directory = "." . $user_directory;
        }

        $user_path_array = explode("/", $user_directory);

        array_pop($user_path_array);

        array_pop($user_path_array);

        $subdir = implode("/", $user_path_array) . "/";

        if (!is_dir($subdir)) {
            mkdir($subdir, 0777);

            chmod($subdir, 0777);

            $handle = fopen($subdir . "index.php", 'x+');

            fclose($handle);
        }

        if (!is_dir($user_directory)) {
            mkdir($user_directory, 0777);

            chmod($user_directory, 0777);

            $handle = fopen($user_directory . "/index.php", 'x+');

            fclose($handle);
        }

// SET KEY VARIABLES

        $file_maxsize = "4194304";

        $file_exts = explode(",", str_replace(" ", "", strtolower('jpg,jpeg,gif,png')));

        $file_types = explode(",", str_replace(" ", "", strtolower("image/jpeg, image/jpg, image/jpe, image/pjpeg, image/pjpg, image/x-jpeg, x-jpg, image/gif, image/x-gif, image/png, image/x-png")));

        $file_maxwidth = 200;

        $file_maxheight = 200;

        $photo_newname = "0_" . rand(1000, 9999) . ".jpg";

        $file_dest = $url->url_userdir($user_id) . $photo_newname;

        $thumb_dest = substr($file_dest, 0, strrpos($file_dest, ".")) . "_thumb" . substr($file_dest, strrpos($file_dest, "."));

        if ($is_admin) { 
            $file_dest = "." . $file_dest;
            $thumb_dest = "." . $thumb_dest;
        }

        $new_photo = new home_upload();

        $new_photo->new_upload($photo_name, $file_maxsize, $file_exts, $file_types, $file_maxwidth, $file_maxheight);


// UPLOAD AND RESIZE PHOTO IF NO ERROR

        if (!$new_photo->is_error) {
// DELETE OLD AVATAR IF EXISTS
            $this->user_photo_delete($user_id);
// UPLOAD THUMB

            $new_photo->upload_thumb($thumb_dest);



// CHECK IF IMAGE RESIZING IS AVAILABLE, OTHERWISE MOVE UPLOADED IMAGE

            if ($new_photo->is_image) {

                $new_photo->upload_photo($file_dest);
            } else {

                $new_photo->upload_file($file_dest);
            }



// UPDATE USER INFO WITH IMAGE IF STILL NO ERROR

            if (!$new_photo->is_error) {
                //store thumb
                $thumb = explode(".", $photo_newname);
                $thumb = $thumb[0] . "_thumb" . "." . $thumb[1];

                $database->database_query("UPDATE home_user SET user_photo='{$photo_newname}',user_path_photo='{$user_directory}',user_path_thumb='{$thumb}' WHERE id='{$user_id}' LIMIT 1");

                // $this->user_info['user_photo'] = $photo_newname;
            }
        }



        $this->is_error = $new_photo->is_error;
    }

// END user_photo_upload() METHOD
// THIS METHOD DELETES A USER PHOTO
// INPUT: 
// OUTPUT: 

    function user_photo_delete($user_id = "") {

        global $database;

        //get path photo
        $query = "select user_path_photo, user_path_thumb from home_user where id={$user_id} ";
        $result = $database->database_query($query);
        $row = $database->database_fetch_assoc($result);
        
        $namePhoto = explode("_", $row['user_path_thumb']);
        $namePhoto = $namePhoto[0] . "_" . $namePhoto[1].'.jpg';

        if ($row) {

            @unlink($row['user_path_photo'] . $row['user_path_thumb']);
            @unlink($row['user_path_photo'] . $namePhoto);
        }
    }

// END user_photo_delete() METHOD
// THIS METHOD RETURNS THE TOTAL NUMBER OF FRIENDS
// INPUT: $direction (OPTIONAL) REPRESENTING A "0" FOR OUTGOING CONNECTIONS AND A "1" FOR INCOMING CONNECTIONS
//	  $friend_status (OPTIONAL) REPRESENTING THE FRIEND STATUS (1 FOR CONFIRMED, 0 FOR PENDING REQUESTS)
//	  $user_details (OPTIONAL) REPRESENTING WHETHER THE QUERY SHOULD JOIN TO THE USER TABLE OR NOT
//	  $where (OPTIONAL) REPRESENTING ADDITIONAL THINGS TO INCLUDE IN THE WHERE CLAUSE
// OUTPUT: AN INTEGER REPRESENTING THE NUMBER OF FRIENDS


    function user_create($user_username, $user_password, $user_confirm_password, $user_fname, $user_lname, $user_address, $user_email, $user_phone, $user_gender, $user_birthday, $user_photo, $user_position, $user_authorities, $user_target, $user_locked = 0) {

        global $database, $url;

// ENCODE PASSWORD WITH MD5

        $crypt_password = $this->user_password_crypt($user_password);

        $signup_code = $user_salt = $this->user_salt;


// ADD USER TO USER TABLE

        $database->database_query("

      INSERT INTO home_user (

        agent_id,

        user_username,

        user_password,

        user_fname,
        
        user_lname,
        
        user_code,    
        
        user_address,

        user_email,

        user_phone,

        user_gender,

        user_birthday,

        user_photo,

        user_authorities,

        user_position,

        user_locked,
        
        user_target
        

      ) VALUES (

        '',

        '{$user_username}',

        '{$crypt_password}',
           
        '{$user_fname}',
        
        '{$user_lname}',

        '{$signup_code}',  
        
        '{$user_address}',

        '{$user_email}',

        '{$user_phone}',

        '{$user_gender}',

        '{$user_birthday}',

        '{$user_photo}',

        '{$user_authorities}',

        '{$user_position}',

        '{$user_locked}',
            
        '{$user_target}'
            

      )

    ");
// RETRIEVE USER ID

        $user_id = $database->database_insert_id();

        if ($user_id)
            $result = TRUE;
        else
            $result = FALSE;


// ADD USER DIRECTORY

        $user_directory = $url->url_userdir($user_id);

        $user_path_array = explode("/", $user_directory);

        array_pop($user_path_array);

        array_pop($user_path_array);

        $subdir = implode("/", $user_path_array) . "/";


        if (!is_dir($subdir)) {

            mkdir($subdir, 0777);

            chmod($subdir, 0777);

            $handle = fopen($subdir . "index.php", 'x+');

            fclose($handle);
        }

        if (!is_dir($user_directory)) {

            mkdir($user_directory, 0777);

            chmod($user_directory, 0777);

            $handle = fopen($user_directory . "/index.php", 'x+');

            fclose($handle);
        }

//UPLOAD PHOTO USER IF INPUT PHOTO FILE

        if ($user_photo)
            $this->user_photo_upload('photo', false, $user_id);




// SEND RANDOM PASSWORD IF NECESSARY
//        if ($setting['setting_signup_randpass']) {
//
//            send_systememail('newpassword', $this->user_info['user_email'], Array($this->user_displayname, $this->user_info['user_email'], $signup_password, "<a href=\"" . $url->url_base . "login.php\">" . $url->url_base . "login.php</a>"), FALSE, $smtp);
//        }
//
//// SEND VERIFICATION EMAIL IF REQUIRED
//
//        if ($setting['setting_signup_verify']) {
//
//            $verify_code = md5($this->user_info['user_code']);
//
//            $time = time();
//
//            $verify_link = $url->url_base . "signup_verify.php?u={$this->user_info['user_id']}&verify={$verify_code}&d={$time}";
//
//            send_systememail('verification', $this->user_info['user_email'], Array($this->user_displayname, $this->user_info['user_email'], "<a href=\"$verify_link\">$verify_link</a>"), FALSE, $smtp);
//        }
//
//
//
//// SEND WELCOME EMAIL IF REQUIRED (AND IF VERIFICATION EMAIL IS NOT BEING SENT)
//
//        if ($setting['setting_signup_welcome'] && !$setting['setting_signup_verify']) {
//
//            send_systememail('welcome', $this->user_info['user_email'], Array($this->user_displayname, $this->user_info['user_email'], $signup_password, "<a href=\"" . $url->url_base . "login.php\">" . $url->url_base . "login.php</a>"), FALSE, $smtp);
//        }
        return $result;
    }

// END user_create() METHOD
// THIS METHOD DELETES THE USER CURRENTLY ASSOCIATED WITH THIS OBJECT
// INPUT: 
// OUTPUT:
//end add code

    function update($user_username, $user_password, $user_fname, $user_lname, $user_address, $user_email, $user_phone, $user_gender, $user_birthday, $user_photo, $user_position, $user_authorities, $user_target, $agent_id, $user_id) {
        global $database, $url;
        $crypt_password = $this->user_password_crypt($user_password);
        
        $signup_code = $user_salt = $this->user_salt;
        $query = "update home_user set
                `agent_id`='{$agent_id}',
                `user_username`='{$user_username}',     
                 `user_password`='{$crypt_password}',
                `user_fname`='{$user_fname}',
                `user_lname`='{$user_lname}',
                 `user_code`='{$signup_code}',
                `user_address`='{$user_address}',
               `user_email`='{$user_email}',
                `user_phone`='{$user_phone}',
                `user_gender`='{$user_gender}',
                `user_birthday`='{$user_birthday}',                     
                `user_authorities`='{$user_authorities}',
                 `user_position`='{$user_position}',
                 `user_target`='{$user_target}'
               
                ";
        if ($user_photo)
            $query.=",". "`user_photo`='{$user_photo}'";
        $query.=" where id={$user_id}";
//        echo $query;
//        die();
        $result = $database->database_query($query);
        if ($user_photo)
            $this->user_photo_upload('photo', false, $user_id);
        return $result;
    }

    function getUsers() {
        global $database;
        $query = "select * from home_user where id<>{$this->user_info['id']} and agent_id=0";
        $result = $database->database_query($query);
        $users = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $user['id'] = $row['id'];
            $user['user_fname'] = $row['user_fname'];
            $user['user_lname'] = $row['user_lname'];
            $users[] = $user;
        }
        return $users;
    }

    function getAllUsers() {
        global $database;
        $query = "select * from home_user order by user_fname ASC";
        $result = $database->database_query($query);
        $users = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $user['id'] = $row['id'];
            $user['user_fname'] = $row['user_fname'];
            $user['user_lname'] = $row['user_lname'];
            $users[] = $user;
        }
        return $users;
    }
    
    function getTotalItem($search) {
        global $database;

        $query = "select * from home_user";

        if (!empty($search))
            $query.=" where user_fname like '%{$search}%' or user_lname like '%{$search}%'";
        $result = $database->database_query($query);
        $row = $database->database_num_rows($result);
        return $row;
    }

    function getAccount($search = "", $offset = 0, $length = 50) {
        global $database;

        $query = "select * from home_user";
        if (!empty($search))
            $query.=" where user_fname like '%{$search}%' or user_lname like '%{$search}%'";

        $query.=" limit $offset,$length";
        //echo $query;
        $result = $database->database_query($query);
        $user_arr = array();
        while ($row = $database->database_fetch_assoc($result)) {
            $user['id'] = $row['id'];
            $user['user_username'] = $row['user_username'];
            $user['user_fname'] = $row['user_fname'];
            $user['user_lname'] = $row['user_lname'];
            $user['user_address'] = $row['user_address'];
            $user['user_email'] = $row['user_email'];
            $user['user_phone'] = $row['user_phone'];
            $user['user_gender'] = $row['user_gender'];
            $user['user_birthday'] = $row['user_birthday'];
            $user['user_authorities'] = $row['user_authorities'];
            $user['user_position'] = $row['user_position'];
            $user['user_target'] = $row['user_target'];
            $user['user_path_photo'] = $row['user_path_photo'];
            $user['user_path_thumb'] = $row['user_path_thumb'];
            $user['user_locked'] = $row['user_locked'];
            $user['user_photo'] = $row['user_photo'];
            $user_arr[] = $user;
        }
        return $user_arr;
    }

    function getAccountById($user_id) {
        if ($user_id) {
            global $database;
            $query = "select * from home_user where id={$user_id}";
            $result = $database->database_query($query);
            return $database->database_fetch_assoc($result);
        } else {
            return null;
        }
    }

}

// Backwards compat

class home_user extends HOMEUser {

    function home_user($user_unique = Array('0', '', ''), $select_fields = Array('*', '*', '*', '*')) {

        $this->HOMEUser($user_unique, $select_fields);
    }

}

?>