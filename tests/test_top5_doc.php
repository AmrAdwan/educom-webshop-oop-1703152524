<?php
include_once "../views/Top5Doc.php";

$data = array('page' => 'Top5', 'title' => 'Top5', 'header' => 'Top 5');

$view = new Top5Doc($data);
$view->show();
?>