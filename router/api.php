<?php
use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\MessageController;
use App\Controllers\StoriesController;
use App\Controllers\NewsController;
 $router = new Router();
$router->get('/', [AuthController::class, 'index']);

$router->post('/api/login', [AuthController::class,'login']);
$router->post('/api/logout', [AuthController::class,'logout']);


$router->get('/api/users', [UserController::class,'index']);
$router->post('/api/users', handler: [UserController::class,'store']);
$router->put('/api/users/{id}', [UserController::class,'update']);
$router->delete('/api/users/{id}', [UserController::class,'destroy']);
$router->get('/messages', [MessageController::class, 'index']);
$router->get('/messages/unread', [MessageController::class, 'unread']);
$router->post('/message', [MessageController::class, 'store']);
$router->patch('/messages/{id}', [MessageController::class, 'markAsRead']);
$router->patch('/messages/read-all', [MessageController::class, 'markAllAsRead']);
$router->get('/stories', [StoriesController::class, 'index']);
$router->get('/stories/{id}', [StoriesController::class, 'show']);
$router->post('/stories', [StoriesController::class, 'store']);
$router->post('/stories/update/{id}', [StoriesController::class, 'update']);
$router->delete('/stories/delete/{id}', [StoriesController::class, 'delete']);

$router->get('/news', [NewsController::class, 'index']);        
$router->get('/news/{id}', [NewsController::class, 'show']);        
$router->post('/news', [NewsController::class, 'store']);       
$router->post('/news/update/{id}', [NewsController::class, 'update']);  
$router->delete('/news/{id}', [NewsController::class, 'delete']); 









