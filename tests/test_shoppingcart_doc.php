<?php
include_once "../views/ShoppingcartDoc.php";

$data = array('page' => 'Shoppingcart', 'title' => 'Shoppingcart', 'header' => 'Shopping cart');

$view = new ShoppingcartDoc($data);
$view->show();
?>