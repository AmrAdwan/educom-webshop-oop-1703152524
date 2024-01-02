<?php
include_once "PageModel.php";
include_once "user_service.php";
require_once("data_access_layer.php");

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
    $this->errors = [];
    $this->name = $id = '';

    // if ($_SERVER['REQUEST_METHOD'] === 'POST')
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page']) && $_POST['page'] === 'login')
    {
      // Get email and password from POST request and assign to class properties
      $this->email = $this->getPostVar('logemail');
      $this->password = $this->getPostVar('logpassword');

      // Check if email and password are not empty
      if (empty($this->email))
      {
        $this->errors['logemail'] = 'Please enter your email.';
      }

      if (empty($this->password))
      {
        $this->errors['logpassword'] = 'Please enter your password.';
      }

      if (empty($this->errors))
      {
        try
        {
          // Use authenticateUser function
          $userResult = $this->authenticateUser($this->email, $this->password);
          switch ($userResult["result"])
          {
            case self::RESULT_OK:
              $user = $userResult["user"];
              $_SESSION['user_name'] = [
                'logemail' => $this->email,
                'logname' => $user['name'],
                'id' => $user['id']
              ];
              $this->name = $user['name'];
              $id = $user['id'];
              $this->valid = true;
              break;
            case self::RESULT_UNKNOWN_USER:
              $this->errors['logemail'] = 'Email address not found. Please try again or register.';
              break;
            case self::RESULT_WRONG_PASSWORD:
              $this->errors['logpassword'] = 'Incorrect password. Please try again.';
              break;
          }
        } catch (Exception $e)
        {
          $this->logError("Authentication failed: " . $e->getMessage());
          $this->errors['generic'] = "There is a technical issue, please try later";
        }
      }
    }
    return [
      'logvalid' => $this->valid,
      'loginData' => [
        'logemail' => $this->email,
        'logpassword' => $this->password
      ],
      'errors' => $this->errors
    ];
  }

  public function validateRegister()
  {
    $this->errors = [];
    $password2 = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      // Assign POST data directly to UserModel properties
      $this->name = $this->getPostVar('regname');
      $this->email = $this->getPostVar('regemail');
      $this->password = $this->getPostVar('regpassword1');
      $password2 = $this->getPostVar('regpassword2');

      // Validation checks
      if (empty($this->name))
      {
        $this->errors['regname'] = 'Insert a name.';
      }

      if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL))
      {
        $this->errors['regemail'] = 'Please insert a valid email.';
      }

      if (empty($this->password))
      {
        $this->errors['regpassword1'] = 'Please insert a password.';
      }

      if (empty($password2))
      {
        $this->errors['regpassword2'] = 'Please confirm the password.';
      }

      if ($this->password !== $password2)
      {
        $this->errors['regpassword2'] = 'The passwords do not match.';
      }

      if (empty($this->errors))
      {
        // Check if user email already exists
        $this->valid = !$this->doesEmailExist($this->email);
        if (!$this->valid)
        {
          $this->errors['regemail'] = "Email already exists!";
        }
      }
    }

    return [
      'regvalid' => $this->valid,
      'errors' => $this->errors,
      'registerData' => [
        'regname' => $this->name,
        'regemail' => $this->email,
        'regpassword1' => $this->password,
        'regpassword2' => $password2
      ]
    ];
  }

  function validateContact()
  {
    $formData = [
      'gender' => '',
      'name' => '',
      'email' => '',
      'phone' => '',
      'street' => '',
      'housenumber' => '',
      'addition' => '',
      'zipcode' => '',
      'city' => '',
      'province' => '',
      'country' => '',
      'message' => '',
      'contact' => ''
    ];
    $this->errors = [];

    // $valid = false;

    // check whether the form is sent
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
      foreach ($formData as $key => $value)
      {
        $formData[$key] = $this->getPostVar($key);
      }

      if (empty($formData['gender']))
      {
        $this->errors['gender'] = 'Select your gender.';
      }

      if (empty($formData['name']))
      {
        $this->errors['name'] = 'Insert a name.';
      }

      if (empty($formData['message']))
      {
        $this->errors['message'] = 'Write your message.';
      }

      if (empty($formData['contact']))
      {
        $this->errors['contact'] = 'Choose your preferred contact method.';
      } else
      {
        switch ($formData['contact'])
        {
          case 'email':
            if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL) || empty($formData['email']))
            {
              $this->errors['email'] = 'Insert a valid e-mail address.';
            }
            break;
          case 'phone':
            if (empty($formData['phone']))
            {
              $this->errors['phone'] = 'Insert a phone number.';
            }
            break;
          case 'post':
            if (
              empty($formData['street']) || empty($formData['housenumber']) ||
              empty($formData['zipcode']) || empty($formData['city']) || empty($formData['province']) || empty($formData['country'])
            )
            {
              $this->errors['street'] = 'Inster a street name.';
              $this->errors['housenumber'] = 'Insert a house number.';
              $this->errors['zipcode'] = 'Insert a zip code.';
              $this->errors['city'] = 'Insert a city.';
              $this->errors['province'] = 'Insert a province.';
              $this->errors['country'] = 'Insert a country.';
            }
            break;
        }
      }

      if (empty($this->errors))
      {
        $this->valid = true;
      }
    }
    return [
      'valid' => $this->valid,
      'errors' => $this->errors,
      'formData' => $formData
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

  public function doLogoutUser()
  {
    $this->sessionManager->doLogoutUser($this->name, $this->userId);
    $this->genericMessage = "Logout successful";
    // session_destroy();
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