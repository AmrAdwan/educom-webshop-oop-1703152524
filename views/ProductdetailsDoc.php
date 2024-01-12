<?php
include_once "BasicDoc.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProductdetailsDoc extends BasicDoc
{
  protected function showHeader()
  {
    echo "<h1>Product Details</h1>";
  }

  protected function showContent()
  {
    $productId = $_GET['product_id'];
    $this->model->product = $this->model->getDetailsData($productId);
    $product = $this->model->product;
    $averageRating = $this->model->getAverageRatingForProduct($product->id);

    if ($product)
    {
      echo "<br><div class='product-detail'>";
      echo "<img src='Images/" . htmlspecialchars($product->file_name) . "' alt='" . htmlspecialchars($product->name) . "' style='width: 60%;' />";
      echo "<h2>" . htmlspecialchars($product->name) . "</h2>";
      echo "<h3>Price: €" . htmlspecialchars($product->price) . "</h3>";
      echo "<h3>Description: " . htmlspecialchars($product->description) . "</h3>";
      echo "</div>";

      // Display average rating as stars
      echo "<div class='average-rating' data-product-id='" . $product->id . "'>" .
        $this->generateStarRating($averageRating, false) .
        "</div>";

      if ($this->model->isUserLoggedIn())
      {
        $this->displayRatingSection($product->id);
      }

      // Edit Product Button for Admin
      if ($this->model->isUserAdmin())
      {
        echo "<a href='index.php?page=edit_product&product_id=" . $product->id .
          "'><button type='button' class='btn btn-primary btn-block'>Edit Product</button></a>";
      }
    } else
    {
      echo "<p>Product not found.</p>";
    }
  }

  private function displayRatingSection($productId)
  {
    $userId = $_SESSION['user_id'];
    $userRating = $this->model->ratingCrud->getUserRating($productId, $userId);
    $userRatingValue = $userRating ? $userRating->rating : 0;

    echo "<div class='product-rating'>";
    echo "<h3>Rate this product:</h3>";
    echo "<div class='user-rating-container' data-product-id='$productId'>";
    echo $this->generateStarRating($userRatingValue, true, $productId);
    echo "</div></div>";
    $this->addRatingScript();
  }


  private function addRatingScript()
  {
    echo "<script>
    $(document).ready(function() {
        // Event delegation for dynamic elements
        $(document).on('click', '.star-input', function() {
            var rating = $(this).data('rating');
            var productId = $(this).data('product-id');
            updateRating(productId, rating);
        });
    });

    function updateRating(productId, rating) {
      $.ajax({
          url: 'index.php?action=ajax',
          type: 'POST',
          data: {
              action: 'rateProduct',
              product_id: productId,
              rating: rating
          },
          success: function(data) {
              if (typeof data !== 'object') {
                  try {
                      data = JSON.parse(data);
                  } catch (error) {
                      console.error('Error parsing JSON:', error);
                      return;
                  }
              }
              if (data.success) {
                  // Update user's rating display
                  updateStarDisplay(productId, rating, '.user-rating-container', true);
                  // Update average rating display
                  if (data.average !== undefined) {
                      updateStarDisplay(productId, parseFloat(data.average), '.average-rating', false);
                  }
              } else {
                  console.error('Error in updating rating:', data);
              }
          },
          error: function(error) {
              console.error('Error:', error);
          }
      });
  }
  
  

  function updateStarDisplay(productId, rating, containerSelector) {
    var container = $(containerSelector + '[data-product-id=\"' + productId + '\"]');
    container.empty(); // Clear the current stars
    var isInteractive = (containerSelector === '.user-rating-container');

    for (var i = 1; i <= 5; i++) {
        var starClass = isInteractive ? 'star-input' : '';
        var checked = i <= rating ? 'checked' : '';
        var dataAttr = isInteractive ? 'data-rating=\"' + i + '\" data-product-id=\"' + productId + '\"' : '';
        container.append('<span class=\"fa fa-star ' + starClass + ' ' + checked + '\" ' + dataAttr + '></span>');
    }
  }

    function handleResponse(data, productId, rating) {
      if (data.success) {
          // Update user's rating display
          updateStarDisplay(productId, rating, '.user-rating-container');
          // Update average rating display
          if (data.average !== undefined) {
              updateStarDisplay(productId, data.average, '.average-rating');
          }
      } else {
          console.error('Error in updating rating:', data);
      }
    }
  
  
    </script>";
  }


  protected function generateStarRating($rating, $isInteractive = false, $productId = null)
  {
    $starHtml = '';
    for ($i = 1; $i <= 5; $i++)
    {
      $checked = $i <= $rating ? 'checked' : '';
      $starClass = $isInteractive ? 'star-input' : '';
      $dataAttr = $isInteractive ? "data-rating='$i' data-product-id='$productId'" : '';
      $starHtml .= "<span class='fa fa-star $starClass $checked' $dataAttr></span>";
    }
    return $starHtml;
  }
}
?>