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

  public function emailExists($email): bool
  {
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() > 0;
  }

  public function editUser($id, $name, $email, $password, $role)
  {
    $sql = "UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
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

  public function loginUser($email, $password)
  {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'name' => $user['name'] ?? '',
        'role' => $user['role'] ?? 'user', // если есть роли
      ];
      return $user;
    } else {
      return false;
    }
  }
}
