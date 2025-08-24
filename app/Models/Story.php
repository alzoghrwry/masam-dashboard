<?php
namespace App\Models;

use App\Core\App;
use PDO;

class Story {
    public function all() {
        $stmt = App::db()->prepare("SELECT * FROM stories ORDER BY date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id) {
        $stmt = App::db()->prepare("SELECT * FROM stories WHERE id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $stmt = App::db()->prepare("
            INSERT INTO stories (title, date, image, content)
            VALUES (:title, :date, :image, :content)
        ");
        return $stmt->execute([
            ':title' => $data['title'],
            ':date' => $data['date'],
            ':image' => $data['image'],
            ':content' => $data['content']
        ]);
    }

    public function update(int $id, array $data) {
        $stmt = App::db()->prepare("
            UPDATE stories SET title=:title, date=:date, image=:image, content=:content
            WHERE id=:id
        ");
        return $stmt->execute([
            ':title' => $data['title'],
            ':date' => $data['date'],
            ':image' => $data['image'],
            ':content' => $data['content'],
            ':id' => $id
        ]);
    }

    public function delete(int $id) {
        $stmt = App::db()->prepare("DELETE FROM stories WHERE id=:id");
        return $stmt->execute([':id' => $id]);
    }
}
