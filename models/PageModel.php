<?php
include_once 'SessionManager.php';
include_once 'MenuItem.php';

class PageModel
{
  // private $data = [];

  public $page;
  protected $isPost = false;
  public $menu;
  public $errors = array();
  public $genericErr = "";
  protected $sessionManager;


  public function __construct($copy)
  {
    if (empty($copy))
    {
      $this->sessionManager = new SessionManager();
    } else
    {
      $this->page = $copy->page;
      $this->isPost = $copy->isPost;
      $this->menu = $copy->menu;
      $this->genericErr = $copy->genericErr;
      $this->sessionManager = $copy->sessionManager;
    }


    // $this->setData('isLoggedIn', isUserLoggedIn());
    // $this->setData('userName', getLoggedInUserName());
    // $this->setData('isAdmin', isUserAdmin());
  }

  public function getRequestedPage()
  {
    $this->isPost = ($_SERVER['REQUEST_METHOD'] == 'POST');

    if ($this->isPost)
    {
      $this->setPage($this->getPostVar("page", "home"));
    } else
    {
      $this->setPage($this->getUrlVar("page", "home"));
    }
  }

  protected function setPage($newPage)
  {
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
    // if (in_array($newPage, $allowedPages))
    // {
      $this->page = $newPage;
    // }
  }

  // public function getPostVar($key, $default = null)
  // {
  //   return isset($_POST[$key]) ? $_POST[$key] : $default;
  // }
  // public function getUrlVar($key, $default = "")
  // {
  //   return isset($_GET[$key]) ? $_GET[$key] : $default;
  // }

  public function getPostVar($key, $default = '')
  {
    if (isset($_POST[$key]))
    {
      return $this->testInput($_POST[$key]);
    }
    return $default;
  }

  public function testInput($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function getUrlVar($key, $default = '')
  {
    if (isset($_GET[$key]))
    {
      return $this->testInput($_GET[$key]);
    }
    return $default;
  }


  public function createMenu()
  {
    $this->menu['home'] = new MenuItem('home', 'Home');
    $this->menu['about'] = new MenuItem('about', 'About');
    $this->menu['contact'] = new MenuItem('contact', 'Contact');
    $this->menu['webshop'] = new MenuItem('webshop', 'Webshop');
    $this->menu['top5'] = new MenuItem('top5', 'Top 5');

    if ($this->sessionManager->isUserLoggedIn())
    {
      $this->menu['logout'] = new MenuItem('logout', 'Logout', $this->sessionManager->getLoggedInUserName());
      $this->menu['shoppingcart'] = new MenuItem('shoppingcart', 'ShoppingCart');
      $this->menu['change_password'] = new MenuItem('change_password', 'Change Password');
    } else
    {
      $this->menu['register'] = new MenuItem('register', 'Register');
      $this->menu['login'] = new MenuItem('login', 'Login');
    }
  }

  public function showMenu()
  {
    echo '<nav>' . PHP_EOL;
    echo '<ul class="menu">' . PHP_EOL;
    foreach ($this->menu as $menuItem)
    {
      $menuItem->render();
    }
    echo '</ul>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;
  }



  // public function createMenu()
  // {
  //   $this->menu['home'] = new MenuItem('home', 'Home');

  //   if ($this->sessionManager->isUserLoggedIn())
  //   {
  //     $this->menu['logout'] = new MenuItem(
  //       'logout',
  //       'LOGOUT',
  //       $this->sessionManager->getLoggedInUserName()['name']
  //     );
  //   }
  // }

  // public function setData($key, $value)
  // {
  //   $this->data[$key] = $value;
  // }

  // public function getData($key)
  // {
  //   return isset($this->data[$key]) ? $this->data[$key] : null;
  // }
}
?>