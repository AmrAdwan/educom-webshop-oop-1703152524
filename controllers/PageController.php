<?php
include_once 'models/UserModel.php';
include_once 'models/WebshopModel.php';
include_once 'AjaxController.php';

class PageController
{
  private $modelFactory;
  private $model;

  public function __construct(ModelFactory $modelFactory)
  {
    $this->modelFactory = $modelFactory;
    $this->model = $this->modelFactory->createModel('Page');
    // $this->model = new PageModel(NULL);
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
    // echo "Requested Page: " . $this->model->page;
    switch ($this->model->page)
    {
      case 'login':
        // $this->model = new UserModel($this->model);
        $this->model = $this->modelFactory->createModel('User');
        $this->model->validateLogin();
        if ($this->model->valid)
        {
          $this->model->doLoginUser();
          $this->model->setPage("home");
        } else
        {
          $this->model->setPage("login");
        }
        break;
      case 'contact':
        $this->model = $this->modelFactory->createModel('User');
        $this->model->validateContact();
        if ($this->model->valid)
        {
          $this->model->setPage("thanks");
        } else
        {
          $this->model->setPage("contact");
        }
        break;
      case 'register':
        $this->model = $this->modelFactory->createModel('User');
        $user = $this->model->validateRegister();
        if ($this->model->valid)
        {
          $userData = [
            'name' => $user["registerData"]["regname"],
            'email' => $user["registerData"]["regemail"],
            'password' => password_hash($user["registerData"]["regpassword1"], PASSWORD_DEFAULT)
          ];
          $this->model->registerUser($userData);
          $this->model->valid = false;
          $this->model->setPage("login");
        } else
        {
          $this->model->setPage("register");
        }
        break;
      case 'logout':
        $this->model = $this->modelFactory->createModel('User');
        $this->model->doLogoutUser();
        $this->model->setPage("home");
        break;
      case 'change_password':
        $this->model = $this->modelFactory->createModel('User');
        $this->model->validateChangePassword();
        if ($this->model->valid)
        {
          $id = $_SESSION['user_id'];
          if (isset($this->model->newPassword))
          {
            $hashedPassword = password_hash($this->model->newPassword, PASSWORD_DEFAULT);
            // Use UserCrud to update the password
            $this->model->updatePassword($id, $hashedPassword);
          }
          $this->model->setPage("home");
        } else
        {
          $this->model->setPage("change_password");
        }
        break;
      case 'webshop':
        // $this->model = new WebshopModel($this->model);
        $this->model = $this->modelFactory->createModel('Shop');
        $this->model->setPage("webshop");
        break;
      case 'product_details':
        // $this->model = new WebshopModel($this->model);
        $this->model = $this->modelFactory->createModel('Shop');
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
        // $this->model = new WebshopModel($this->model);
        $this->model = $this->modelFactory->createModel('Shop');
        $this->model->cartActions();
        $this->model->setPage("shoppingcart");
        break;
      case 'add_product':
        // $this->model = new WebshopModel($this->model);
        $this->model = $this->modelFactory->createModel('Shop');
        $this->model->validateAddProduct();
        if ($this->model->valid)
        {
          // saveProduct(
          $product = [
            'name' => $this->model->prodName,
            'price' => $this->model->prodPrice,
            'description' => $this->model->prodDescription,
            'file_name' => $this->model->prodImage['name']
          ];
          $this->model->AddNewProduct($product);
          $this->model->setPage("webshop");
        } else
        {
          $this->model->setPage("add_product");
        }
        break;
      case 'edit_product':
        // $this->model = new WebshopModel($this->model);
        $this->model = $this->modelFactory->createModel('Shop');
        $productId = $_GET['product_id'] ?? $_POST['product_id'];
        $product = $this->model->getDetailsData($productId);
        // var_dump($product);
        $this->model->validateEditProduct($product);
        if ($this->model->valid)
        {
          $editProduct = [
            // 'id' =>  $this->model->editId,
            'name' => $this->model->editName,
            'price' => $this->model->editPrice,
            'description' => $this->model->editDescription,
            'file_name' => $this->model->editImage
          ];
          $this->model->EditProduct($productId, $editProduct);
          $this->model->setPage("webshop");
        } else
        {
          $this->model->setPage("edit_product");
        }
        break;
      case 'top5':
        $this->model = $this->modelFactory->createModel('Shop');
        $this->model->setPage("top5");
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