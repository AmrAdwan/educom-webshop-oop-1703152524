<?php
include_once "../views/RegisterDoc.php";

$data = array('page' => 'Register', 'title' => 'Register', 'header' => 'Register');

$view = new RegisterDoc($data);
$view->show();
?>