<?php
include_once "../views/HomeDoc.php";

$data = array('page' => 'Home', 'title' => 'Home', 'header' => 'Home');

$view = new HomeDoc($data);
$view->show();
?>