<?php
use App\Core\Router;

use App\Controllers\MessageController;

 $router = new Router();

$router->get('/messages', [MessageController::class, 'index']);
$router->get('/messages/unread', [MessageController::class, 'unread']);
$router->post('/message', [MessageController::class, 'store']);
$router->patch('/messages/{id}', [MessageController::class, 'markAsRead']);
$router->patch('/messages/read-all', [MessageController::class, 'markAllAsRead']);











