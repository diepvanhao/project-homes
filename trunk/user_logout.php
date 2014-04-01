<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "header.php";

$page="user_logout";
$user->user_logout();
header('Location: ./index.php');
include "footer.php";
