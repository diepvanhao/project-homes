<?php

/* $Id: class_url.php 256 2009-11-18 02:58:25Z steve $ */

//  THIS CLASS CONTAINS URL-RELATED METHODS.
//  IT IS USED TO RETURN THE CURRENT URL AND CREATE NEW URLS
//  METHODS IN THIS CLASS:
//    se_url()
//    url_create()
//    url_current()
//    url_userdir()
//    url_encode()





class HOMEUrl {

    // INITIALIZE VARIABLES
    var $is_error;   // DETERMINES WHETHER THERE IS AN ERROR OR NOT
    var $url_base;   // CONTAINS THE BASE URL TO WHICH FILENAMES CAN BE APPENDED
    var $convert_urls;  // CONTAINS THE URL CONVERSIONS

    // THIS METHOD SETS THE BASE URL TO WHICH FILENAMES CAN BE APPENDED
    // INPUT:
    // OUTPUT: A STRING REPRESENTING A PATH TO WHICH FILENAMES CAN BE APPENDED TO CREATE URLs

    function HOMEUrl() {
        global $database;
        $server_array = explode("/", $_SERVER['PHP_SELF']);
        $server_array_mod = array_pop($server_array);
        if ($server_array[count($server_array) - 1] == "admin") {
            $server_array_mod = array_pop($server_array);
        }
        $server_info = implode("/", $server_array);
        
//        if (preg_match ("/^www/i", $_SERVER[HTTP_HOST]) === 0 || preg_match ("/^dev1/i", $_SERVER[HTTP_HOST] === 0 )){
//            $_SERVER[HTTP_HOST] = "www." . $_SERVER[HTTP_HOST];
//        } 
        $this->url_base = "http" . (443 == $_SERVER['SERVER_PORT'] ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . $server_info . "/";       
         
    }

    // END HOMEUrl() METHOD
    // THIS METHOD GETS URLS SETTINGS
    // INPUT:
    // OUTPUT: THE ARRAY OF SETTINGS

    function url_current() {
        $current_url_domain = $_SERVER['HTTP_HOST'];
        $current_url_path = $_SERVER['SCRIPT_NAME'];
        $current_url_querystring = $_SERVER['QUERY_STRING'];
        $current_url = "https://" . $current_url_domain . $current_url_path;
        if ($current_url_querystring != "") {
            $current_url .= "?" . $current_url_querystring;
        }
        //$current_url = urlencode($current_url);
        return $current_url;
    }

    // END url_current() METHOD
    // THIS METHOD RETURNS THE PATH TO THE GIVEN USER'S DIRECTORY
    // INPUT: $user_id REPRESENTING A USER'S USER_ID
    // OUTPUT: A STRING REPRESENTING THE RELATIVE PATH TO THE USER'S DIRECTORY

    function url_userdir($user_id) {
        $subdir = $user_id + 999 - (($user_id - 1) % 1000);
        $userdir = "./uploads_user/$subdir/$user_id/";
        return $userdir;
    }

    // END url_userdir() METHOD
    // THIS METHOD RETURNS A URLENCODED VERSION OF THE GIVEN STRING
    // INPUT: $url REPRESENTING ANY STRING
    // OUTPUT: A STRING REPRESENTING A URLENCODED VERSION OF THE GIVEN STRING
    function url_encode($url) {
        return urlencode($url);
    }

    // END url_encode() METHOD
}

// Backwards compatibility
class home_url extends HOMEUrl {

    function home_url() {
        $this->HOMEUrl();
    }

}

?>
