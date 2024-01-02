<?php

include_once 'PageModel.php';

class WebshopModel extends PageModel
{
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->connectToDatabase();
  }

  private function connectToDatabase()
  {
    $servername = '127.0.0.1';
    $username = 'amr_web_shop_user';
    $password = 'Amr-ma,236037';
    $dbname = 'amr_webshop';

    $this->conn = new mysqli($servername, $username, $password, $dbname);

    if ($this->conn->connect_error)
    {
      throw new Exception("Connection failed: " . $this->conn->connect_error);
    }
  }

  public function getProducts()
  {
    $sql = "SELECT * FROM products";
    $result = $this->conn->query($sql);

    if (!$result)
    {
      throw new Exception("Error: " . $this->conn->error);
    }

    $products = [];
    while ($row = $result->fetch_assoc())
    {
      $products[] = $row;
    }

    return $products;
  }

  public function getProductById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();
    return $product;
  }


  public function __destruct()
  {
    $this->conn->close();
  }
}

?>