<?php
namespace App\Controllers;

use App\Models\Message;
use PDO;

class MessageController {
    private Message $messageModel;

    public function __construct() {
        $this->messageModel = new Message();
       
    }

    
    public function index() {
        echo json_encode($this->messageModel->all());
    }

    public function unread() {
        echo json_encode($this->messageModel->unread());
    }

   
    public function markAsRead($id) {
        $success = $this->messageModel->markAsRead((int)$id);
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Message marked as read' : 'Failed to update message'
        ]);
    }

   
    public function markAllAsRead() {
        $success = $this->messageModel->markAllAsRead();
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'All messages marked as read' : 'Failed to update messages'
        ]);
    }

   public function store() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        return;
    }

    $id = $this->messageModel->create($data);

    echo json_encode([
        'status' => 'success',
        'message' => 'Message sent successfully',
        'id' => $id
    ]);
}
}
