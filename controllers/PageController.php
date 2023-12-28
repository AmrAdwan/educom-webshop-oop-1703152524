<?php
include_once 'models/PageModel.php';
class PageController
{
  private $model;

  public function __construct($model)
  {
    $this->model = $model;
  }
  // private function showResponsePage($data)
  // {
  //   // var_dump($data);
  //   if (!isset($data['page']))
  //   {
  //     // Handle the case where 'page' is not set
  //     echo "Page not specified";
  //     return;
  //   }

  //   switch ($data['page'])
  //   {
  //     case 'about':
  //       $data['title'] = 'About';
  //       $doc = new AboutDoc($data);
  //       break;
  //     case 'add_product':
  //       $doc = new AddproductDoc($data);
  //       break;
  //     case 'edit_product':
  //       $doc = new EditproductDoc($data);
  //       break;
  //     case 'top5':
  //       $doc = new Top5Doc($data);
  //       break;
  //     case 'product_details':
  //       $doc = new ProductdetailsDoc($data);
  //       break;
  //     case 'home':
  //       $doc = new HomeDoc($data);
  //       break;
  //     case 'change_password':
  //       $doc = new ChangePasswordDoc($data);
  //       break;
  //     case 'register':
  //       $doc = new RegisterDoc($data);
  //       break;
  //     case 'contact':
  //       $doc = new ContactDoc($data);
  //       break;
  //     case 'login':
  //       $doc = new LoginDoc($data);
  //       break;
  //     case 'shopping_cart':
  //       $doc = new ShoppingcartDoc($data);
  //       break;
  //     case 'webshop':
  //       $doc = new WebshopDoc($data);
  //       break;
  //     default:
  //       echo "Page not found";
  //       return;
  //   }

  //   // Assuming each Doc class has a method to render the page
  //   $doc->show();
  // }

  private function showResponsePage()
  {
    $page = $this->model->getData('page');
    switch ($page)
    {
      case 'home':
        $doc = new HomeDoc($this->model);
        break;
      case 'about':
        $doc = new AboutDoc($this->model);
        break;
      default:
        $doc = new ErrorDoc($this->model);
        break;
    }
    $doc->show();
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
    // Update model based on the page and any input data
    // For example, if it's a form submission, validate the data and update the model

    // Set the page in the model
    $this->model->setData('page', $page);

    // Render the appropriate view
    $this->showResponsePage();
  }

}


?>