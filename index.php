<?php
session_start();
include 'validations.php';
include 'shoppingcart.php';
include 'views/HtmlDoc.php';
include 'views/BasicDoc.php';
include 'views/HomeDoc.php';
include 'views/AboutDoc.php';
include 'views/ContactDoc.php';
include 'views/ThanksDoc.php';
include 'views/RegisterDoc.php';
include 'views/LoginDoc.php';
include 'views/Top5Doc.php';
include 'views/WebshopDoc.php';
include 'views/AddproductDoc.php';
include 'views/EditproductDoc.php';
include 'views/ShoppingcartDoc.php';
include 'views/ProductdetailsDoc.php';
include 'views/ChangePasswordDoc.php';
include 'views/ErrorDoc.php';
require_once('controllers/PageController.php');
require_once('views/ModelFactory.php');
require_once('views/RatingCrud.php');

// Create an instance of CRUD
$crud = new CRUD();

// Check if the request is for AJAX
if (isset($_GET['action']) && $_GET['action'] === 'ajax')
{
  // Handle AJAX requests
  $ajaxController = new AjaxController($crud);
  $ajaxController->handleRequest();
} else
{
  // Handle regular page requests
  $modelFactory = new ModelFactory($crud);
  $controller = new PageController($modelFactory);
  $controller->handleRequest();
}