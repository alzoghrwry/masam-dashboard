<?php
use App\Core\Router;

 se App\Controllers\NewsController;
use App\Controllers\StoriesController;

use App\Controllers\UserController;
 $router = new Router();

$router->get('/api/users', [UserController::class,'index']);
$router->post('/api/users', [UserController::class,'store']);
$router->put('/api/users/{id}', [UserController::class,'update']);
$router->delete('/api/users/{id}', [UserController::class,'destroy']);

$router->get('/stories', [StoriesController::class, 'index']);
$router->post('/create', [StoriesController::class, 'store']);
$router->post('/update/{id}', [StoriesController::class, 'update']);

$router->get('/news', [NewsController::class, 'index']);        
$router->post('/news', [NewsController::class, 'store']);       
$router->post('/news/update/{id}', [NewsController::class, 'update']);  
$router->delete('/news/{id}', [NewsController::class, 'delete']); 











