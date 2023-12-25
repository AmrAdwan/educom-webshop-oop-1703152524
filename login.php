<?php
function getErrorLogin($loginResult, $key)
{
  return isset($loginResult['errors'][$key]) ? $loginResult['errors'][$key] : '';
}
function showLoginForm($loginResult)
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
    echo getErrorLogin($loginResult, 'logemail');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"password\">Password</label>";
    echo "<input type=\"password\" id=\"logpassword\" name=\"logpassword\" />";
    echo "<span class=\"error\">*";
    echo getErrorLogin($loginResult, 'logpassword');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<button type=\"Submit\">Send</button>";
    echo "</form>";
    echo "</div>";
  } else 
  {
    echo getErrorLogin($loginResult, 'generic');
  }
}
?>