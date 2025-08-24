<?php
use App\Core\Router;
use App\Controllers\UserController;
 $router = new Router();


// Users
$router->get('/api/users', [UserController::class,'index']);
$router->post('/api/users', [UserController::class,'store']);
$router->put('/api/users/{id}', [UserController::class,'update']);
$router->delete('/api/users/{id}', [UserController::class,'destroy']);










