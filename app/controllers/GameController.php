<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/Profile.php';

class GameController extends Controller
{
    public function lobby()
    {
        return $this->view('game.lobby');
    }

    public function robot()
    {
        if (!isset($_SESSION['auth'])) return $this->view('game.robot');

        $profile = Profile::find($_SESSION['auth']['profile']['id']);
        return $this->view('game.robot', [
            'player_1_banner' => $profile->banner(),
        ]);
    }

    public function local()
    {
        return $this->view('game.local');
    }
}
