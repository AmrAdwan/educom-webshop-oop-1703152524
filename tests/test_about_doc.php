<?php
include_once "../views/AboutDoc.php";

$data = array('page' => 'About', 'title' => 'About', 'header' => 'About');

$view = new AboutDoc($data);
$view->show();
?>