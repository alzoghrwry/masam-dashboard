<?php
namespace App\Models;

use App\Core\App;
use PDO;

abstract class BarentModel
{
    protected $table;
    protected $pdo;

    public function __construct()
    {
        $this->pdo = App::db();
    }

    

    
    public function allActive()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
   

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

   

    public function softDelete(int $id)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET deleted_at=NOW() WHERE id=:id");
        $result = $stmt->execute(['id' => $id]);
        return $result;
    }

  
    public function restore(int $id)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET deleted_at=NULL WHERE id=:id");
        $result = $stmt->execute(['id' => $id]);
       
        return $result;
    }

    
}
