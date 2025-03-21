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

  public function updateUser($id, $name, $email)
  {
    try {
      $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при обновлении пользователя");
    }
  }

  public function updatePassword($id, $password)
  {
    try {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $sql = "UPDATE users SET password = :password WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':password', $hashedPassword);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при обновлении пароля");
    }
  }

  public function deleteUser($id)
  {
    try {
      $sql = "DELETE FROM users WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute() && $stmt->rowCount() > 0) {
        return true;
      }
      return false;
    } catch (PDOException $e) {
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
  public function getTotalUsers()
  {
    $query = "SELECT COUNT(*) as total FROM users";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }
  public function getRecentUsers($limit = 3)
  {
    $query = "SELECT name, role, created_at FROM users ORDER BY created_at DESC LIMIT :limit";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getUserById($id)
  {
    try {
      $sql = "SELECT * FROM users WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Ошибка при получении данных пользователя");
    }
  }
  // Add these methods to your UserController class

  public function updateUserWithoutPassword($id, $name, $email, $role)
  {
    try {
      $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':role', $role);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при обновлении пользователя: " . $e->getMessage());
    }
  }

  public function updateUserWithPassword($id, $name, $email, $password, $role)
  {
    try {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $sql = "UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $hashedPassword);
      $stmt->bindParam(':role', $role);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при обновлении пользователя: " . $e->getMessage());
    }
  }
}
