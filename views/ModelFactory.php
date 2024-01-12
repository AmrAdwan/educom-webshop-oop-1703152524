<?php
include_once "DataBase.php";
include_once "UserCrud.php";
include_once "ShopCrud.php";
include_once "RatingCrud.php";
// include_once "../models/UserModel.php";
class ModelFactory
{
  private $crud;
  private $pageModel;

  public function __construct($crud)
  {
    $this->crud = $crud;
    // $this->pageModel = $pageModel;
  }

  public function createCrud($name)
  {
    switch ($name)
    {
      case 'User':
        return new UserCrud($this->crud);
      case 'Shop':
        return new shopCrud($this->crud);
      case 'Rating':
        return new RatingCrud($this->crud);

      default:
        throw new Exception("Unknown CRUD type: " . $name);
    }
  }

  public function createModel($name)
  {
    switch ($name)
    {
      case 'Page':
        return new PageModel(NULL);
      case 'User':
        return new UserModel($this->pageModel, $this->createCrud('User'));
      case 'Shop':
        return new WebshopModel($this->pageModel, $this->createCrud('Shop'), $this->createCrud('Rating'));
      default:
        throw new Exception("Unknown Model type: " . $name);
    }
  }
}

?>