<?php
include_once "../views/DataBase.php";

$db = new DataBase();
$db->CreateConnection();

// Testing Create
$insertSql = "INSERT INTO products (name, description, price, file_name) VALUES (:name, :description, :price, :file_name)";
$insertParams = [
    'name' => 'Test Product', 
    'description' => 'Test Description', 
    'price' => 10.99,
    'file_name' => 'test_image.png'
];
$lastInsertId = $db->createRow($insertSql, $insertParams);
echo "Inserted Row ID: $lastInsertId\n";

// Testing Read One Row
$selectOneSql = "SELECT * FROM products WHERE id = :id";
$product = $db->readOneRow($selectOneSql, ['id' => $lastInsertId]);
echo "Single Product: ";
var_dump($product);

// Testing Read Multiple Rows
$selectAllSql = "SELECT * FROM products";
$allProducts = $db->readMultipleRows($selectAllSql);
echo "All Products: ";
var_dump($allProducts);

// Testing Update (including image file name)
$updateSql = "UPDATE products SET price = :price, file_name = :file_name WHERE id = :id";
$updateParams = [
    'price' => 15.99, 
    'file_name' => 'test_image.png', 
    'id' => $lastInsertId
];
$db->updateRow($updateSql, $updateParams);
echo "Updated Product ID $lastInsertId\n";

// Re-fetch to show update results
$updatedProduct = $db->readOneRow($selectOneSql, ['id' => $lastInsertId]);
echo "Updated Product: ";
var_dump($updatedProduct);

// Testing Delete
$deleteSql = "DELETE FROM products WHERE id = :id";
$db->deleteRow($deleteSql, ['id' => $lastInsertId]);
echo "Deleted Product ID $lastInsertId\n";

// $deletedProduct = $db->readOneRow($selectOneSql, ['id' => $lastInsertId]);
// echo "Deleted Product (should be null): ";
// var_dump($deletedProduct);


// Verify Deletion
$deletedProduct = $db->readOneRow($selectOneSql, ['id' => $lastInsertId]);
if ($deletedProduct) {
    echo "Deleted Product: ";
    var_dump($deletedProduct);
} else {
    echo "Deleted Product ID $lastInsertId does not exist anymore.\n";
}

?>
