<?php
include_once "PageModel.php";
include_once "user_service.php"; 

class UserModel extends PageModel
{
  private $userId;
  private $userName;
  private $userEmail;

  public function setUserData($userId, $userName, $userEmail)
  {
    $this->userId = $userId;
    $this->userName = $userName;
    $this->userEmail = $userEmail;

    $this->setData('userId', $userId);
    $this->setData('userName', $userName);
    $this->setData('userEmail', $userEmail);
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function getUserName()
  {
    return $this->userName;
  }

  public function getUserEmail()
  {
    return $this->userEmail;
  }

  // Authentication function
  public function authenticateUser($email, $password)
  {
    return authenticateUser($email, $password);
  }

  // Check if email exists
  public function doesEmailExist($email)
  {
    return doesEmailExist($email);
  }

  // Update user password
  public function updateUserPassword($email, $newPassword)
  {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    return updateUserPassword($email, $hashedPassword);
  }
}
?>