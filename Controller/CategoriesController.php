<?php

class CategoriesController
{
  private $conn;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function createCategories($name, $slug = null)
  {
    if (!$slug) {
      $slug = $this->generateSlug($name);
    }

    $create_categories = "INSERT INTO categories (name, slug) VALUES (:name, :slug)";
    $stmt = $this->conn->prepare($create_categories);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
  }

  private function generateSlug($string)
  {
    $string = $this->transliterate($string);
    $slug = mb_strtolower($string, 'UTF-8');
    $slug = trim($slug);
    $slug = preg_replace('/[^a-z0-9-]+/u', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return $slug;
  }

  private function transliterate($text)
  {
    $translit = [
      'а' => 'a',
      'б' => 'b',
      'в' => 'v',
      'г' => 'g',
      'д' => 'd',
      'е' => 'e',
      'ё' => 'yo',
      'ж' => 'zh',
      'з' => 'z',
      'и' => 'i',
      'й' => 'y',
      'к' => 'k',
      'л' => 'l',
      'м' => 'm',
      'н' => 'n',
      'о' => 'o',
      'п' => 'p',
      'р' => 'r',
      'с' => 's',
      'т' => 't',
      'у' => 'u',
      'ф' => 'f',
      'х' => 'kh',
      'ц' => 'ts',
      'ч' => 'ch',
      'ш' => 'sh',
      'щ' => 'shch',
      'ы' => 'y',
      'э' => 'e',
      'ю' => 'yu',
      'я' => 'ya',
      'ь' => '',
      'ъ' => '',
      ' ' => '-'
    ];
    return strtr(mb_strtolower($text, 'UTF-8'), $translit);
  }

  public function getCategories()
  {
    $sql = "SELECT * FROM `categories`";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getCategory($slug)
  {
    $sql = "SELECT * FROM `categories` WHERE slug = :slug";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getTotalCategories()
  {
    $query = "SELECT COUNT(*) as total FROM `categories`";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  public function getRecentCategories($limit = 3)
  {
    $query = "SELECT name, created_at FROM categories ORDER BY created_at DESC LIMIT :limit";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function deleteCategory($id)
  {
    try {
      $sql = "DELETE FROM categories WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при удалении категории");
    }
  }

  public function getCategoryById($id)
  {
    try {
      $sql = "SELECT * FROM categories WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $category = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$category) {
        throw new Exception("Категория не найдена");
      }

      return $category;
    } catch (PDOException $e) {
      throw new Exception("Ошибка при получении категории");
    }
  }

  public function updateCategory($id, $name)
  {
    try {
      if (empty($name)) {
        throw new Exception("Название категории не может быть пустым");
      }

      $sql = "UPDATE categories SET name = :name WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':name', $name);

      if (!$stmt->execute()) {
        throw new Exception("Ошибка при обновлении категории");
      }

      return true;
    } catch (PDOException $e) {
      throw new Exception("Ошибка при обновлении категории");
    }
  }
}
