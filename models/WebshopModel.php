<?php

include_once 'PageModel.php';

class WebshopModel extends PageModel
{
  public $products = '';
  public $product = '';
  public $cartLines = '';
  public $cartTotal = '';
  public $valid = false;
  public $prodName = '';
  public $prodDescription = '';
  public $prodPrice = '';
  public $prodImage = '';

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

  public function validateAddProduct()
  {
    $addProductData = [
      'prodname' => '',
      'proddescription' => '',
      'prodprice' => '',
      'prodimage' => ''
    ];
    $this->errors = [];
    $this->valid = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Fetch POST data
      $this->prodName = $this->getPostVar('prodname');
      $this->prodDescription = $this->getPostVar('proddescription');
      $this->prodPrice = $this->getPostVar('prodprice');
      $this->prodImage = $_FILES['prodimage'] ?? null;

      // $this->prodImage = $this->getPostVar('prodimage');

      // foreach ($addProductData as $key => &$value)
      // {
      //   if ($key != 'prodimage')
      //   {
      //     $value = $_POST[$key] ?? '';
      //   } else
      //   {
      //     $value = $_FILES[$key] ?? '';
      //   }
      // }

      // Validate product name
      // if (empty($addProductData['prodname']))
      if (empty($this->prodName))
      {
        $this->errors['prodname'] = 'Product name is required.';
      }

      // Validate product price
      // if (empty($addProductData['prodprice']) || !is_numeric($addProductData['prodprice']) || $addProductData['prodprice'] < 0)
      if (empty($this->prodPrice || !is_numeric($this->prodPrice) || $this->prodPrice < 0))
      {
        $this->errors['prodprice'] = 'Valid product price is required.';
      }

      // Validate description
      // if (empty($addProductData['proddescription']))
      if (empty($this->prodDescription))
      {
        $this->errors['proddescription'] = 'Product description is required.';
      }

      // Validate image
      // if (empty($addProductData['prodimage']['name']))
      // {
      //   $this->errors['prodimage'] = 'Product image is required.';
      // } else
      // {
      //   // Check for valid image types (PNG, GIF, JPEG) and size (less than 2 MB)
      //   $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
      //   $maxSize = 2 * 1024 * 1024; // 2MB

      //   if (!in_array($addProductData['prodimage']['type'], $allowedTypes))
      //   {
      //     $this->errors['prodimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
      //   } elseif ($addProductData['prodimage']['size'] > $maxSize)
      //   {
      //     $this->errors['prodimage'] = 'Image size too large. Maximum size: 2MB.';
      //   }
      // }
      // Validate image upload
      if (!$this->prodImage || $this->prodImage['error'] !== UPLOAD_ERR_OK) {
        $this->errors['prodimage'] = 'Product image is required.';
      } else {
        // Validate image type and size
        $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($this->prodImage['type'], $allowedTypes)) {
            $this->errors['prodimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
        } elseif ($this->prodImage['size'] > $maxSize) {
            $this->errors['prodimage'] = 'Image size too large. Maximum size: 2MB.';
        }
      }


      // Check if there are no errors
      if (empty($this->errors))
      {
        $this->valid = true;
      }
    }

    return [
      'addvalid' => $this->valid,
      // 'addData' => $addProductData,
      'addData' => [
      'prodname' => $this->prodName,
      'proddescription' => $this->prodDescription,
      'prodprice' => $this->prodPrice,
      'prodimage' => $this->prodImage
      ],
      'errors' => $this->errors
    ];
  }

  public function validaEditProduct($product)
  {
    $editProductData = [
      'editid' => $product['id'],
      'editname' => $product['name'],
      'editdescription' => $product['description'],
      'editprice' => $product['price'],
      'editimage' => $product['file_name']
    ];
    $this->errors = [];
    $this->valid = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {

      // Update with submitted data
      $editProductData = [
        'editid' => $_POST['editid'] ?? $product['id'],
        'editname' => $_POST['editname'] ?? $product['name'],
        'editdescription' => $_POST['editdescription'] ?? $product['description'],
        'editprice' => $_POST['editprice'] ?? $product['price'],
        'editimage' => $_FILES['editimage']['name'] ?? $product['file_name']
      ];

      // Validate product name
      if (empty($editProductData['editname']))
      {
        $this->errors['editname'] = 'Product name is required.';
      }

      // Validate product price
      if (empty($editProductData['editprice']) || !is_numeric($editProductData['editprice']) || $editProductData['editprice'] < 0)
      {
        $this->errors['editprice'] = 'Valid product price is required.';
      }

      // Validate description
      if (empty($editProductData['editdescription']))
      {
        $this->errors['editdescription'] = 'Product description is required.';
      }

      // Validate image if a new image is being uploaded
      if ($editProductData['editimage'] && $editProductData['editimage'] !== $product['file_name'])
      {
        // Check for valid image types (PNG, GIF, JPEG) and size (less than 2 MB)
        $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($editProductData['editimage']['type'], $allowedTypes))
        {
          $this->errors['editimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
        } elseif ($editProductData['editimage']['size'] > $maxSize)
        {
          $this->errors['editimage'] = 'Image size too large. Maximum size: 2MB.';
        }
      }

      // Check if there are no errors
      if (empty($this->errors))
      {
        $this->valid = true;
        $editProductData['editimage'] = $editProductData['editimage'] ?? $product['file_name'];
      }
    }

    return [
      'editvalid' => $this->valid,
      'editData' => $editProductData,
      'errors' => $this->errors
    ];
  }



}

?>