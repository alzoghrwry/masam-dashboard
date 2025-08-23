<?php
namespace App\Controllers;

use App\Models\User;
use App\Controllers\AuthController;

class UserController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

   
    public function index() {
        $currentUser = AuthController::requireLogin();
        header('Content-Type: application/json');
        echo json_encode($this->userModel->all());
        exit;
    }

   
    public function store() {
        AuthController::requireAdmin(); 
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $role = $input['role'] ?? 'موظف عادي';

        if (!$name || !$email || !$password) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'All fields are required']);
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Email already exists']);
            exit;
        }

        $this->userModel->create($name, $email, $password, $role);
        echo json_encode(['status'=>'success','message'=>'User created successfully']);
        exit;
    }

   
    public function update($id) {
        AuthController::requireAdmin(); 
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $data = [
            'name' => $input['name'] ?? null,
            'email' => $input['email'] ?? null,
            'password' => $input['password'] ?? null,
            'role' => $input['role'] ?? null
        ];

        $this->userModel->update($id, $data);
        echo json_encode(['status'=>'success','message'=>'User updated successfully']);
        exit;
    }

   
    public function destroy($id) {
        AuthController::requireAdmin(); 
        header('Content-Type: application/json');

        $deleted = $this->userModel->delete($id);
        if ($deleted) {
            echo json_encode(['status'=>'success','message'=>'User deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Failed to delete user']);
        }
        exit;
    }
}
