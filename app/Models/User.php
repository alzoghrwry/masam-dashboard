<?php
namespace App\Models;

use App\Core\App;
use PDO;

class User {
    public function all() {
        $stmt = App::db()->prepare("SELECT id,name,email,role,token FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email) {
        $stmt = App::db()->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByToken(string $token) {
        $stmt = App::db()->prepare("SELECT * FROM users WHERE token=:token LIMIT 1");
        $stmt->execute([':token'=>$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $name,string $email,string $password,string $role='موظف عادي',string $token='') {
        $stmt = App::db()->prepare("INSERT INTO users (name,email,password,role,token) VALUES (:name,:email,:password,:role,:token)");
        return $stmt->execute([
            ':name'=>$name,
            ':email'=>$email,
            ':password'=>password_hash($password,PASSWORD_DEFAULT),
            ':role'=>$role,
            ':token'=>$token
        ]);
    }

    public function update($id,array $data) {
        $fields=[];
        $params=[':id'=>$id];

        if(isset($data['name'])) { $fields[]="name=:name"; $params[':name']=$data['name']; }
        if(isset($data['email'])) { $fields[]="email=:email"; $params[':email']=$data['email']; }
        if(!empty($data['password'])) { $fields[]="password=:password"; $params[':password']=password_hash($data['password'],PASSWORD_DEFAULT); }
        if(isset($data['role'])) { $fields[]="role=:role"; $params[':role']=$data['role']; }
        if(isset($data['token'])) { $fields[]="token=:token"; $params[':token']=$data['token']; }

        $sql="UPDATE users SET ".implode(', ',$fields)." WHERE id=:id";
        $stmt = App::db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = App::db()->prepare("DELETE FROM users WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }
}
