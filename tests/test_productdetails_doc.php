<?php
include_once "../views/ProductdetailsDoc.php";

$data = array('page' => 'Product_details', 'title' => 'Product Details', 'header' => 'Top 5');

$view = new ProductdetailsDoc($data);
$view->show();
?>