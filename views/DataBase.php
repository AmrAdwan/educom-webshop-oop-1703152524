<?php
class CRUD
{
  public $pdo = null;
  public function __construct()
  {
    $this->CreateConnection();
  }

  public function CreateConnection()
  {
    $connectionString = "mysql:host=localhost;dbname=amr_webshop";
    $userName = 'amr_web_shop_user';
    $password = 'Amr-ma,236037';

    try
    {
      $this->pdo = new PDO($connectionString, $userName, $password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e)
    {
      // Handle connection error
      echo "Connection failed: " . $e->getMessage();
    }
  }

  public function PrepareAndExecute($sql, $params = [])
  {
    try
    {
      $stmt = $this->pdo->prepare($sql);
      // var_dump($params);
      foreach ($params as $key => &$value)
      {
        $stmt->bindValue($key, $value);
      }
      // var_dump($stmt);

      $stmt->setFetchMode(PDO::FETCH_OBJ);
      $stmt->execute();

      return $stmt->fetchAll();
    } catch (PDOException $e)
    {
      // Handle query error
      echo "Query failed: " . $e->getMessage();
    }
  }

  public function createRow($sql, $params = [])
  {
    try
    {
      $this->PrepareAndExecute($sql, $params);
      return $this->pdo->lastInsertId();
    } catch (PDOException $e)
    {
      echo "Insert failed: " . $e->getMessage();
    }
  }

  public function readOneRow($sql, $params = [])
  {
    try
    {
      $results = $this->PrepareAndExecute($sql, $params);
      return $results[0] ?? null; // Return the first row if available, otherwise null
    } catch (PDOException $e)
    {
      echo "Query failed: " . $e->getMessage();
    }
  }

  public function readMultipleRows($sql, $params = [])
  {
    return $this->PrepareAndExecute($sql, $params);
  }

  public function updateRow($sql, $params = [])
  {
    return $this->PrepareAndExecute($sql, $params);
  }

  public function deleteRow($sql, $params = [])
  {
    return $this->PrepareAndExecute($sql, $params);
  }
}
?>