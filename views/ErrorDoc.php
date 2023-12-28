<?php
include_once "BasicDoc.php";

class ErrorDoc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1>404</h1>";
  }

  protected function showContent()
  {
    echo "<p>Sorry, the page you are looking for does not exist.</p>";
  }

}

?>