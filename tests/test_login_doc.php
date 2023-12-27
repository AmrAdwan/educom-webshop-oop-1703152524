<?php
include_once "../views/LoginDoc.php";

$data = array('page' => 'Login', 'title' => 'Login', 'header' => 'Login');

$view = new LoginDoc($data);
$view->show();
?>