<?php
include_once "../views/WebshopDoc.php";

$data = array('page' => 'Webshop', 'title' => 'Webshop', 'header' => 'Webshop');

$view = new WebshopDoc($data);
$view->show();
?>