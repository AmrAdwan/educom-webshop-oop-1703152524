<?php

class PageModel
{
  private $data = [];

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