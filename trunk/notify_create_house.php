<?php

include "header.php";
$page = "notify_create_house";

if (isset($_POST['content'])) {
    $content = $_POST['content'];
} elseif (isset($_GET['content'])) {
    $content = $_GET['content'];
} else {
    $content = "";
}
if (isset($_POST['url_return'])) {
    $url_return = $_POST['url_return'];
} elseif (isset($_GET['url_return'])) {
    $url_return = $_GET['url_return'];
} else {
    $url_return = "";
}

$smarty->assign('content',$content);
$smarty->assign('url_return',$url_return);

include "footer.php";

