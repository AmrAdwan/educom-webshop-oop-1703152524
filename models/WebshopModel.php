<?php

include_once 'PageModel.php';

class WebshopModel extends PageModel
{
  public $products = '';
  public $product = '';
  public $cartLines = '';
  public $cartTotal = '';

  public function __construct($pageModel)
  {
    parent::__construct($pageModel);
  }


  public function CartActions()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      $action = $_POST['action'] ?? '';
      $productId = $_POST['product_id'] ?? 0;
      $quantity = $_POST['quantity'] ?? 1;

      switch ($action)
      {
        case 'add':
          $this->addToCart($productId);
          break;
        case 'update':
          $this->updateCartQuantity($productId, $quantity);
          break;
        case 'remove':
          $this->removeFromCart($productId);
          break;
        case 'checkout':
          $this->processCheckout();
          break;
      }
    }
  }

  public function setPage($newPage)
  {
    $allowedPages = [
      'webshop',
      'product_details',
      'add_product',
      'edit_product',
      'shoppingcart',
    ];
    if (in_array($newPage, $allowedPages))
    {
      $this->page = $newPage;
    }
  }
  public function isUserLoggedIn()
  {
    return $this->sessionManager->isUserLoggedIn();
  }

  public function isUserAdmin()
  {
    return $this->sessionManager->isUserAdmin();
  }

  public function getWebshopData()
  {
    return getProducts();
  }

  public function getDetailsData($productId)
  {
    return getProductById($productId);
  }

  public function getCartLines()
  {
    // $this->cartLines = $_SESSION['cart'] ?? [];
    if (!isset($_SESSION['cart']))
    {
      return [];
    }

    $this->cartLines = [];
    foreach ($_SESSION['cart'] as $productId => $quantity)
    {
      $this->product = getProductById($productId);
      if ($this->product)
      {
        $this->product['quantity'] = $quantity;
        $this->product['subtotal'] = $quantity * $this->product['price'];
        $this->cartLines[] = $this->product;
      }
    }
    return $this->cartLines;
  }

  public function addToCart($productId)
  {
    if (!isset($_SESSION['cart']))
    {
      $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId]))
    {
      $_SESSION['cart'][$productId]++; // Increase quantity
    } else
    {
      $_SESSION['cart'][$productId] = 1; // Add product with quantity 1
    }
  }


  protected function updateCartQuantity($productId, $quantity)
  {
    if (isset($_SESSION['cart'][$productId]))
    {
      if ($quantity > 0)
      {
        $_SESSION['cart'][$productId] = $quantity;
      } else
      {
        unset($_SESSION['cart'][$productId]); // Remove item if quantity is 0
      }
    }
  }

  protected function removeFromCart($productId)
  {
    if (isset($_SESSION['cart'][$productId]))
    {
      unset($_SESSION['cart'][$productId]);
    }
  }

  protected function processCheckout()
  {

    $userId = $_SESSION['user_id'];
    $cartItems = $this->getCartLines();

    // Call insertOrder to save the order in the database
    $orderPlaced = insertOrder($userId, $cartItems);

    if ($orderPlaced)
    {
      echo '<script language="javascript">alert("Order Placed Successfully! Thank you for your order!");</script>';
      $_SESSION['cart'] = []; // Empty the cart
    }
  }
}

?>