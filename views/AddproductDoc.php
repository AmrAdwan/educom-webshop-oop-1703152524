<?php

include_once "ProductDoc.php";

class AddproductDoc extends ProductDoc
{
  protected function showHeader()
  {
    echo "<h1>Add Product</h1>";
  }

  protected function getError($addProductResult, $key)
  {
    return isset($addProductResult['errors'][$key]) ? $addProductResult['errors'][$key] : '';
  }
  protected function showForm($addProductResult = [])
  {
    if (!isset($addProductResult['addvalid']) || !$addProductResult['addvalid'])
    {
      // Extract form data for convenience
      $addProductData = $addProductResult['addData'] ?? [];
  
      echo "<br><br>";
      echo "<div class=\"formcarry-container\">";
      echo "<form action=\"index.php\" method=\"POST\" enctype=\"multipart/form-data\" class=\"formcarry-form\">";
      echo "<input type=\"hidden\" name=\"page\" value=\"add_product\">";
  
      // Product Name
      echo "<div class=\"input\">";
      echo "<label for=\"prodname\">Product Name:</label>";
      echo "<input type=\"text\" id=\"prodname\" name=\"prodname\" value=\"" . htmlspecialchars($addProductData['prodname'] ?? '') . "\">";
      echo "<span class=\"error\"> *" . $this->getError($addProductResult, 'prodname') . "</span>";
      // echo "</div>";
  
      echo "<br>";
      echo "<br>";
      // Description
      echo "<label for=\"proddescription\">Description:</label>";
      echo "<br>";
      echo "<textarea id=\"proddescription\" name=\"proddescription\" cols=\"30\" rows=\"10\">" . htmlspecialchars($addProductData['proddescription'] ?? '') . "</textarea>";
      echo "<span class=\"error\"> *" . $this->getError($addProductResult, 'proddescription') . "</span>";
  
      echo "<br>";
      echo "<br>";
  
      // Price
      echo "<label for=\"prodprice\">Price (€):</label>";
      echo "<input id=\"prodprice\" name=\"prodprice\" step=\"0.01\" value=\"" . htmlspecialchars($addProductData['prodprice'] ?? '') . "\">";
      echo "<span class=\"error\"> *" . $this->getError($addProductResult, 'prodprice') . "</span>";
  
      echo "<br>";
      echo "<br>";
  
      // Image Upload
      echo "<label for=\"prodimage\">Product Image:</label>";
      echo "<input type=\"file\" id=\"prodimage\" name=\"prodimage\">";
      echo "<span class=\"error\">*" . $this->getError($addProductResult, 'prodimage') . "</span>";
  
      echo "<br>";
      echo "<br>";
  
      // Submit Button
      echo "<input type=\"submit\" value=\"Add Product\">";
      echo "</div>";
  
      echo "</form>";
      echo "</div>";
    }
  }
}

?>