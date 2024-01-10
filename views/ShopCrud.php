<?php
// include_once "Database.php";

class ShopCrud
{
  private $crud;

  public function __construct($crud)
  {
    $this->crud = $crud;
  }

  public function createProduct($product)
  {
    $sql = "INSERT INTO products (name, price, description, file_name) VALUES (:name, :price, :description, :file_name)";
    return $this->crud->createRow($sql, $product);
  }

  public function createOrder($userId, $cartItems)
  {
    // Get the current maximum order number
    $sqlMaxOrderNr = "SELECT MAX(order_nr) as max_order_nr FROM orders";
    $maxOrderRow = $this->crud->readOneRow($sqlMaxOrderNr);
    $nextOrderNr = ($maxOrderRow->max_order_nr ?? 0) + 1;

    $sqlOrder = "INSERT INTO orders (user_id, order_date, order_nr) VALUES (:user_id, NOW(), :order_nr)";
    $orderData = [
      'user_id' => $userId,
      'order_nr' => $nextOrderNr
    ];
    echo "$nextOrderNr";

    // Insert the order and get its ID
    $orderId = $this->crud->createRow($sqlOrder, $orderData);
    // Check if order insertion was successful
    if (!$orderId)
    {
      return false; // Order insertion failed
    }
    // Insert each cart item into orderlines
    foreach ($cartItems as $item)
    {
      $orderlineData = [
        'order_id' => $orderId,
        'product_id' => $item->id,
        'quantity_per_product' => $item->quantity,
      ];

      // SQL statement to insert into orderlines table
      $sqlOrderline = "INSERT INTO orderlines (order_id, product_id, quantity_per_product) VALUES (:order_id, :product_id, :quantity_per_product)";

      // Insert the orderline and check if it's successful
      if (!$this->crud->createRow($sqlOrderline, $orderlineData))
      {
        return false; // Orderline insertion failed
      }
    }

    return true; // All insertions were successful
  }
  public function readAllProducts()
  {
    $sql = "SELECT * FROM products";
    return $this->crud->readMultipleRows($sql);
  }
  public function readProductById($productId)
  {
    $sql = "SELECT * FROM products WHERE id = :id";
    return $this->crud->readOneRow($sql, ['id' => $productId]);
  }
  public function updateProduct($productId, $product)
  {
    // var_dump($product);
    $sql = "UPDATE products SET name = :name, price = :price, description = :description, file_name = :file_name WHERE id = :id";
    return $this->crud->updateRow($sql, array_merge($product, ['id' => $productId]));
  }
  public function deleteProduct($productId)
  {
    $sql = "DELETE FROM products WHERE id = :id";
    return $this->crud->deleteRow($sql, ['id' => $productId]);
  }
  public function readTop5Products()
  {
    // SQL to fetch the top 5 products based on sales in the last 7 days
    $sql = "SELECT p.id, p.name, p.price, p.description, p.file_name, SUM(ol.quantity_per_product) AS total_quantity
          FROM products p
          JOIN orderlines ol ON p.id = ol.product_id
          JOIN orders o ON ol.order_id = o.id
          WHERE o.order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          GROUP BY p.id
          ORDER BY total_quantity DESC
          LIMIT 5";

    return $this->crud->readMultipleRows($sql);
  }

}


?>