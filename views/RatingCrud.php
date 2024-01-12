<?php
class RatingCRUD
{
  private $crud;

  public function __construct($crud)
  {
    $this->crud = $crud;
  }

  public function storeRating($productId, $userId, $rating)
  {
    $sql = "INSERT INTO ratings (product_id, user_id, rating) VALUES (:product_id, :user_id, :rating)";
    return $this->crud->createRow($sql, [
      'product_id' => $productId,
      'user_id' => $userId,
      'rating' => $rating
    ]);
  }

  public function updateRating($productId, $userId, $rating)
  {
    $sql = "UPDATE ratings SET rating = :rating WHERE product_id = :product_id AND user_id = :user_id";
    return $this->crud->updateRow($sql, [
      'rating' => $rating,
      'product_id' => $productId,
      'user_id' => $userId
    ]);
  }

  public function getAverageRating($productId)
  {
    $sql = "SELECT AVG(rating) as average_rating FROM ratings WHERE product_id = :product_id";
    $result = $this->crud->readOneRow($sql, ['product_id' => $productId]);
    return $result ? $result->average_rating : null;
  }

  public function getAllAverageRatings()
  {
    $sql = "SELECT product_id, AVG(rating) as average_rating FROM ratings GROUP BY product_id";
    return $this->crud->readMultipleRows($sql);
  }

  public function hasUserRated($productId, $userId)
  {
    $sql = "SELECT COUNT(*) as count FROM ratings WHERE product_id = :product_id AND user_id = :user_id";
    $result = $this->crud->readOneRow($sql, [
      'product_id' => $productId,
      'user_id' => $userId
    ]);
    return $result && $result->count > 0;
  }

  public function getUserRating($productId, $userId)
  {
    $sql = "SELECT rating FROM ratings WHERE product_id = :product_id AND user_id = :user_id";
    return $this->crud->readOneRow($sql, ['product_id' => $productId, 'user_id' => $userId]);
  }
}
?>