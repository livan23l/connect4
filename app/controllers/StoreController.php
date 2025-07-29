<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/Profile.php';
require_once BASE . 'app/models/Avatar.php';
require_once BASE . 'app/models/Banner.php';

class StoreController extends Controller
{
    public function lobby()
    {
        if (!isset($_SESSION['auth'])) return $this->view('store.store');

        $profile = Profile::find($_SESSION['auth']['profile']['id']);
        return $this->view('store.store', [
            'avatars' => Avatar::where('price', 0, '>')->get(),
            'banners' => Banner::where('price', 0, '>')->get(),
            'current_banner' => $profile->banner(),
            'current_avatar_name' => $_SESSION['auth']['profile']['avatar_name'],
        ]);
    }
}
