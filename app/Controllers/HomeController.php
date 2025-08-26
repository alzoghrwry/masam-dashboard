<?php
namespace App\Controllers;


use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        $this->view('home', ['title' => 'Welcome to Home']);
    }

    public function notFound() {
        $this->view('404', ['title' => 'Page Not Found']);
    }
}
