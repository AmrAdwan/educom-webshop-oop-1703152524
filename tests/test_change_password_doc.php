<?php
include_once "../views/ChangePasswordDoc.php";

$data = array('page' => 'ChangePassword', 'title' => 'Change Password', 'header' => 'Change Password');

$view = new ChangePasswordDoc($data);
$view->show();
?>