<?php

require_once BASE . 'app/controllers/Controller.php';

class GameController extends Controller
{
    public function lobby()
    {
        return $this->view('game.lobby');
    }

    public function robot()
    {
        return $this->view('game.robot');
    }

    public function local()
    {
        return $this->view('game.local');
    }
}
