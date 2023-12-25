<?php
include 'data_access_layer.php';

define("RESULT_OK", 0);
define("RESULT_UNKNOWN_USER", -1);
define("RESULT_WRONG_PASSWORD", -2); 

function authenticateUser($email, $password) 
 { 
     // global $conn; // Use the global connection variable 
     $user = findUserByEmail($email);
     if (empty($user)) {
         return ['result' => RESULT_UNKNOWN_USER];
     }  
     if (!password_verify($password, $user['password'])) { 
         return ['result' => RESULT_WRONG_PASSWORD];
     } 
     return ['result' => RESULT_OK, "user" => $user];
}

function doesEmailExist($email)
{
	$user = findUserByEmail($email);
	return $user !== null; // Returns true if user exists, false otherwise
}

function updateUserPassword($email, $hashedPassword)
{
	$conn = connectToDatabase();

	$updateSql = "UPDATE users SET password = ? WHERE email = ?";
	$updateStmt = mysqli_prepare($conn, $updateSql);
	mysqli_stmt_bind_param($updateStmt, "ss", $hashedPassword, $email);
	$success = mysqli_stmt_execute($updateStmt);
	mysqli_stmt_close($updateStmt);

	return $success;
}

?>