<?php
// Database configuration
$servername = '127.0.0.1';
$username = 'amr_web_shop_user';
$password = 'Amr-ma,236037';
$dbname = 'amr_webshop';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn)
{
  die("Connection failed: " . mysqli_connect_error());
}

// users database
function findUserByEmail($email)
{
  global $conn;
  $sql = "SELECT * FROM users WHERE email = ?"; // Select all columns
  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt)
  {
    // Handle error in statement preparation
    die('Statement preparation failed: ' . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($stmt, "s", $email);

  if (!mysqli_stmt_execute($stmt))
  {
    // Handle error in statement execution
    die('Execute failed: ' . mysqli_error($conn));
  }

  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($result);

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return $user;
}

function findEmailById($id)
{
  global $conn;
  $sql = "SELECT email FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt)
  {
    die('Statement preparation failed: ' . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($stmt, "i", $id);

  if (!mysqli_stmt_execute($stmt))
  {
    die('Execute failed: ' . mysqli_error($conn));
  }

  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($result);

  if ($user)
  {
    $email = $user['email']; // Fetch only the email
  } else
  {
    $email = null; // No user found with that ID
  }

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);
  return $email;
}


function saveUser($email, $name, $password)
{
  global $conn; // Use the global connection variable
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO users (email, name, password) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sss", $email, $name, $hashedPassword);
  $success = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  return $success;
}

function getAdminStatus($userId)
{
  global $conn;

  $sql = "SELECT is_admin FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt)
  {
    die('Statement preparation failed: ' . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($stmt, "i", $userId);

  if (!mysqli_stmt_execute($stmt))
  {
    die('Execute failed: ' . mysqli_error($conn));
  }

  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($result);

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  if ($user)
  {
    return $user['is_admin'] == 1; // Assuming is_admin is a boolean or integer (0 or 1)
  } else
  {
    return false; // User not found
  }
}



// products database
function getProducts()
{
  global $conn;
  $sql = "SELECT * FROM products";
  $stmt = mysqli_prepare($conn, $sql);

  if (!mysqli_stmt_execute($stmt))
  {
    // Handle error in statement execution
    die('Execute failed: ' . mysqli_error($conn));
  }

  $result = mysqli_stmt_get_result($stmt);

  $products = [];
  while ($row = mysqli_fetch_assoc($result))
  {
    $products[] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'description' => $row['description'],
      'price' => $row['price'],
      'file_name' => $row['file_name']
    ];
  }

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return $products;
}


function getProductById($id)
{
  global $conn;

  if (!$conn)
  {
    die("Database connection error: " . mysqli_connect_error());
  }

  $sql = "SELECT * FROM products WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt)
  {
    die("Error preparing statement: " . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($stmt, "i", $id);

  if (!mysqli_stmt_execute($stmt))
  {
    die("Error executing query: " . mysqli_stmt_error($stmt));
  }

  $result = mysqli_stmt_get_result($stmt);

  if (!$result)
  {
    die("Error fetching result: " . mysqli_error($conn));
  }

  $product = mysqli_fetch_assoc($result);

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);
  // var_dump($product);
  return $product;
}

function insertOrder($userId, $cartItems)
{
  global $conn;

  // Start transaction
  mysqli_begin_transaction($conn);

  try
  {
    // Get the next order number
    $result = mysqli_query($conn, "SELECT MAX(order_nr) as max_order_nr FROM orders");
    $row = mysqli_fetch_assoc($result);
    $nextOrderNr = $row['max_order_nr'] + 1;

    // Insert into orders table
    $orderDate = date('Y-m-d H:i:s'); // Current date and time
    $sqlOrder = "INSERT INTO orders (user_id, order_date, order_nr) VALUES (?, ?, ?)";
    $stmtOrder = mysqli_prepare($conn, $sqlOrder);
    mysqli_stmt_bind_param($stmtOrder, "isi", $userId, $orderDate, $nextOrderNr);
    mysqli_stmt_execute($stmtOrder);
    $orderId = mysqli_insert_id($conn); // Get the ID of the inserted order
    mysqli_stmt_close($stmtOrder);

    // Insert into order_lines table
    $sqlOrderLine = "INSERT INTO orderlines (order_id, product_id, quantity_per_product) VALUES (?, ?, ?)";
    foreach ($cartItems as $item)
    {
      $stmtOrderLine = mysqli_prepare($conn, $sqlOrderLine);
      mysqli_stmt_bind_param($stmtOrderLine, "iii", $orderId, $item['id'], $item['quantity']);
      mysqli_stmt_execute($stmtOrderLine);
      mysqli_stmt_close($stmtOrderLine);
    }

    // Commit transaction
    mysqli_commit($conn);
    return true;
  } catch (Exception $e)
  {
    // Rollback transaction on error
    mysqli_rollback($conn);
    return false;
  }
}

function getTop5Products()
{
  global $conn;

  $sql = "SELECT p.id, p.name, p.file_name, SUM(ol.quantity_per_product) AS total_quantity
          FROM orders o
          JOIN orderlines ol ON o.id = ol.order_id
          JOIN products p ON ol.product_id = p.id
          WHERE o.order_date >= CURRENT_DATE - INTERVAL 7 DAY
          GROUP BY p.id, p.name, p.file_name
          ORDER BY total_quantity DESC
          LIMIT 5";

  $result = mysqli_query($conn, $sql);

  if (!$result)
  {
    die("Error executing query: " . mysqli_error($conn));
  }

  $topProducts = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);

  return $topProducts;
}


function saveProduct($productName, $productPrice, $productDescription, $imageName)
{
  global $conn;

  // Prepare an INSERT statement
  $sql = "INSERT INTO products (name, price, description, file_name) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt)
  {
    die('Statement preparation failed: ' . mysqli_error($conn));
  }

  // Bind parameters to the prepared statement
  mysqli_stmt_bind_param($stmt, "sdss", $productName, $productDescription, $productPrice, $imageName);

  // Execute the statement and check for success
  $success = mysqli_stmt_execute($stmt);

  // Close the statement
  mysqli_stmt_close($stmt);

  return $success;
}

function editProduct($id, $productName, $productPrice, $productDescription, $imageName)
{
    global $conn;

    // Prepare an UPDATE statement
    $sql = "UPDATE products SET name = ?, price = ?, description = ?, file_name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt)
    {
        die('Statement preparation failed: ' . mysqli_error($conn));
    }

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "sdssi", $productName, $productPrice, $productDescription, $imageName, $id);

    // Execute the statement and check for success
    $success = mysqli_stmt_execute($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    return $success;
}



// Close the database connection at the end of the script
// $conn->close();
?>