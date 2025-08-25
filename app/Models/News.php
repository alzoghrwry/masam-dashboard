<?php
namespace App\Models;

use PDO;
use App\Core\App;

class News
{
    
     public function all() {
        $stmt = App::db()->prepare("SELECT * FROM news ORDER BY date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id)
    {
        $stmt = App::db()->prepare("SELECT * FROM news WHERE id=:id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $stmt = App::db()->prepare("INSERT INTO news (title, date, content, image) VALUES (:title, :date, :content, :image)");
        $stmt->execute([
            ':title'   => $data['title'],
            ':date'    => $data['date'],
            ':content' => $data['content'],
            ':image'   => $data['image'] ?? '',
        ]);
    }

    public function update(int $id, array $data): void
    {
        $stmt = App::db()->prepare("UPDATE news SET title=:title, date=:date, content=:content, image=:image WHERE id=:id");
        $stmt->execute([
            ':title'   => $data['title'],
            ':date'    => $data['date'],
            ':content' => $data['content'],
            ':image'   => $data['image'] ?? '',
            ':id'      => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = App::db()->prepare("DELETE FROM news WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }
}
