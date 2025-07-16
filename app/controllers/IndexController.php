<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/enums/LoginErrorsEnum.php';

class IndexController extends Controller
{
    public function index()
    {
        return $this->view('home');
    }

    public function settings()
    {
        return $this->view('settings');
    }

    public function notFound()
    {
        http_response_code(404);
        return $this->view('_404');
    }
}
