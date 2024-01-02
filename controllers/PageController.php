<?php
include_once 'models/UserModel.php';
include_once 'models/WebshopModel.php';



class PageController
{
  private $model;

  public function __construct($model)
  {
    $this->model = $model;
  }

  private function getRequestedPage()
  {
    // A list of allowed pages
    $allowedPages = [
      'home',
      'about',
      'contact',
      'register',
      'login',
      'logout',
      'change_password',
      'thanks',
      'webshop',
      'product_details',
      'shoppingcart',
      'top5',
      'add_product',
      'edit_product'
    ];

    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Check the page field to determine which form was submitted
      if (isset($_POST['page']))
      {
        return $_POST['page'];
      }
    }

    // Retrieving the 'page' parameter from the GET request
    $requestedPage = $_GET['page'] ?? null;

    // Check whether the requested page is in the list of allowed pages
    if (in_array($requestedPage, $allowedPages))
    {
      return $requestedPage;
    }

    // Return '404' for any other cases
    return '404';
  }

  public function handleRequest()
  {
    $page = $this->getRequestedPage();


    if (in_array($page, ['webshop', 'product_details', 'shoppingcart', 'top5', 'add_product', 'edit_product']))
    {
      $shopModel = new WebshopModel();
      $shopModel->setData('page', $page);
      $this->model = $shopModel;
    } elseif (in_array($page, ['login', 'contact', 'register', 'logout', 'change_password']))
    {
      $userModel = new UserModel();
      $userModel->setData('page', $page);
      $this->model = $userModel;
    } else
    {
      $this->model->setData('page', $page);
    }

    // Handle specific page logic
    switch ($page)
    {
      case 'login':
        $data = validateLogin();
        $this->model->setData('loginData', $data);
        if ($data['logvalid'])
        {
          doLoginUser($data['id'], $data['logname']);
          $this->model->setData('isLoggedIn', true);
          $this->model->setData('userName', getLoggedInUserName());
          $this->model->setData('page', 'home');
        } else
        {
          // Stay on login page with errors
          $this->model->setData('page', 'login');
        }
        break;
      case 'contact':
        $data = validateContact();
        $this->model->setData('formResult', $data);
        if ($data['valid'])
        {
          $this->model->setData('contactFormData', $data);
          $this->model->setData('page', 'thanks');
        }
        break;
      case 'register':
        $data = validateRegister();
        $this->model->setData('registerData', $data);
        if ($data['regvalid'])
        {
          $registerData = $data['registerData'];
          // Save the user to the database
          saveUser($registerData['regemail'], $registerData['regname'], $registerData['regpassword1']);
          // Redirect to the login page after successful registration
          $this->model->setData('page', 'login');
        } else
        {
          // Stay on register page with errors
          $this->model->setData('page', 'register');
        }
        break;
      case 'logout':
        doLogoutUser();
        $this->model->setData('isLoggedIn', false);
        $this->model->setData('userName', null);
        $this->model->setData('page', 'home');
        break;
      case 'change_password':
        $data = validateChangePassword();
        $this->model->setData('changeData', $data);

        if ($data['changevalid'])
        {
          $id = $_SESSION['user_id'];
          $email = findEmailById($id);
          if (isset($data['changeData']['new_password']))
          {
            $hashedPassword = password_hash($data['changeData']['new_password'], PASSWORD_DEFAULT);
            updateUserPassword($email, $hashedPassword);
          }
          $this->model->setData('page', 'home');
        } else
        {
          $this->model->setData('page', 'change_password');
        }
        break;
      case 'product_details':
        if (isset($_GET['product_id']))
        {
          $productId = $_GET['product_id'];
          $product = getProductById($productId);
          if ($product)
          {
            $this->model->setData('product', $product);
            $this->model->setData('page', 'product_details');
          }
        }
        break;
      case 'shoppingcart':
        if (isset($_POST['product_id']))
        {
          $productId = $_POST['product_id'];
          addToCart($productId);
        }
        $cartItems = getCartItems();
        $this->model->setData('cart', $cartItems);
        $this->model->setData('page', 'shoppingcart');
        break;
      case 'update_cart':
        if (isset($_POST['product_id']) && isset($_POST['quantity']))
        {
          updateCartQuantity($_POST['product_id'], $_POST['quantity']);
        }
        break;
      case 'remove_from_cart':
        if (isset($_POST['product_id']))
        {
          removeFromCart($_POST['product_id']);
        }
        break;
      case 'checkout':
        if (!empty($_SESSION['cart']))
        {
          processCheckout();
          $this->model->setData('page', 'webshop');
        }
        break;
      case 'add_product':
        $data = validateAddProduct();
        $this->model->setData('addData', $data);
        if ($data['addvalid'])
        {
          $addProductData = $data['addData'];
          saveProduct(
            $addProductData['prodname'],
            $addProductData['proddescription'],
            $addProductData['prodprice'],
            $addProductData['prodimage']['name']
          );
          $this->model->setData('page', 'webshop');
        }
        break;

      case 'edit_product':
        if (isset($_GET['product_id']) || isset($_POST['product_id']))
        {
          $productId = $_GET['product_id'] ?? $_POST['product_id'];
          $product = getProductById($productId);
          if ($product)
          {
            $data = validaEditProduct($product);
            $this->model->setData('editData', $data);
            if ($data['editvalid'])
            {
              $editData = $data['editData'];
              editProduct(
                $editData['editid'],
                $editData['editname'],
                $editData['editprice'],
                $editData['editdescription'],
                $editData['editimage']
              );
              $this->model->setData('page', 'webshop');
            }
          }
        }
        break;
    }
    // Render the appropriate view
    $this->showResponsePage();
  }

  private function showResponsePage()
  {
    $page = $this->model->getData('page');
    switch ($page)
    {
      case 'home':
      case 'logout':
        $doc = new HomeDoc($this->model);
        break;
      case 'about':
        $doc = new AboutDoc($this->model);
        break;
      case 'contact':
        $doc = new ContactDoc($this->model);
        break;
      case 'thanks':
        $doc = new ThanksDoc($this->model);
        break;
      case 'register':
        $doc = new RegisterDoc($this->model);
        break;
      case 'login':
        $doc = new LoginDoc($this->model);
        break;
      case 'change_password':
        $doc = new ChangePasswordDoc($this->model);
        break;
      case 'webshop':
        $doc = new WebshopDoc($this->model);
        break;
      case 'product_details':
        $doc = new ProductdetailsDoc($this->model);
        break;
      case 'shopping_cart':
        $doc = new ShoppingcartDoc($this->model);
        break;
      case 'top5':
        $doc = new Top5Doc($this->model);
        break;
      case 'shoppingcart':
        $doc = new ShoppingcartDoc($this->model);
        break;
      case 'add_product':
        $doc = new AddproductDoc($this->model);
        break;
      case 'edit_product':
        $doc = new EditproductDoc($this->model);
        break;
      default:
        $doc = new ErrorDoc($this->model);
        break;
    }
    $doc->show();
  }


}


?>