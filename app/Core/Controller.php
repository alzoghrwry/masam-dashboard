<?php
namespace App\Core;

class Controller {
    protected function view(string $name, array $data = []): void {
        extract($data);
        require __DIR__ . '/../../Views/' . $name . '.php';
    }
  


   public static function json(int $status = 200) {
         http_response_code($status);
         header("Access-Control-Allow-Origin: http://localhost:5173");
         header("Access-Control-Allow-Methods: POST,GET, OPTIONS");
         header("Access-Control-Allow-Headers: Content-Type, Accept");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;
  
        //  echo json_encode($data);
    exit;
}



}
