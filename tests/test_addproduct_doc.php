<?php
include_once "../views/AddproductDoc.php";

$data = array('page' => 'add_product', 'title' => 'Add Product', 'header' => 'Add Product');

$view = new AddproductDoc($data);
$view->show();
?>