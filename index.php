<?php
// session_start();

include 'home.php';
include 'about.php';
include '404.php';
include 'register.php';
include 'login.php';
include 'contact.php';
include 'thanks.php';
include 'change_password.php';
include 'validations.php';
include 'session_manager.php';
include 'webshop.php';
include 'product_details.php';
include 'shoppingcart.php';
include 'top5.php';
include 'add_product.php';
include 'edit_product.php';
include 'views/HtmlDoc.php';
include 'views/BasicDoc.php';
include 'views/HomeDoc.php';


function processRequest($page)
{
  switch ($page)
  {
    case 'login':
      $data = validateLogin();
      if ($data['logvalid'])
      {
        doLoginUser($data['id'], $data['logname']);
        $page = 'home';
      }
      break;
    case 'logout':
      doLogoutUser();
      $page = 'home';
      break;
    case 'contact':
      $data = validateContact();
      if ($data['valid'])
      {
        $page = 'thanks';
      }
      break;
    case 'register':
      $data = validateRegister();
      if ($data['regvalid'])
      {
        $registerData = $data['registerData'];
        saveUser($registerData['regemail'], $registerData['regname'], $registerData['regpassword1']);
        $page = 'login';
      }
      break;
    case 'product_details':
      if (isset($_GET['product_id']))
      {
        $productId = $_GET['product_id'];
        $product = getProductById($productId);
        if ($product)
        {
          $data['product'] = $product;
          $page = 'product_details';
        }
      }
      break;
    case 'shoppingcart':
      if (isset($_POST['product_id']))
      {
        addToCart($_POST['product_id']);
      }
      $page = 'shoppingcart';
      $data['cart'] = getCartItems();
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
        $page = 'webshop';
      }
      break;
    case 'change_password':
      $data = validateChangePassword();
      if ($data['changevalid'])
      {
        $id = $_SESSION['user_id'];
        $email = findEmailById($id);
        $changeData = $data['changeData'];

        $hashedPassword = password_hash($changeData['new_password'], PASSWORD_DEFAULT);
        updateUserPassword($email, $hashedPassword);
        $page = 'home';
      }
      break;
    case 'add_product':
      $data = validateAddProduct();
      if ($data['addvalid'])
      {
        $addProductData = $data['addData'];
        saveProduct($addProductData['prodname'], $addProductData['proddescription'],
          $addProductData['prodprice'], $addProductData['prodimage']['name']);
        $page = 'webshop';
      }
      break;
    case 'edit_product':
      if (isset($_GET['product_id']) || isset($_POST['product_id']))
      {
        $productId = $_GET['product_id'] ?? $_POST['product_id'];
        $product = getProductById($productId);
        if ($product)
        {
          $data['product'] = $product;
          $data = validaEditProduct($product);
          if ($data['editvalid'])
          {
            $editData = $data['editData'];
            editProduct($editData['editid'], $editData['editname'], $editData['editprice'], $editData['editdescription'],
              $editData['editimage']);
            $page = 'webshop';
          }
        }
      }
      break;
  }

  $data['menu'] = array(
    'home' => 'Home',
    'about' => 'About',
    'contact' => 'Contact',
    'webshop' => 'Webshop',
    'top5' => 'Top 5'
  );

  if (isUserLoggedIn())
  {
    $data['menu']['shoppingcart'] = 'ShoppingCart';
    $data['menu']['logout'] = 'Logout[' . getLoggedInUserName() . ']';
    $data['menu']['change_password'] = 'Change Password';
  } else
  {
    $data['menu']['register'] = 'Register';
    $data['menu']['login'] = 'Login';
  }


  $data['page'] = $page;
  return $data;
}

function showHtmlstatement()
{
  echo "<!DOCTYPE html>\n";
  "<html>";
}

function showHeadSection($data)
{
  echo "<head>";
  echo "<title>";
  switch ($data['page'])
  {
    case 'home':
    case 'logout':
      echo "Home";
      break;
    case 'about':
      echo "About";
      break;
    case 'contact':
      echo "Contact";
      break;
    case 'thanks':
      echo 'Thanks';
      break;
    case 'register':
      echo "Register";
      break;
    case 'login':
      echo "Login";
      break;
    case 'change_password':
      echo "Change Password";
      break;
    case 'webshop':
      echo 'Webshop';
      break;
    case 'product_details':
      echo 'Product Details';
      break;
    case 'shoppingcart':
      echo 'Shopping Cart';
      break;
    case 'top5':
      echo 'Top 5';
      break;
    case 'add_product':
      echo 'Add Product';
      break;
    case 'edit_product':
      echo 'Edit Product';
      break;
    default:
      echo "404 Not Found";
      echo "</title>";
      echo "</head>";
      break;
  }
  if ($data != '404')
  {
    echo "</title>";
    echo "<link rel=\"stylesheet\" href=\"./CSS/stylesheet.css\">";
    echo "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">";
    echo "<link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">";
    echo "</head>";
  }
}

function showHeader($data)
{
  echo "<h1>";
  switch ($data['page'])
  {
    case 'home':
    case 'logout':
      echo "Home";
      break;
    case 'about':
      echo "About";
      break;
    case 'contact':
      echo "Contact";
      break;
    case 'thanks':
      echo "Thanks";
      break;
    case 'register':
      echo "Register";
      break;
    case 'login':
      echo "Login";
      break;
    case 'change_password':
      echo "Change Password";
      break;
    case 'webshop':
      echo "Webshop";
      break;
    case 'product_details':
      echo 'Product Details';
      break;
    case 'shoppingcart':
      echo 'Shopping Cart';
      break;
    case 'top5':
      echo 'Top 5';
      break;
    case 'add_product':
      echo 'Add Product';
      break;
    case 'edit_product':
      echo 'Edit Product';
      break;
    default:
      echo '404 Page Not Found';
      break;
  }
  echo "</h1>";
}

function showNavMenu($data)
{
  echo '<nav>' . PHP_EOL;
  echo '<ul class="menu">' . PHP_EOL;
  foreach ($data['menu'] as $link => $label)
  {
    showMenuItem($link, $label);
  }
  echo '</ul>' . PHP_EOL;
  echo '</nav>' . PHP_EOL;
}

function showContent($data)
{
  switch ($data['page'])
  {
    case 'home':
      showHomeContent();
      break;
    case 'about':
      showAboutContent();
      break;
    case 'contact':
      showContactForm($data);
      break;
    case 'thanks':
      showContactThanks($data);
      break;
    case 'login':
      showLoginForm($data);
      break;
    case 'register':
      showRegisterForm($data);
      break;
    case 'change_password':
      showChangePasswordForm($data);
      break;
    case 'webshop':
      showWebshopContent();
      break;
    case 'product_details':
      if (isset($data['product']))
      {
        showProductDetails($data['product']);
      }
      break;
    case 'shoppingcart':
      if (isset($data['cart']))
      {
        showshoppingcartContent();
      }
      break;
    case 'top5':
      showTop5Content();
      break;
    case 'add_product':
      showAddProductForm($data);
      break;
    case 'edit_product':
      if (isset($data))
      {
        showEditProductForm($data);
      }
      break;
    default:
      show404Content();
      break;
  }
}

function showFooter()
{
  echo "<footer>";
  echo "<p>&copy; Amr Adwan 2023</p>";
  echo "</footer>";
}
function showBodySection($data)
{
  echo "<body>";
  ShowHeader($data);

  if ($data['page'] !== "404")
  {
    echo "<div class=\"text\">";
    showNavMenu($data);
  }
  showContent($data);
  echo "</div>";
  if ($data['page'] !== "404")
    showFooter();
  echo "</body>";
}

function showHtmlEnd()
{
  echo "</html>";
}

function getRequestedPage()
{
  // A list of allowed pages
  $allowedPages = ['home', 'about', 'contact', 'register', 'login', 'logout',
    'change_password', 'thanks', 'webshop', 'product_details', 'shoppingcart',
    'top5', 'add_product', 'edit_product'];

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

function showResponsePage($data)
{
  // TODO een grote switch!

  showHtmlstatement();
  showHeadSection($data);
  showBodySection($data);
  showHtmlEnd();
}

$page = getRequestedPage();
$data = processRequest($page);
showResponsePage($data);