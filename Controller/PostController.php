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
    $result = mysqli_query($this->db, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
  }

  public function getRecentPosts($limit = 5)
  {
    $query = "SELECT id, title, created_at, status FROM posts ORDER BY created_at DESC LIMIT $limit";
    $result = mysqli_query($this->db, $query);
    $posts = [];

    while ($row = mysqli_fetch_assoc($result)) {
      $posts[] = $row;
    }

    return $posts;
  }

  public function createPost($title, $content, $category_id, $user_id)
  {
    $title = mysqli_real_escape_string($this->db, $title);
    $content = mysqli_real_escape_string($this->db, $content);

    $query = "INSERT INTO posts (title, content, category_id, user_id, created_at) 
                  VALUES ('$title', '$content', $category_id, $user_id, NOW())";

    return mysqli_query($this->db, $query);
  }

  public function updatePost($id, $title, $content, $category_id)
  {
    $title = mysqli_real_escape_string($this->db, $title);
    $content = mysqli_real_escape_string($this->db, $content);

    $query = "UPDATE posts 
                  SET title = '$title', 
                      content = '$content', 
                      category_id = $category_id 
                  WHERE id = $id";

    return mysqli_query($this->db, $query);
  }

  public function deletePost($id)
  {
    $query = "DELETE FROM posts WHERE id = $id";
    return mysqli_query($this->db, $query);
  }
}
