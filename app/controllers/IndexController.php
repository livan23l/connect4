<?php

require_once BASE . "app/controllers/Controller.php";

class IndexController extends Controller
{
    public function index()
    {
        return $this->view('home');
    }

    public function notFound()
    {
        return $this->view('_404');
    }
}