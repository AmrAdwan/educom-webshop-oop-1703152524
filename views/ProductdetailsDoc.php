<?php
include_once "BasicDoc.php";


class ProductdetailsDoc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1>Product Details</h1>";
  }

  protected function showContent()
  {
    // Retrieve product data from the model
    // $product = $this->model->getData('product');
    // $product = $this->model->product;
    $productId = $_GET['product_id'];
    $this->model->product = $this->model->getDetailsData($productId);
    $product = $this->model->product;

    if ($product)
    {
      echo "<br>";
      echo "<div class='product-detail'>";
      echo "<img src='Images/" . htmlspecialchars($product['file_name']) . "' alt='" . htmlspecialchars($product['name']) . "' style='width: 60%;' />";
      echo "<h2>" . htmlspecialchars($product['name']) . "</h2>";
      echo "<h3>Price: €" . htmlspecialchars($product['price']) . "</h3>";
      echo "<h3>Description: " . htmlspecialchars($product['description']) . "</h3>";
      echo "</div>";

      if ($this->model->isUserLoggedIn())
      {
        // Add to Cart Form
        echo "<form action='index.php' method='post' onsubmit='redirectToCart()'>";
        echo "<input type='hidden' name='page' value='shoppingcart'>";
        echo "<input type='hidden' name='action' value='add'>";
        echo "<input type='hidden' name='product_id' value='" . $product['id'] . "'>";
        echo "<button style=\"font-size:16px\"><i class=\"fa fa-shopping-cart\"> Add to Cart</i></button>";
        echo "</form>";

        // Edit Product Button
        if ($this->model->isUserAdmin())
        {
          echo "<input type='hidden' name='product_id' value='" . $product['id'] . "'>";
          echo "<a href='index.php?page=edit_product&product_id=" . $product['id'] .
            "'><button type=\"button\" class=\"btn btn-primary btn-block\"> Edit Product </button></a>";
        }


        echo "<script>
        function redirectToCart() {
            setTimeout(function() {
                window.location.href = 'index.php?page=shoppingcart';
            }, 10); // Redirect after a short delay
        }
        </script>";
      }
    } else
    {
      echo "<p>Product not found.</p>";
    }
  }
}


?>