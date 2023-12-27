<?php
include_once "../views/ContactDoc.php";

$data = array('page' => 'Contact', 'title' => 'Contact', 'header' => 'Contact');

$view = new ContactDoc($data);
$view->show();
?>