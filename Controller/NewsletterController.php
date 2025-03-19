<?php

class NewsletterController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // Newsletter Methods
  public function createNewsletter($subject, $content)
  {
    try {
      $stmt = $this->conn->prepare("INSERT INTO newsletters (subject, content, status, created_at) VALUES (?, ?, 'draft', NOW())");
      return $stmt->execute([$subject, $content]);
    } catch (PDOException $e) {
      throw new Exception("Error creating newsletter: " . $e->getMessage());
    }
  }

  public function getAllNewsletters()
  {
    $sql = "SELECT * FROM newsletters ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getNewsletterById($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM newsletters WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updateNewsletter($id, $subject, $content)
  {
    $stmt = $this->conn->prepare("UPDATE newsletters SET subject = ?, content = ? WHERE id = ?");
    return $stmt->execute([$subject, $content, $id]);
  }

  public function deleteNewsletter($id)
  {
    try {
      $sql = "DELETE FROM newsletters WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при удалении рассылки");
    }
  }

  public function sendNewsletter($id)
  {
    try {
      $sql = "UPDATE newsletters SET status = 'sent', sent_at = CURRENT_TIMESTAMP WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при отправке рассылки");
    }
  }

  // Subscriber Methods
  public function getAllSubscribers()
  {
    $sql = "SELECT * FROM subscribers ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getActiveSubscribers()
  {
    $sql = "SELECT * FROM subscribers WHERE status = 'active' ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addSubscriber($email, $name = null)
  {
    try {
      $sql = "INSERT INTO subscribers (email, name) VALUES (:email, :name)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':name', $name);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при добавлении подписчика");
    }
  }

  public function updateSubscriberStatus($id, $status)
  {
    try {
      $sql = "UPDATE subscribers SET status = :status WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':status', $status);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при обновлении статуса подписчика");
    }
  }

  public function deleteSubscriber($id)
  {
    try {
      $sql = "DELETE FROM subscribers WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Ошибка при удалении подписчика");
    }
  }
}
