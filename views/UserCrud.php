<?php
// include_once "DataBase.php";
class UserCrud
{
  private $crud;

  public function __construct($crud)
  {
    $this->crud = $crud;
  }

  public function createUser($userData)
  {
    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    return $this->crud->createRow($sql, $userData);
  }

  public function readUserByEmail($email)
  {
    $sql = "SELECT * FROM users WHERE email = :email";
    return $this->crud->readOneRow($sql, ['email' => $email]);
  }

  public function updateUser($userId, $userData)
  {
    $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
    return $this->crud->updateRow($sql, array_merge($userData, ['id' => $userId]));
  }

  public function updateUserPassword($userId, $hashedPassword)
  {
    $sql = "UPDATE users SET password = :password WHERE id = :id";
    return $this->crud->updateRow($sql, ['id' => $userId, 'password' => $hashedPassword]);
  }

  public function deleteUser($userId)
  {
    $sql = "DELETE FROM users WHERE id = :id";
    return $this->crud->deleteRow($sql, ['id' => $userId]);
  }
}


?>