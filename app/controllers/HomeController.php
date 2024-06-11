<?php
class HomeController extends BaseController {
    public function index() {
        $this->view('home/index', ['title' => 'Welcome to Service Exchange']);
    }
}
