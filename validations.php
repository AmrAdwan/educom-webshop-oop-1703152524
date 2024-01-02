<?php
// Include the user_service.php file
include 'user_service.php';

function getPostVar($key, $default = '')
{
  if (isset($_POST[$key]))
  {
    return testInput($_POST[$key]);
  }
  return $default;
}

function testInput($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function getVar($key, $default = '')
{
  if (isset($_GET[$key]))
  {
    return testInput($_GET[$key]);
  }
  return $default;
}

function logError($msg)
{
  echo "LOGGING TO THE SERVER LOG: " . $msg;
}

function validateLogin()
{
  $loginData = [
    'logemail' => '',
    'logpassword' => '',
  ];
  $errors = [];
  $name = $id = '';

  $logvalid = false;

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
        $userResult = authenticateUser($loginData['logemail'], $loginData['logpassword']);
        switch ($userResult["result"])
        {
          case RESULT_OK:
            $user = $userResult["user"]; // Get the user array from the result
            $_SESSION['user_name'] = [
              'logemail' => $loginData['logemail'],
              'logname' => $user['name'], // Use the 'name' from the user array
              'id' => $user['id'] // Use the 'id' from the user array
            ];
            $name = $user['name'];
            $id = $user['id'];

            $logvalid = true;
            break;
          case RESULT_UNKNOWN_USER:
            $errors['logemail'] = 'Email address not found. Please try again or register.';
            break;
          case RESULT_WRONG_PASSWORD:
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
    'logvalid' => $logvalid,
    'logname' => $name,
    'id' => $id,
    'errors' => $errors,
    'loginData' => $loginData
  ];
}

function validateRegister()
{
  $registerData = [
    'regname' => '',
    'regemail' => '',
    'regpassword1' => '',
    'regpassword2' => '',
  ];
  $errors = [];

  $regvalid = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    foreach ($registerData as $key => $value)
    {
      $registerData[$key] = getPostVar($key);
    }

    // Validation checks
    if (empty($registerData['regname']))
    {
      $errors['regname'] = 'Insert a name.';
    }

    if (empty($registerData['regemail']) || !filter_var($registerData['regemail'], FILTER_VALIDATE_EMAIL))
    {
      $errors['regemail'] = 'Please insert a valid email';
    }


    if (empty($registerData['regpassword1']))
    {
      $errors['regpassword1'] = 'Please insert a password';
    }
    if (empty($registerData['regpassword2']))
    {
      $errors['regpassword2'] = 'Please insert the password one more time';
    }
    if (isset($registerData['regpassword1']) && isset($registerData['regpassword2']))
    {
      if ($registerData['regpassword1'] != $registerData['regpassword2'])
      {
        $errors['regpassword2'] = 'The second password does not match the first password!';
      }
    }

    if (empty($errors))
    {
      // check if user email already exists
      $regvalid = !doesEmailExist($registerData['regemail']);
      if (!$regvalid)
      {
        $errors['regemail'] = "Email already exists!";
      }
    }
  }
  return [
    'regvalid' => $regvalid,
    'errors' => $errors,
    'registerData' => $registerData
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
  $errors = [];

  $valid = false;

  // check whether the form is sent
  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    foreach ($formData as $key => $value)
    {
      $formData[$key] = getPostVar($key);
    }

    if (empty($formData['gender']))
    {
      $errors['gender'] = 'Select your gender.';
    }

    if (empty($formData['name']))
    {
      $errors['name'] = 'Insert a name.';
    }

    if (empty($formData['message']))
    {
      $errors['message'] = 'Write your message.';
    }

    if (empty($formData['contact']))
    {
      $errors['contact'] = 'Choose your preferred contact method.';
    } else
    {
      switch ($formData['contact'])
      {
        case 'email':
          if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL) || empty($formData['email']))
          {
            $errors['email'] = 'Insert a valid e-mail address.';
          }
          break;
        case 'phone':
          if (empty($formData['phone']))
          {
            $errors['phone'] = 'Insert a phone number.';
          }
          break;
        case 'post':
          if (
            empty($formData['street']) || empty($formData['housenumber']) ||
            empty($formData['zipcode']) || empty($formData['city']) || empty($formData['province']) || empty($formData['country'])
          )
          {
            $errors['street'] = 'Inster a street name.';
            $errors['housenumber'] = 'Insert a house number.';
            $errors['zipcode'] = 'Insert a zip code.';
            $errors['city'] = 'Insert a city.';
            $errors['province'] = 'Insert a province.';
            $errors['country'] = 'Insert a country.';
          }
          break;
      }
    }

    if (empty($errors))
    {
      $valid = true;
    }
  }
  return [
    'valid' => $valid,
    'errors' => $errors,
    'formData' => $formData
  ];
}

function validateChangePassword()
{
  $changeData = [
    'old_password' => '',
    'new_password' => '',
    'confirm_new_password' => '',
  ];
  $errors = [];
  $changevalid = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {

    foreach ($changeData as $key => $value)
    {
      $changeData[$key] = getPostVar($key);
    }

    // Validation checks
    if (empty($changeData['old_password']))
    {
      $errors['old_password'] = 'Please enter your old password.';
    }
    if (empty($changeData['new_password']))
    {
      $errors['new_password'] = 'Please enter a new password.';
    } elseif ($changeData['new_password'] === $changeData['old_password'])
    {
      $errors['new_password'] = 'New password cannot be the same as the old password.';
    }
    if ($changeData['new_password'] !== $changeData['confirm_new_password'])
    {
      $errors['confirm_new_password'] = 'Passwords do not match.';
    }
  }
  if (empty($errors))
  {
    try
    {
      $id = $_SESSION['user_id'];
      $email = findEmailById($id);

      $user = findUserByEmail($email);

      if ($user && password_verify($changeData['old_password'], $user['password']))
      {
        $changevalid = true;
      } else
      {
        if (!empty($changeData['old_password']))
          $errors['old_password'] = 'Incorrect old password.';
      }
    } catch (Exception $e)
    {
      logError("Registration failed: " + $e->getMessage());
      $errorrs['generic'] = "there is a technical issue, please try later";
    }
  }
  return [
    'changevalid' => $changevalid,
    'errors' => $errors,
    'changeData' => $changeData
  ];
}


function validateAddProduct()
{
  $addProductData = [
    'prodname' => '',
    'proddescription' => '',
    'prodprice' => '',
    'prodimage' => ''
  ];
  $errors = [];
  $addProductvalid = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    // Fetch POST data
    foreach ($addProductData as $key => &$value)
    {
      if ($key != 'prodimage')
      {
        $value = $_POST[$key] ?? '';
      } else
      {
        $value = $_FILES[$key] ?? '';
      }
    }

    // Validate product name
    if (empty($addProductData['prodname']))
    {
      $errors['prodname'] = 'Product name is required.';
    }

    // Validate product price
    if (empty($addProductData['prodprice']) || !is_numeric($addProductData['prodprice']) || $addProductData['prodprice'] < 0)
    {
      $errors['prodprice'] = 'Valid product price is required.';
    }

    // Validate description
    if (empty($addProductData['proddescription']))
    {
      $errors['proddescription'] = 'Product description is required.';
    }

    // Validate image
    if (empty($addProductData['prodimage']['name']))
    {
      $errors['prodimage'] = 'Product image is required.';
    } else
    {
      // Check for valid image types (PNG, GIF, JPEG) and size (less than 2 MB)
      $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
      $maxSize = 2 * 1024 * 1024; // 2MB

      if (!in_array($addProductData['prodimage']['type'], $allowedTypes))
      {
        $errors['prodimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
      } elseif ($addProductData['prodimage']['size'] > $maxSize)
      {
        $errors['prodimage'] = 'Image size too large. Maximum size: 2MB.';
      }
    }

    // Check if there are no errors
    if (empty($errors))
    {
      $addProductvalid = true;
    }
  }

  return [
    'addvalid' => $addProductvalid,
    'addData' => $addProductData,
    'errors' => $errors
  ];
}

function validaEditProduct($product)
{
  $editProductData = [
    'editid' => $product['id'],
    'editname' => $product['name'],
    'editdescription' => $product['description'],
    'editprice' => $product['price'],
    'editimage' => $product['file_name']
  ];
  $errors = [];
  $editProductvalid = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {

    // Update with submitted data
    $editProductData = [
      'editid' => $_POST['editid'] ?? $product['id'],
      'editname' => $_POST['editname'] ?? $product['name'],
      'editdescription' => $_POST['editdescription'] ?? $product['description'],
      'editprice' => $_POST['editprice'] ?? $product['price'],
      'editimage' => $_FILES['editimage']['name'] ?? $product['file_name']
    ];

    // Validate product name
    if (empty($editProductData['editname']))
    {
      $errors['editname'] = 'Product name is required.';
    }

    // Validate product price
    if (empty($editProductData['editprice']) || !is_numeric($editProductData['editprice']) || $editProductData['editprice'] < 0)
    {
      $errors['editprice'] = 'Valid product price is required.';
    }

    // Validate description
    if (empty($editProductData['editdescription']))
    {
      $errors['editdescription'] = 'Product description is required.';
    }

    // Validate image if a new image is being uploaded
    if ($editProductData['editimage'] && $editProductData['editimage'] !== $product['file_name'])
    {
      // Check for valid image types (PNG, GIF, JPEG) and size (less than 2 MB)
      $allowedTypes = ['image/png', 'image/gif', 'image/jpeg'];
      $maxSize = 2 * 1024 * 1024; // 2MB

      if (!in_array($editProductData['editimage']['type'], $allowedTypes))
      {
        $errors['editimage'] = 'Invalid image type. Allowed types: PNG, GIF, JPEG.';
      } elseif ($editProductData['editimage']['size'] > $maxSize)
      {
        $errors['editimage'] = 'Image size too large. Maximum size: 2MB.';
      }
    }

    // Check if there are no errors
    if (empty($errors))
    {
      $editProductvalid = true;
      $editProductData['editimage'] = $editProductData['editimage'] ?? $product['file_name'];
    }
  }

  return [
    'editvalid' => $editProductvalid,
    'editData' => $editProductData,
    'errors' => $errors
  ];
}


?>