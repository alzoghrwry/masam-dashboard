<?php
use App\Core\Router;

use App\Controllers\NewsController;
use App\Controllers\StoriesController;

 $router = new Router();

$router->get('/stories', [StoriesController::class, 'index']);
$router->post('/create', [StoriesController::class, 'store']);
$router->post('/update/{id}', [StoriesController::class, 'update']);

$router->get('/news', [NewsController::class, 'index']);        
$router->post('/news', [NewsController::class, 'store']);       
$router->post('/news/update/{id}', [NewsController::class, 'update']);  
$router->delete('/news/{id}', [NewsController::class, 'delete']); 










