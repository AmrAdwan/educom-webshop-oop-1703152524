<?php

class PageModel
{
  private $data = [];

  public function __construct()
  {
    $this->setData('isLoggedIn', isUserLoggedIn());
    $this->setData('userName', getLoggedInUserName());
    $this->setData('isAdmin', isUserAdmin());
  }

  public function setData($key, $value)
  {
    $this->data[$key] = $value;
  }

  public function getData($key)
  {
    return isset($this->data[$key]) ? $this->data[$key] : null;
  }
}
?>