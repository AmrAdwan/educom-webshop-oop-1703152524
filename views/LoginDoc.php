<?php
include_once "FormsDoc.php";
class LoginDoc extends FormDoc
{

  protected function showHeader()
  {
    echo "<h1>Login</h1>";
  }

  protected function showContent()
  {
    $loginResult = $this->model->validateLogin();
    $this->showForm($loginResult);
  }

  protected function getError($loginResult, $key)
  {
    return isset($loginResult['errors'][$key]) ? $loginResult['errors'][$key] : '';
  }
  protected function showForm($loginResult = [])
  {
    if (!isset($loginResult['logvalid']) || !$loginResult['logvalid'])
    { /* Show the next part only when $valid is false */
      // Extract form data for convenience
      $loginData = $loginResult['loginData'] ?? [];
      echo "<div class=\"formcarry-container\">";
      echo "<form action=\"index.php\" method=\"POST\" class=\"formcarry-form\">";
      echo "<!-- Hidden field to identify the login form -->";
      echo "<input type=\"hidden\" name=\"page\" value=\"login\">";
      echo "<div class=\"input\">";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"email\">Email Address</label>";
      // Check if 'logemail' is set before trying to use it
      $emailValue = isset($loginData['logemail']) ? $loginData['logemail'] : '';
      echo "<input type=\"email\" id=\"logemail\" name=\"logemail\" value=\"" . $emailValue . "\" />";
      // echo "<input type=\"email\" id=\"logemail\" name=\"logemail\" value=\"" . $loginData['logemail'] ?? '' . "\" />";
      // echo "\" />";
      echo "<span class=\"error\">*";
      echo $this->getError($loginResult, 'logemail');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"password\">Password</label>";
      echo "<input type=\"password\" id=\"logpassword\" name=\"logpassword\" />";
      echo "<span class=\"error\">*";
      echo $this->getError($loginResult, 'logpassword');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<button type=\"Submit\">Login</button>";
      echo "</form>";
      echo "</div>";
    } else
    {
      echo $this->getError($loginResult, 'generic');
    }
  }

}

?>