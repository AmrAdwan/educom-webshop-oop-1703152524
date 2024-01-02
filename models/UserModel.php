<?php
include_once "PageModel.php";
include_once "user_service.php";
require_once ("data_access_layer.php");

class UserModel extends PageModel
{
  public $name = '';
  public $email = '';
  public $password = '';
  public $emailErr = '';
  private $userId = 0;
  public $valid = false;
  public $genericMessage = '';
  const RESULT_OK = 0;
  const RESULT_UNKNOWN_USER = -1;
  const RESULT_WRONG_PASSWORD = -2;

  public function __construct($pageModel)
  {
    parent::__construct($pageModel);
  }

  public function setPage($newPage)
  {
    $allowedPages = [
      'home',
      'about',
      'contact',
      'register',
      'login',
      'logout',
      'change_password',
      'thanks'
    ];
    if (in_array($newPage, $allowedPages))
    {
      $this->page = $newPage;
    }
  }

  public function logError($msg)
  {
    echo "LOGGING TO THE SERVER LOG: " . $msg;
  }
  public function validateLogin()
  {
    $loginData = [
      'logemail' => '',
      'logpassword' => '',
    ];
    $errors = [];
    $name = $id = '';

    // $logvalid = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      foreach ($loginData as $key => $value)
      {
        $loginData[$key] = getPostVar($key);
      }

      // Check if email and password are not empty
      if (empty($loginData['logemail']))
      {
        $errors['logemail'] = 'Please enter your email.';
      }

      if (empty($loginData['logpassword']))
      {
        $errors['logpassword'] = 'Please enter your password.';
      }

      if (empty($errors))
      {
        try
        {
          // Use authenticateUser function from user_service.php 
          $userResult = $this->authenticateUser($loginData['logemail'], $loginData['logpassword']);
          switch ($userResult["result"])
          {
            case self::RESULT_OK:
              $user = $userResult["user"]; // Get the user array from the result
              $_SESSION['user_name'] = [
                'logemail' => $loginData['logemail'],
                'logname' => $user['name'], // Use the 'name' from the user array
                'id' => $user['id'] // Use the 'id' from the user array
              ];
              $name = $user['name'];
              $id = $user['id'];

              // $logvalid = true;
              $this->valid = true;
              break;
            case self::RESULT_UNKNOWN_USER:
              $errors['logemail'] = 'Email address not found. Please try again or register.';
              break;
            case self::RESULT_WRONG_PASSWORD:
              $errors['logpassword'] = 'Incorrect password. Please try again.';
              break;
          }
        } catch (Exception $e)
        {
          logError("Authentication failed: " . $e->getMessage());
          $errors['generic'] = "There is a technical issue, please try later";
        }
      }

    }
    return [
      // 'logvalid' => $logvalid,
      'logvalid' => $this->valid,
      'logname' => $name,
      'id' => $id,
      'errors' => $errors,
      'loginData' => $loginData
    ];
  }

  private function authenticateUser($email, $password)
  {
    $user = findUserByEmail($email);
    if (empty($user))
    {
      return ['result' => self::RESULT_UNKNOWN_USER];
    }
    if (!password_verify($password, $user['password']))
    {
      return ['result' => self::RESULT_WRONG_PASSWORD];
    }
    return ['result' => self::RESULT_OK, "user" => $user];
  }

  public function doLoginUser()
  {
    $this->sessionManager->doLoginUser($this->name, $this->userId);
    $this->genericMessage = "Login successful";
  }

  public function doesEmailExist($email)
  {
    $user = findUserByEmail($email);
    return $user !== null; // Returns true if user exists, false otherwise
  }

  // public function updateUserPassword($email, $hashedPassword)
  // {
  //   return updatePassword($email, $hashedPassword);
  // }





  // public function setUserData($userId, $name, $email)
  // {
  //   $this->userId = $userId;
  //   $this->userName = $name;
  //   $this->userEmail = $email;

  //   $this->setData('userId', $userId);
  //   $this->setData('userName', $name);
  //   $this->setData('userEmail', $email);
  // }

  // public function getUserId()
  // {
  //   return $this->userId;
  // }

  // public function getUserName()
  // {
  //   return $this->userName;
  // }

  // public function getUserEmail()
  // {
  //   return $this->userEmail;
  // }

  // Authentication function
  // public function authenticateUser($email, $password)
  // {
  //   return authenticateUser($email, $password);
  // }

  // // Check if email exists
  // public function doesEmailExist($email)
  // {
  //   return doesEmailExist($email);
  // }

  // // Update user password
  // public function updateUserPassword($email, $newPassword)
  // {
  //   $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  //   return updateUserPassword($email, $hashedPassword);
  // }
}
?>