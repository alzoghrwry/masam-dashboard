<?php
use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;

 $router = new Router();
$router->get('/', [AuthController::class, 'index']);

$router->post('/api/login', [AuthController::class,'login']);
$router->post('/api/logout', [AuthController::class,'logout']);

// Users
$router->get('/api/users', [UserController::class,'index']);
$router->post('/api/users', [UserController::class,'store']);
$router->put('/api/users/{id}', [UserController::class,'update']);
$router->delete('/api/users/{id}', [UserController::class,'destroy']);









