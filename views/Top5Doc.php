<?php
include_once "BasicDoc.php";

class Top5Doc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1>Top 5</h1>";
  }

  protected function showContent()
  {
    // $topProducts = getTop5Products();
    $topProducts = $this->model->getTop5Products();

  
    // echo "<div class='top-products'>";
    echo "<div class=\"row\">";
    foreach ($topProducts as $product)
    {
      echo "<br>";
      // echo "<br>";
      // echo "<br>";
      // echo "<div class='product'>";
      echo "<div class=\"column\">";
      echo "<a href='index.php?page=product_details&product_id=" . $product->id . "' style='cursor: pointer;'>";
      echo "<img src='Images/" . $product->file_name . "' alt='" . $product->name . "' style='width: 45%;' />";
      echo "</a>";
      echo "<h3>" . $product->name . "</h3>";
      echo "<p>Total Sold: " . $product->total_quantity . "</p>";
      echo "</div>";
    }
    echo "</div>";
  }

}


?>