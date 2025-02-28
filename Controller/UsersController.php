<?php

class UserController
{
  public $name;
  public $email;
  public $password;
  public $role;
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function createUser($name, $email, $password, $role)
  {
    $db = "CREATE TABLE IF NOT EXISTS `users` (
      id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      password VARCHAR(255) NOT NULL,
      role VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $this->conn->exec($db);

    $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function getAllUsers()
  {
    $sql = "SELECT * FROM users";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
