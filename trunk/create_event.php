<?php

include "header.php";

$page="create_event";
if (isset($_POST['title'])) {
    $title = $_POST['title'];
} elseif (isset($_GET['title'])) {
    $title = $_GET['title'];
} else {
    $title = "";
}
if (isset($_POST['start'])) {
    $start = $_POST['start'];
} elseif (isset($_GET['start'])) {
    $start = $_GET['start'];
} else {
    $start = "";
}
if (isset($_POST['end'])) {
    $end = $_POST['end'];
} elseif (isset($_GET['end'])) {
    $end = $_GET['end'];
} else {
    $end = "";
}
if (isset($_POST['url'])) {
    $url = $_POST['url'];
} elseif (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = "";
}
$event= new HOMEEvent();
$result=$event->create_event($title,$start,$end,$url);
echo $result;

//include "footer.php";

