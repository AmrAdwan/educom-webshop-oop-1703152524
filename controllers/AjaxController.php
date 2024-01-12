<?php
class AjaxController
{
  private $ratingCrud;

  public function __construct($crud)
  {
    $this->ratingCrud = new RatingCRUD($crud);
  }

  public function handleRequest()
  {
    // Determine the type of request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']))
    {
      switch ($_POST['action'])
      {
        case 'rateProduct':
          $this->handleRating();
          break;
      }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'ajax')
    {
      switch ($_GET['function'])
      {
        case 'getRating':
          $this->getRating();
          break;
      }
    }
  }
  private function handleRating()
  {
    // Process the rating POST request and return a response
    $productId = $_POST['product_id'];
    $userId = $_SESSION['user_id'];
    $rating = $_POST['rating'];

    // Check if the user has already rated this product
    $hasRated = $this->ratingCrud->hasUserRated($productId, $userId);

    // Based on the check, either store or update the rating
    if ($hasRated)
    {
      $this->ratingCrud->updateRating($productId, $userId, $rating);
    } else
    {
      $this->ratingCrud->storeRating($productId, $userId, $rating);
    }

    // Fetch the new average rating after updating
    $newAverageRating = $this->ratingCrud->getAverageRating($productId);

    // Return a JSON response with the new average rating
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'average' => $newAverageRating]);
  }


  private function getRating()
  {
    // Process the GET request to retrieve rating
    $productId = $_GET['id'] ?? null;
    if ($productId)
    {
      header('Content-Type: application/json');
      $averageRating = $this->ratingCrud->getAverageRating($productId);
      echo json_encode([
        "id" => $productId,
        "rating" => $averageRating
      ]);
    } else
    {
      // Handle the case where the product ID is not provided
      echo json_encode(["error" => "Product ID is required"]);
    }
  }
}
?>