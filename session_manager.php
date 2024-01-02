<?php
// session_start();

function doLoginUser($id, $name)
{
  // Set session variables for the logged-in user
  $_SESSION['user_logged_in'] = true;
  $_SESSION['user_id'] = $id;
  $_SESSION['user_name'] = $name;
  
  if (getAdminStatus($id))
  {
    $_SESSION['is_admin'] = 1;
  } else
  {
    $_SESSION['is_admin'] = 0;
  }
}

function isUserLoggedIn()
{
  // Check if the user is logged in
  return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

function getLoggedInUserName()
{
  // Return the name of the logged-in user
  if (isUserLoggedIn())
  {
    return $_SESSION['user_name'];
  }
  return null; // No user is logged in
}

function doLogoutUser()
{
  // Unset session variables and destroy the session
  unset($_SESSION['user_logged_in']);
  unset($_SESSION['user_name']);
  session_destroy();
}

function isUserAdmin()
{
  return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

function showMenuItem($link,$label)
{
  echo "<li><a href=\"index.php?page=$link\">$label</a></li>";
}


?>