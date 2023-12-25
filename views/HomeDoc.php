<?php
include_once "BasicDoc.php";
class HomeDoc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1>Home</h1>";
  }

  protected function showContent()
  { 
    echo "<p> Welcome to the homepage of this website!</p>";
  }
}

?>