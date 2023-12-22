<?php
// include 'validations.php';
function getErrorChangePassword($changeResult, $key)
{
  return isset($changeResult['errors'][$key]) ? $changeResult['errors'][$key] : '';
}
function showChangePasswordForm($changeResult)
{
  if (!isset($changeResult['changevalid']) || !$changeResult['changevalid'])
  { /* Show the next part only when $valid is false */
    echo "<br>";

    echo "<div class=\"formcarry-container\">";
    echo "<form action=\"index.php\" method=\"post\" class=\"formcarry-form\">";
    echo "<!-- Hidden field to identify the change_password form -->";
    echo "<input type=\"hidden\" name=\"page\" value=\"change_password\">";
    echo "<label for=\"old_password\">Old Password:</label>";
    echo "<input type=\"password\" id=\"old_password\" name=\"old_password\">";
    echo "<br>";
    echo "<span class=\"error\">*";
    echo getErrorChangePassword($changeResult, 'old_password');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"new_password\">New Password:</label>";
    echo "<input type=\"password\" id=\"new_password\" name=\"new_password\"><br>";
    echo "<span class=\"error\">*";
    echo getErrorChangePassword($changeResult, 'new_password');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<label for=\"confirm_new_password\">Confirm New Password:</label>";
    echo "<input type=\"password\" id=\"confirm_new_password\" name=\"confirm_new_password\"><br>";
    echo "<span class=\"error\">*";
    echo getErrorChangePassword($changeResult, 'confirm_new_password');
    echo "</span>";
    echo "<br>";
    echo "<br>";
    echo "<button type=\"Submit\">Send</button>";
    echo "</form>";
    echo "</div>";
  }
}
?>