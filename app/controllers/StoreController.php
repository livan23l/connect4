<?php

require_once BASE . 'app/controllers/Controller.php';

class StoreController extends Controller
{
    public function lobby()
    {
        return $this->view('store.lobby');
    }
}