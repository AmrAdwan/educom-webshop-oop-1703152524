<?php
include_once "BasicDoc.php";
// require_once "../data_access_layer.php";
// require_once "../session_manager.php";

abstract class ProductDoc extends BasicDoc 
{
  protected $data;
  public function __construct($data)
  {
    $this->data = $data;
  }


  protected function showHeaderContent()
  {
    $this->showTitle();
    $this->showCssLinks();
    $this->showHeader();
  }

  private function showTitle()
  {
    echo "<title>";
    // echo $this->data["title"];
    echo ucfirst($this->data['page']);
    echo "</title>";
  }

  private function showCssLinks()
  {
    echo "<link rel=\"stylesheet\" href=\"./CSS/stylesheet.css\">";
    echo "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">";
    echo "<link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">";
  }

  protected function showBodyContent()
  {
    echo "<div class=\"text\">";
    $this->showMenu();
    $this->showContent();
    $this->showForm();
    echo "</div>";
    $this->showFooter();
  }

  protected function showHeader()
  {
    
    // echo "<h1>";
    // echo $this->data["header"];
    // echo "</h1>";
  }

  private function showMenuItem($link, $label)
  {
    echo "<li><a href=\"index.php?page=$link\">$label</a></li>";
  }
  private function showMenu()
  {
    $menu['menu'] = array(
      'home' => 'Home',
      'about' => 'About',
      'contact' => 'Contact',
      'webshop' => 'Webshop',
      'top5' => 'Top 5'
    );
    echo '<nav>' . PHP_EOL;
    echo '<ul class="menu">' . PHP_EOL;
    foreach ($menu['menu'] as $link => $label)
    {
      $this->showMenuItem($link, $label);
    }
    echo '</ul>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;
  }

  protected function showContent()
  {

  }

  private function showFooter()
  {
    echo "<footer>";
    echo "<p>&copy; Amr Adwan 2023</p>";
    echo "</footer>";
  }

  abstract protected function showForm();

  abstract protected function getError($form, $key);
}
?>