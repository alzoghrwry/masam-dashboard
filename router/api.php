<?php
use App\Core\Router;

use App\Controllers\NewsController;
 $router = new Router();

$router->get('/news', [NewsController::class, 'index']);        
$router->post('/news', [NewsController::class, 'store']);       
$router->post('/news/update/{id}', [NewsController::class, 'update']);  
$router->delete('/news/{id}', [NewsController::class, 'delete']); 









