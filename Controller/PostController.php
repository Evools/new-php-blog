<?php

class PostController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getAllPosts()
  {
    $query = "SELECT posts.*, users.name as author_name, categories.name as category_name 
              FROM posts 
              LEFT JOIN users ON posts.user_id = users.id 
              LEFT JOIN categories ON posts.category_id = categories.id 
              ORDER BY posts.created_at DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function deletePost($post_id)
  {
    try {
      $query = "DELETE FROM posts WHERE id = ?";
      $stmt = $this->conn->prepare($query);
      return $stmt->execute([$post_id]);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function createPost($title, $content, $category_id, $user_id, $status = 'draft')
  {
    try {
      $query = "INSERT INTO posts (title, content, category_id, user_id, status, created_at) 
                  VALUES (?, ?, ?, ?, ?, NOW())";

      $stmt = $this->conn->prepare($query);
      return $stmt->execute([$title, $content, $category_id, $user_id, $status]);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function getPostById($id)
  {
    $query = "SELECT posts.*, users.name as author_name, categories.name as category_name 
              FROM posts 
              LEFT JOIN users ON posts.user_id = users.id 
              LEFT JOIN categories ON posts.category_id = categories.id 
              WHERE posts.id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updatePost($id, $title, $content, $status)
  {
    try {
      $query = "UPDATE posts 
                   SET title = ?, 
                       content = ?, 
                       status = ?,
                       updated_at = NOW()
                   WHERE id = ?";

      $stmt = $this->conn->prepare($query);
      $result = $stmt->execute([$title, $content, $status, $id]);

      if (!$result) {
        throw new Exception("Failed to update post");
      }

      return true;
    } catch (PDOException $e) {
      throw new Exception("Database error: " . $e->getMessage());
    }
  }
}
