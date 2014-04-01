<?php
// ENSURE THIS IS BEING INCLUDED IN AN HOME SCRIPT
defined('HOME_PAGE') or exit();

$url_page= explode("/", $url->url_current());

$smarty->assign('url_page',$url_page[count($url_page)-1]);
$smarty->assign('url', $url);
$smarty->assign('page',$page);
$smarty->assign('user',$user);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// DISPLAY PAGE
$smarty->display("$page.tpl");

exit();