<?php
class HomeController extends BaseController {
    public function index() {
        $this->view('home', ['title' => 'Welcome to Service Exchange']);
    }
}

