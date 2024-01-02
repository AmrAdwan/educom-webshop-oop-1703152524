<?php
include_once "FormsDoc.php";
class RegisterDoc extends FormDoc
{
  protected function showHeader()
  {
    echo "<h1>Register</h1>";
  }

  protected function showContent()
  {
    // $registerResult = $this->model->getData('registerData');
    $registerResult = $this->model->validateRegister();
    $this->showForm($registerResult);
  }

  protected function getError($registerResult, $key)
  {
    return isset($registerResult['errors'][$key]) ? $registerResult['errors'][$key] : '';
  }
  protected function showForm($registerResult = [])
  {

    if (!isset($registerResult['regvalid']) || !$registerResult['regvalid'])
    { /* Show the next part only when $valid is false */
      // Extract form data for convenience
      $registerData = $registerResult['registerData'] ?? [];
      echo "<div class=\"formcarry-container\">";
      echo "<form action=\"index.php\" method=\"POST\" class=\"formcarry-form\">";
      echo "<!-- Hidden field to identify the register form -->";
      echo "<input type=\"hidden\" name=\"page\" value=\"register\">";
      echo "<div class=\"input\">";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"name\">Name</label>";
      echo "<input type=\"text\" id=\"regname\" name=\"regname\" value=\"" . ($registerData['regname'] ?? '') . "\" />";
      echo "<span class=\"error\">  *";
      echo $this->getError($registerResult, 'regname');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"email\">Email Address</label>";
      echo "<input type=\"email\" id=\"regemail\" name=\"regemail\" value=\"" . ($registerData['regemail'] ?? '') . "\" />";
      echo "<span class=\"error\">  *";
      echo $this->getError($registerResult, 'regemail');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"phone\">Password</label>";
      echo "<input type=\"password\" id=\"regpassword1\" name=\"regpassword1\" />";
      echo "<span class=\"error\">  *";
      echo $this->getError($registerResult, 'regpassword1');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<label for=\"phone\">Repeat Password</label>";
      echo "<input type=\"password\" id=\"regpassword2\" name=\"regpassword2\" />";
      echo "<span class=\"error\">  *";
      echo $this->getError($registerResult, 'regpassword2');
      echo "</span>";
      echo "<br>";
      echo "<br>";
      echo "<button type=\"Submit\">Register</button>";
      echo "</form>";
    } else
    {
      echo $this->getError($registerResult, 'generic');
    }
  }


}

?>