<?php

include_once "ProductDoc.php";

class EditproductDoc extends ProductDoc
{
  protected function showHeader()
  {
    echo "<h1>Edit Product</h1>";
  }

  protected function showContent()
  {
    // $editProductResult = $this->model->getData('editData');

    $productId = $_GET['product_id'] ?? $_POST['product_id'];
    $product = getProductById($productId);
    $editProductResult = $this->model->validateEditProduct($product);
    $this->showForm($editProductResult);

  }
  protected function getError($editProductResult, $key)
  {
    return isset($editProductResult['errors'][$key]) ? $editProductResult['errors'][$key] : '';
  }
  protected function showForm($editProductResult = [])
  {
    if (!isset($editProductResult['editvalid']) || !$editProductResult['editvalid'])
    {
      // Extract form data for convenience
      $editProductData = $editProductResult['editData']?? [];

      echo "<br><br>";
      echo "<div class=\"formcarry-container\">";
      echo "<form action=\"index.php\" method=\"POST\" enctype=\"multipart/form-data\" class=\"formcarry-form\">";
      echo "<input type=\"hidden\" name=\"page\" value=\"edit_product\">";
      echo "<input type=\"hidden\" name=\"product_id\" value=\"" . ($editProductData['editid']?? '') . "'>";



      // Product Name
      echo "<div class=\"input\">";
      echo "<label for='editname'>Product Name:</label>";
      echo "<input type=\"text\" id=\"editname\" name=\"editname\" value=\"" . ($editProductData['editname'] ?? '') . "\">";
      echo "<span class=\"error\"> *" . $this->getError($editProductResult, 'editname') . "</span>";
      // echo "</div>";


      echo "<br>";
      echo "<br>";
      // Description
      echo "<label for=\"editdescription\">Description:</label>";
      echo "<br>";
      echo "<textarea id=\"editdescription\" name=\"editdescription\" cols=\"30\" rows=\"10\">" . ($editProductData['editdescription']?? '') . "</textarea>";
      echo "<span class=\"error\"> *" . $this->getError($editProductResult, 'editdescription') . "</span>";

      echo "<br>";
      echo "<br>";

      // Price
      echo "<label for=\"editprice\">Price (€):</label>";
      echo "<input id=\"editprice\" name=\"editprice\" step=\"0.01\" value=\"" . ($editProductData['editprice']?? '') . "\">";
      echo "<span class=\"error\"> *" . $this->getError($editProductResult, 'editprice') . "</span>";

      echo "<br>";
      echo "<br>";

      // Image Upload
      echo "<label for=\"editimage\">Product Image:</label>";
      echo "<input type=\"file\" id=\"editimage\" name=\"editimage\" . value\"" . ($editProductData['editimage']?? '') . "\">";
      echo "<span class=\"error\">*" . $this->getError($editProductResult, 'editimage') . "</span>";

      echo "<br>";
      echo "<br>";

      // Submit Button
      echo "<input type=\"submit\" value=\"Edit Product\">";
      echo "</div>";

      echo "</form>";
      echo "</div>";
    }
  }

}


?>