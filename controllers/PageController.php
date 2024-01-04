<?php
include_once 'models/UserModel.php';
include_once 'models/WebshopModel.php';



class PageController
{
  private $model;

  public function __construct()
  {
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
  // business flow code
  private function processRequest()
  {
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
      case 'webshop':
        $this->model = new WebshopModel($this->model);
        $this->model->setPage("webshop");
        break;
      case 'product_details':
        $this->model = new WebshopModel($this->model);
        if (isset($_GET['product_id']))
        {
          $productId = $_GET['product_id'];
          $this->model->product = $this->model->getDetailsData($productId);
          if ($this->model->product)
          {
            $this->model->setPage("product_details");
          }
        }
        break;
      case 'shoppingcart':
        $this->model = new WebshopModel($this->model);
        $this->model->cartActions();
        $this->model->setPage("shoppingcart");
        break;
      case 'add_product':
        $this->model = new WebshopModel($this->model);
        $this->model->validateAddProduct();
        if ($this->model->valid)
        {
          saveProduct(
            $this->model->prodName,
            $this->model->prodDescription,
            $this->model->prodPrice,
            $this->model->prodImage['name']
          );
          $this->model->setPage("webshop");
        }
        break;
      case 'edit_product':
        $this->model = new WebshopModel($this->model);
        $productId = $_GET['product_id'] ?? $_POST['product_id'];
        $product = getProductById($productId);
        // var_dump($product);
        $this->model->validateEditProduct($product);
        if ($this->model->valid)
        {
          editProduct(
            $this->model->editId,
            $this->model->editName,
            $this->model->editPrice,
            $this->model->editDescription,
            $this->model->editImage
          );
          $this->model->setPage("webshop");
        }
        break;
    }
  }

  private function showResponsePage()
  {
    $this->model->createMenu();
    $page = $this->model->page;
    switch ($page)
    {
      case 'home':
      case 'logout':
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