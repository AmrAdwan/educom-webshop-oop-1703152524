<?php
include 'data_access_layer.php';
function authenticateUser($email, $password)
{
    // global $conn; // Use the global connection variable
    $user = findUserByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Authentication successful
    }
    return null; // Authentication failed
}

function doesEmailExist($email)
{
    // global $conn; // Use the global connection variable
    $user = findUserByEmail($email);
    return $user !== null; // Returns true if user exists, false otherwise
}

// function storeUser($email, $name, $password)
// {
//     // global $conn; // Use the global connection variable
//     // Check if the email already exists
//     if (!doesEmailExist($email)) {
//         return true;
//     }
//     return false; // Email already exists
// }


function updateUserPassword($email, $hashedPassword) {
  global $conn; // Use the global connection variable

  $updateSql = "UPDATE users SET password = ? WHERE email = ?";
  $updateStmt = mysqli_prepare($conn, $updateSql);
  mysqli_stmt_bind_param($updateStmt, "ss", $hashedPassword, $email);
  $success = mysqli_stmt_execute($updateStmt);
  mysqli_stmt_close($updateStmt);

  return $success;
}

?>