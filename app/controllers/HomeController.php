<?php
class HomeController extends BaseController {
    public function index() {
        AuthMiddleware::requireAuth();
        $this->view('home/index', ['title' => 'Welcome to Service Exchange']);
    }
}
