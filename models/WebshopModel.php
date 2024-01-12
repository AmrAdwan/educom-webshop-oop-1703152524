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
  public $editId = '';
  public $editName = '';
  public $editDescription = '';
  public $editPrice = '';
  public $editImage = '';
  private $shopCrud;
  public $ratingCrud;

  public function __construct($pageModel, $shopCrud, $ratingCrud)
  {
    parent::__construct($pageModel);
    $this->shopCrud = $shopCrud;
    $this->ratingCrud = $ratingCrud;
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
      'top5'
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
    // return getProducts();
    // var_dump($this->shopCrud->readAllProducts());
    return $this->shopCrud->readAllProducts();
  }

  public function getDetailsData($productId)
  {
    // return getProductById($productId);
    // var_dump($this->shopCrud->readProductById($productId));
    return $this->shopCrud->readProductById($productId);
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
      $this->product = $this->shopCrud->readProductById($productId);
      if ($this->product)
      {
        $this->product->quantity = $quantity;
        $this->product->subtotal = $quantity * $this->product->price;
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
    // $orderPlaced = insertOrder($userId, $cartItems);
    $orderPlaced = $this->shopCrud->createOrder($userId, $cartItems);

    if ($orderPlaced)
    {
      echo '<script language="javascript">alert("Order Placed Successfully! Thank you for your order!");</script>';
      $_SESSION['cart'] = []; // Empty the cart
    }
  }

  public function AddNewProduct($product)
  {
    return $this->shopCrud->createProduct($product);
  }

  public function EditProduct($productId, $product)
  {
    return $this->shopCrud->updateProduct($productId, $product);
  }
  public function getTop5Products()
  {
    return $this->shopCrud->readTop5Products();
  }

  public function getAverageRatingForProduct($productId)
  {
    return $this->ratingCrud->getAverageRating($productId);
  }

  public function getAverageRatingsForAllProducts()
  {
    $ratings = $this->ratingCrud->getAllAverageRatings();
    $averageRatings = [];
    foreach ($ratings as $rating)
    {
      $averageRatings[$rating->product_id] = $rating->average_rating;
    }
    return $averageRatings;
  }


  public function validateAddProduct()
  {
    $this->errors = [];
    $this->valid = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Fetch POST data
      $this->prodName = $this->getPostVar('prodname');
      $this->prodDescription = $this->getPostVar('proddescription');
      $this->prodPrice = $this->getPostVar('prodprice');
      $this->prodImage = $_FILES['prodimage'] ?? null;

      // Validate product name
      if (empty($this->prodName))
      {
        $this->errors['prodname'] = 'Product name is required.';
      }

      // Validate product price
      if (empty($this->prodPrice || !is_numeric($this->prodPrice) || $this->prodPrice < 0))
      {
        $this->errors['prodprice'] = 'Valid product price is required.';
      }

      // Validate description
      if (empty($this->prodDescription))
      {
        $this->errors['proddescription'] = 'Product description is required.';
      }

      // Validate image upload
      if (!$this->prodImage || $this->prodImage['error'] !== UPLOAD_ERR_OK)
      {
        $this->errors['prodimage'] = 'Product image is required.';
      } else
      {
        // Validate image type and size
        $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($this->prodImage['type'], $allowedTypes))
        {
          $this->errors['prodimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
        } elseif ($this->prodImage['size'] > $maxSize)
        {
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
      'addData' => [
        'prodname' => $this->prodName,
        'proddescription' => $this->prodDescription,
        'prodprice' => $this->prodPrice,
        'prodimage' => $this->prodImage
      ],
      'errors' => $this->errors
    ];
  }

  public function validateEditProduct($product)
  {
    // var_dump($product->name);
    // echo "name == = == = " . $product->name;
    // Initialize properties with the existing product data
    $this->editId = $product->id;
    $this->editName = $product->name;
    $this->editDescription = $product->description;
    $this->editPrice = $product->price;
    // $this->editImage = $product['file_name'];


    $this->errors = [];
    $this->valid = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Update properties with submitted data
      $this->editId = $_POST['editid'] ?? $this->editId;
      $this->editName = $_POST['editname'] ?? $this->editName;
      $this->editDescription = $_POST['editdescription'] ?? $this->editDescription;
      $this->editPrice = $_POST['editprice'] ?? $this->editPrice;
      $this->editImage = $_FILES['editimage']['name'] ?? $this->editImage;

      // Validate product name
      if (empty($this->editName))
      {
        $this->errors['editname'] = 'Product name is required.';
      }

      // Validate product price
      if (empty($this->editPrice) || !is_numeric($this->editPrice) || $this->editPrice < 0)
      {
        $this->errors['editprice'] = 'Valid product price is required.';
      }

      // Validate description
      if (empty($this->editDescription))
      {
        $this->errors['editdescription'] = 'Product description is required.';
      }

      // Check if an image file is uploaded
      if (isset($_FILES['editimage']) && $_FILES['editimage']['error'] === UPLOAD_ERR_OK)
      {
        // Validate image type and size
        $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['editimage']['type'], $allowedTypes))
        {
          $this->errors['editimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
        } elseif ($_FILES['editimage']['size'] > $maxSize)
        {
          $this->errors['editimage'] = 'Image size too large. Maximum size: 2MB.';
        } else
        {
          // File is valid, update the editImage property to the new file name
          $this->editImage = $_FILES['editimage']['name'];
        }
      } else
      {
        // No new image uploaded, keep the old image
        $this->editImage = $product->file_name;
      }


      // Check if there are no errors
      if (empty($this->errors))
      {
        $this->valid = true;
      }
    }

    return [
      'editvalid' => $this->valid,
      'editData' => [
        'editid' => $this->editId,
        'editname' => $this->editName,
        'editdescription' => $this->editDescription,
        'editprice' => $this->editPrice,
        'editimage' => $this->editImage
      ],
      'errors' => $this->errors
    ];
  }




}

?>