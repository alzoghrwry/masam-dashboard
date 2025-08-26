<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\ActivityLogger;

class AuthController {
    private User $userModel;
    use ActivityLogger;

    public function __construct() {
        $this->userModel = new User();
        $this->initLogger(); 
    }

    public function login() {

        $input = json_decode(file_get_contents("php://input"), true);
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
           
            $this->logActivity('LOGIN_FAIL', "Failed login attempt for '$email'");

            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'Invalid credentials']);
            exit;
        }

        $token = bin2hex(random_bytes(32));
        $this->userModel->update($user['id'], ['token'=>$token]);

      
        $this->logActivity('LOGIN', "User '{$user['name']}' logged in successfully");

        echo json_encode([
            'status'=>'success',
            'message'=>'Login successful',
            'token'=>$token,
            'user'=>[
                'id'=>$user['id'],
                'name'=>$user['name'],
                'role'=>$user['role']
            ]
        ]);
        exit;
    }

    public function logout() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            echo json_encode(['status'=>'error','message'=>'Token missing']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = $this->userModel->findByToken($token);

        if (!$user) {
            echo json_encode(['status'=>'error','message'=>'Invalid token']);
            exit;
        }

        $this->userModel->update($user['id'], ['token'=>null]);

       
        $this->logActivity('LOGOUT', "User '{$user['name']}' logged out");

        echo json_encode(['status'=>'success','message'=>'Logged out successfully']);
        exit;
    }

    public static function requireLogin() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'Token missing']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = (new User())->findByToken($token);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['status'=>'error','message'=>'Invalid token']);
            exit;
        }

        return $user;
    }

    public static function requireAdmin() {
        $user = self::requireLogin();
        if ($user['role'] !== 'مدير') {
            http_response_code(403);
            echo json_encode(['status'=>'error','message'=>'Access denied']);
            exit;
        }
        return $user;
    }
}
