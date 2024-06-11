<?php
class AboutController extends BaseController {
    public function index() {
        $this->view('about/index', ['title' => 'About Us']);
    }

    public function team() {
        $this->view('about/team', ['title' => 'Our Team']);
    }
}
