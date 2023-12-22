<?php
// include 'validations.php';
function getErrorRegister($registerResult, $key)
{
  return isset($registerResult['errors'][$key]) ? $registerResult['errors'][$key] : '';
}
function showRegisterForm($registerResult)
{

  if (!isset($registerResult['regvalid']) || !$registerResult['regvalid']) { /* Show the next part only when $valid is false */
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
    echo "<input type=\"text\" id=\"regname\" name=\"regname\" value=\"" . $registerData['regname'] ?? '' . "\" />";
    echo "\" />";
    echo "<span class=\"error\">*";
    echo getErrorRegister($registerResult, 'regname');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"email\">Email Address</label>";
    echo "<input type=\"email\" id=\"regemail\" name=\"regemail\" value=\"" . $registerData['regemail'] ?? '' . "\" />";
    echo "\" />";
    echo "<span class=\"error\">*";
    echo getErrorRegister($registerResult, 'regemail');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"phone\">Password</label>";
    echo "<input type=\"password\" id=\"regpassword1\" name=\"regpassword1\" />";
    echo "<span class=\"error\">*";
    echo getErrorRegister($registerResult, 'regpassword1');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"phone\">Repeat Password</label>";
    echo "<input type=\"password\" id=\"regpassword2\" name=\"regpassword2\" />";
    echo "<span class=\"error\">*";
    echo getErrorRegister($registerResult, 'regpassword2');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<button type=\"Submit\">Send</button>";
    echo "</form>";
  }
}
?>