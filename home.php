<?php

$page="home";
include "header.php";

$orderClass = new HOMEOrder();

$smarty->assign('messages', $orderClass->getHomeMessages());

include "footer.php";
