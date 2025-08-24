<?php
use App\Core\Router;

use App\Controllers\StoriesController;

 $router = new Router();

$router->get('/stories', [StoriesController::class, 'index']);
$router->post('/create', [StoriesController::class, 'store']);
$router->post('/update/{id}', [StoriesController::class, 'update']);










