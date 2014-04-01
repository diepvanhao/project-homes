<?php
header('Content-Type: text/html; charset=utf-8');
//require('include/config/config.php');
//
//$smarty=new smarty_class();
define('HOME_DEBUG', FALSE);

define('HOME_PAGE', TRUE);

define('HOME_ROOT', realpath(dirname(__FILE__)));

define('HOME_HEADER', TRUE);

set_include_path(get_include_path() . PATH_SEPARATOR . realpath("./"));

if (!isset($page))
    $page = "";
// INITIATE SMARTY

include "include/class_smarty.php";

$smarty = & HOMESmarty::getInstance();
 
include "include/database_config.php";

include "include/class_database.php";

include "include/class_user.php";

include "include/class_url.php";

include "include/class_validate.php";

include "include/functions_general.php";

include 'include/class_session.php';

include 'include/class_upload.php';

include 'include/class_agent.php';

include 'include/class_house.php';

include 'include/class_broker.php';

include 'include/class_customer.php';
// INITIATE DATABASE CONNECTION

$database = & HOMEDatabase::getInstance();

$url=new HOMEUrl();
// ENSURE NO SQL INJECTIONS THROUGH POST OR GET ARRAYS

$_POST = security($_POST);

$_GET = security($_GET);

$_COOKIE = security($_COOKIE);

// CREATE USER OBJECT AND ATTEMPT TO LOG USER IN

$user = new HOMEUser();

$user->user_checkCookies();

// CANNOT ACCESS USER-ONLY AREA IF NOT LOGGED IN
//var_dump($user);//die();
