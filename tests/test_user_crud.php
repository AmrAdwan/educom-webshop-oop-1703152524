<?php
include_once "../views/UserCrud.php";
$db = new CRUD();
$db->CreateConnection();

$userCrud = new UserCrud($db);

// Example usage of UserCrud methods
$userData = [
  'name' => 'John Doe',
  'email' => 'john@example.com',
  'password' => password_hash('password123', PASSWORD_DEFAULT)
];

// Create user
$userId = $userCrud->createUser($userData);

// Read user
$user = $userCrud->readUserByEmail('john@example.com');

// Update user
$userCrud->updateUser($userId, ['name' => 'John Updated', 'email' => 'johnnew@example.com', 'password' => password_hash('newpassword', PASSWORD_DEFAULT)]);

// Delete user
$userCrud->deleteUser($userId);


?>