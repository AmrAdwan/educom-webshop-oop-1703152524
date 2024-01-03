<?php
// include_once 'models/pageModel.php';
include_once 'models/UserModel.php';
include_once 'models/WebshopModel.php';



class PageController
{
  private $model;

  public function __construct()
  {
    // $this->model = $model;
    $this->model = new PageModel(NULL);
  }

  public function handleRequest()
  {
    $this->getRequest();
    $this->processRequest();
    $this->showResponsePage();
  }

  private function getRequest()
  {
    $this->model->getRequestedPage();
  }
    // A list of allowed pages
    // $allowedPages = [
    //   'home',
    //   'about',
    //   'contact',
    //   'register',
    //   'login',
    //   'logout',
    //   'change_password',
    //   'thanks',
    //   'webshop',
    //   'product_details',
    //   'shoppingcart',
    //   'top5',
    //   'add_product',
    //   'edit_product'
    // ];

    // // Check if it's a POST request
    // if ($_SERVER['REQUEST_METHOD'] === 'POST')
    // {
    //   // Check the page field to determine which form was submitted
    //   if (isset($_POST['page']))
    //   {
    //     return $_POST['page'];
    //   }
    // }

    // // Retrieving the 'page' parameter from the GET request
    // $requestedPage = $_GET['page'] ?? null;

    // // Check whether the requested page is in the list of allowed pages
    // if (in_array($requestedPage, $allowedPages))
    // {
    //   return $requestedPage;
    // }

    // // Return '404' for any other cases
    // return '404';
  // }

  // business flow code
  private function processRequest()
  {
    // $page = $this->getRequestedPage();


    // if (in_array($page, ['webshop', 'product_details', 'shoppingcart', 'top5', 'add_product', 'edit_product']))
    // {
    //   $shopModel = new WebshopModel();
    //   $shopModel->setPage($page);
    //   $this->model = $shopModel;
    // } elseif (in_array($page, ['login', 'contact', 'register', 'logout', 'change_password']))
    // {
    //   $userModel = new UserModel();
    //   $userModel->setPage($page);
    //   $this->model = $userModel;
    // } else
    // {
    //   $this->model->setPage($page);
    // }

    switch ($this->model->page)
    {
      case 'login':
        $this->model = new UserModel($this->model);
        $this->model->validateLogin();
        // var_dump($this->model);
        if ($this->model->valid)
        {
          $this->model->doLoginUser();
          $this->model->setPage("home");
        } 
        break;
      case 'contact':
        $this->model = new UserModel($this->model);
        $this->model->validateContact();
        if ($this->model->valid)
        {
          $this->model->setPage("thanks");
        }
        break;
      case 'register':
        $this->model = new UserModel($this->model);
        $user = $this->model->validateRegister();
        // var_dump($user["registerData"]["regpassword1"]);
        if ($this->model->valid)
        {
          $this->model->email = $user["registerData"]["regemail"];
          $this->model->name = $user["registerData"]["regname"];
          $this->model->password = $user["registerData"]["regpassword1"];
          saveUser($this->model->email, $this->model->name, $this->model->password);
          $this->model->valid = false;
          $this->model->setPage("login");
        }
        break;
      case 'logout':
        $this->model = new UserModel($this->model);
        $this->model->doLogoutUser();
        $this->model->setPage("home");
        break;
      case 'change_password':
        $this->model = new UserModel($this->model);
        $this->model->validateChangePassword();
        if ($this->model->valid)
        {
          $id = $_SESSION['user_id'];
          $email = findEmailById($id);
          var_dump($email);
          if (isset($this->model->newPassword))
          {
            $hashedPassword = password_hash($this->model->newPassword, PASSWORD_DEFAULT);
            updatePassword($email, $hashedPassword);
          }
          $this->model->setPage("home");
        }
        break;
      // case 'product_details':
      //   if (isset($_GET['product_id']))
      //   {
      //     $productId = $_GET['product_id'];
      //     $product = getProductById($productId);
      //     if ($product)
      //     {
      //       $this->model->setData('product', $product);
      //       $this->model->setData('page', 'product_details');
      //     }
      //   }
      //   break;
      // case 'shoppingcart':
      //   if (isset($_POST['product_id']))
      //   {
      //     $productId = $_POST['product_id'];
      //     addToCart($productId);
      //   }
      //   $cartItems = getCartItems();
      //   $this->model->setData('cart', $cartItems);
      //   $this->model->setData('page', 'shoppingcart');
      //   break;
      // case 'update_cart':
      //   if (isset($_POST['product_id']) && isset($_POST['quantity']))
      //   {
      //     updateCartQuantity($_POST['product_id'], $_POST['quantity']);
      //   }
      //   break;
      // case 'remove_from_cart':
      //   if (isset($_POST['product_id']))
      //   {
      //     removeFromCart($_POST['product_id']);
      //   }
      //   break;
      // case 'checkout':
      //   if (!empty($_SESSION['cart']))
      //   {
      //     processCheckout();
      //     $this->model->setData('page', 'webshop');
      //   }
      //   break;
      // case 'add_product':
      //   $data = validateAddProduct();
      //   $this->model->setData('addData', $data);
      //   if ($data['addvalid'])
      //   {
      //     $addProductData = $data['addData'];
      //     saveProduct(
      //       $addProductData['prodname'],
      //       $addProductData['proddescription'],
      //       $addProductData['prodprice'],
      //       $addProductData['prodimage']['name']
      //     );
      //     $this->model->setData('page', 'webshop');
      //   }
      //   break;

      // case 'edit_product':
      //   if (isset($_GET['product_id']) || isset($_POST['product_id']))
      //   {
      //     $productId = $_GET['product_id'] ?? $_POST['product_id'];
      //     $product = getProductById($productId);
      //     if ($product)
      //     {
      //       $data = validaEditProduct($product);
      //       $this->model->setData('editData', $data);
      //       if ($data['editvalid'])
      //       {
      //         $editData = $data['editData'];
      //         editProduct(
      //           $editData['editid'],
      //           $editData['editname'],
      //           $editData['editprice'],
      //           $editData['editdescription'],
      //           $editData['editimage']
      //         );
      //         $this->model->setData('page', 'webshop');
      //       }
      //     }
      //   }
      //   break;
    }
    // Render the appropriate view
    // $this->showResponsePage();
  }

  private function showResponsePage()
  {
    $this->model->createMenu();
    $page = $this->model->page;
    switch ($page)
    {
      case 'home':
      case 'logout':
        // require_once('../views/HomeDoc.php');
        $view = new HomeDoc($this->model);
        break;
      case 'about':
        $view = new AboutDoc($this->model);
        break;
      case 'contact':
        $view = new ContactDoc($this->model);
        break;
      case 'thanks':
        $view = new ThanksDoc($this->model);
        break;
      case 'register':
        $view = new RegisterDoc($this->model);
        break;
      case 'login':
        $view = new LoginDoc($this->model);
        break;
      case 'change_password':
        $view = new ChangePasswordDoc($this->model);
        break;
      case 'webshop':
        $view = new WebshopDoc($this->model);
        break;
      case 'product_details':
        $view = new ProductdetailsDoc($this->model);
        break;
      case 'shopping_cart':
        $view = new ShoppingcartDoc($this->model);
        break;
      case 'top5':
        $view = new Top5Doc($this->model);
        break;
      case 'shoppingcart':
        $view = new ShoppingcartDoc($this->model);
        break;
      case 'add_product':
        $view = new AddproductDoc($this->model);
        break;
      case 'edit_product':
        $view = new EditproductDoc($this->model);
        break;
      default:
        $view = new ErrorDoc($this->model);
        break;
    }
    $view->show();
  }


}


?>