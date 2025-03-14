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
    $create_category_table = "CREATE TABLE IF NOT EXISTS `categories` (
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      slug VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $this->conn->exec($create_category_table);

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
}
