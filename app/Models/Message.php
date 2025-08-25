<?php
namespace App\Models;
use App\Core\App;
use PDO;

class Message {
   

    
    public function all(){
        
       
        $stmt = App::db()->prepare("SELECT * FROM messages ORDER BY `messages`.`createdAt` DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function unread(){
        $stmt = App::db()->prepare("SELECT * FROM messages WHERE `read` = 0 ORDER BY `messages`.`createdAt` DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function markAsRead(int $id) {
        $stmt = App::db()->prepare("UPDATE messages SET `read` = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

   public function markAllAsRead() {
    $stmt = App::db()->prepare("UPDATE messages SET `read` = 1 WHERE `read` = 0");
    return $stmt->execute();
}


  // App/Models/Message.php
public function create(array $data): int {
    $stmt = App::db()->prepare("
        INSERT INTO messages (name, email, message, `read`, createdAt) 
        VALUES (:name, :email, :message, 0, :createdAt)
    ");
    $stmt->execute([
        'name' => $data['name'],
        'email' => $data['email'],
        'message' => $data['message'],
        'createdAt' => date('Y-m-d H:i:s')
    ]);
    return (int)App::db()->lastInsertId();
}

}
