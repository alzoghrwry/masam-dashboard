<?php
namespace App\Controllers;

use App\Models\News;
use App\Core\ActivityLogger;

class NewsController
{
    use ActivityLogger;

    private News $newsModel;

    public function __construct()
    {
        $this->newsModel = new News();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->initLogger(); 
    }

 
    public function index(): void
    {
        $news = $this->newsModel->all();

        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                   . "://{$_SERVER['HTTP_HOST']}";

        foreach ($news as &$item) {
            if (!empty($item['image'])) {
                $item['image'] = $baseUrl . '/uploads/news/' . $item['image'];
            }
        }

        echo json_encode($news);
    }

   
    public function show(int $id): void
    {
        $news = $this->newsModel->find($id);

        if (!$news) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'news not found']);
            return;
        }

        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
                   . "://{$_SERVER['HTTP_HOST']}";

        if (!empty($news['image'])) {
            $news['image'] = $baseUrl . '/uploads/news/' . basename($news['image']);
        }

        echo json_encode(['status' => 'success', 'data' => $news]);
    }


    public function store(): void
    {
        $title   = $_POST['title'] ?? '';
        $date    = $_POST['date'] ?? '';
        $content = $_POST['content'] ?? '';
        $imagePath = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/news/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename   = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $filename;
            }
        }

        if (!$title || !$date || !$content) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'جميع الحقول مطلوبة'
            ]);
            return;
        }

        $this->newsModel->create([
            'title'   => $title,
            'date'    => $date,
            'content' => $content,
            'image'   => $imagePath
        ]);

       
        $this->logActivity('Create News', "Title: $title");

        echo json_encode([
            'status'  => 'success',
            'message' => 'تمت إضافة الخبر'
        ]);
    }

  
    public function update(int $id): void
    {
        $newsItem = $this->newsModel->find($id);

        if (!$newsItem) {
            http_response_code(404);
            echo json_encode(['status'=>'error','message'=>'الخبر غير موجود']);
            return;
        }

        $title   = $_POST['title'] ?? $newsItem['title'];
        $date    = $_POST['date'] ?? $newsItem['date'];
        $content = $_POST['content'] ?? $newsItem['content'];
        $imagePath = $newsItem['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/news/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename   = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
               
                if (!empty($imagePath)) {
                    $oldFile = $uploadDir . $imagePath;
                    if (file_exists($oldFile)) unlink($oldFile);
                }
                $imagePath = $filename;
            }
        }

        $this->newsModel->update($id, [
            'title'   => $title,
            'date'    => $date,
            'content' => $content,
            'image'   => $imagePath
        ]);

        $this->logActivity('Update News', "ID: $id, Title: $title");

        echo json_encode(['status'=>'success','message'=>'تم تحديث الخبر']);
    }

   
    public function delete(int $id): void
    {
        $newsItem = $this->newsModel->find($id);

        if ($newsItem && !empty($newsItem['image'])) {
            $file = __DIR__ . '/../../public/uploads/news/' . $newsItem['image'];
            if (file_exists($file)) unlink($file);
        }

        $this->newsModel->delete($id);

        
        $this->logActivity('Delete News', "ID: $id, Title: {$newsItem['title']}");

        echo json_encode(['status'=>'success','message'=>'تم حذف الخبر']);
    }
}
