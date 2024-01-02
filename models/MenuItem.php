<?php
include_once "SessionManager.php";

// class MenuItem
// {
//   public function __construct($link, $label, )
//   {

//   }
//   public function MenuItem ($link, $label)
//   {
//     echo "<li><a href=\"index.php?page=$link\">$label</a></li>";
//   }
// }


class MenuItem
{
  private $link;
  private $label;

  public function __construct($link, $label, $userName = null)
  {
    $this->link = $link;
    $this->label = $label;
    if ($userName)
    {
      $this->label .= "[$userName]";
    }
  }

  public function render()
  {
    echo "<li><a href=\"index.php?page={$this->link}\">{$this->label}</a></li>";
  }
}


?>