<?php
  include_once "../views/BasicDoc.php";

  $data = array ( 'page' => 'Basic','title' => 'Basic', 'header' => 'Basic');

  $view = new BasicDoc($data);
  $view  -> show();
?>