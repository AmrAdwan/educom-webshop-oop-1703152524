<?php
include_once "../views/EditproductDoc.php";

$data = array('page' => 'edit_product', 'title' => 'Edit Product', 'header' => 'Edit Product');

$view = new EditproductDoc($data);
$view->show();
?>