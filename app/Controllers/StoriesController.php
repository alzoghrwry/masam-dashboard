<?php
namespace App\Controllers;

use App\Models\Story;
use App\Core\ActivityLogger;

class StoriesController {
    use ActivityLogger; 

    private Story $storyModel;

    public function __construct() {
        $this->storyModel = new Story();
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->initLogger(); 
    }

    public function index() {
        $stories = $this->storyModel->all();
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                   . "://{$_SERVER['HTTP_HOST']}";

        foreach ($stories as &$story) {
            if (!empty($story['image'])) {
                $story['image'] = $baseUrl . $story['image'];
            }
        }

        $this->logActivity("عرض جميع قصص النجاح", "Count: " . count($stories));
        echo json_encode($stories);
    }

    public function show(int $id): void {
        $story = $this->storyModel->find($id);

        if (!$story) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Story not found']);
            return;
        }

        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                   . "://{$_SERVER['HTTP_HOST']}";
        if (!empty($story['image'])) {
            $story['image'] = $baseUrl . $story['image'];
        }

        $this->logActivity("عرض قصة", "ID: $id, Title: {$story['title']}");
        echo json_encode(['status' => 'success', 'data' => $story]);
    }

    public function store() {
        $title = $_POST['title'] ?? '';
        $date = $_POST['date'] ?? '';
        $content = $_POST['content'] ?? '';
        $imagePath = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = '/uploads/' . $filename; 
            }
        }

        if (!$title || !$date || !$content || !$imagePath) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'All fields are required']);
            return;
        }

        $this->storyModel->create([
            'title' => $title,
            'date' => $date,
            'content' => $content,
            'image' => $imagePath
        ]);

        $this->logActivity("إنشاء قصة جديدة", "Title: $title, Date: $date");
        echo json_encode(['status'=>'success','message'=>'Story created successfully']);
    }

    public function update(int $id): void {
        $title   = $_POST['title'] ?? '';
        $date    = $_POST['date'] ?? '';
        $content = $_POST['content'] ?? '';

        $story = $this->storyModel->find($id);
        if (!$story) {
            http_response_code(404);
            echo json_encode(['status'=>'error','message'=>'Story not found']);
            return;
        }

        $imagePath = $story['image'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename   = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = '/uploads/' . $filename;

                if (!empty($story['image'])) {
                    $oldFile = __DIR__ . '/../../public' . $story['image'] ?? '';
                    if (file_exists($oldFile)) unlink($oldFile);
                }
            }
        }

        $this->storyModel->update($id, [
            'title'   => $title,
            'date'    => $date,
            'content' => $content,
            'image'   => $imagePath,
        ]);

        $this->logActivity("تحديث قصة", "ID: $id, Title: $title");
        echo json_encode(['status' => 'success', 'message' => 'Story updated successfully']);
    }

    public function delete($id) {
        $this->storyModel->delete($id);
        $this->logActivity("حذف قصة", "ID: $id");
        echo json_encode(['status'=>'success','message'=>'Story deleted successfully']);
    }
}
