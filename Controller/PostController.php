<?php

class PostController
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getTotalPosts()
  {
    $query = "SELECT COUNT(*) as total FROM posts";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  public function getRecentPosts($limit = 5)
  {
    $query = "SELECT p.id, p.title, p.created_at, p.status, u.name as author_name, c.name as category_name 
              FROM posts p 
              LEFT JOIN users u ON p.user_id = u.id 
              LEFT JOIN categories c ON p.category_id = c.id 
              ORDER BY p.created_at DESC LIMIT :limit";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createPost($title, $content, $category_id, $user_id)
  {
    $query = "INSERT INTO posts (title, content, category_id, user_id, created_at) 
              VALUES (:title, :content, :category_id, :user_id, NOW())";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function updatePost($id, $title, $content, $category_id)
  {
    $query = "UPDATE posts 
              SET title = :title, 
                  content = :content, 
                  category_id = :category_id 
              WHERE id = :id";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function deletePost($id)
  {
    $query = "DELETE FROM posts WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }
}
