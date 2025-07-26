<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/Friendship.php';
require_once BASE . 'app/enums/FriendshipsStatusEnum.php';

class FriendshipsApi extends Controller
{
    public function request()
    {
        $Friendship = new Friendship();

        // Get the auth user profile
        if (!isset($_SESSION['auth'])) $this->response(400);
        $authProfileId = $_SESSION['auth']['profile']['id'];
        $requestProfileId = $this->request['profile_id'];

        // Check both ids are differents
        if ($authProfileId == $requestProfileId) $this->request(400);

        // Add the current friendship to the DB
        $profiles = ($authProfileId < $requestProfileId)
            ? [$authProfileId, $requestProfileId]
            : [$requestProfileId, $authProfileId];

        $Friendship->create([
            'profile_id_1' => $profiles[0],
            'profile_id_2' => $profiles[1],
        ]);
        $this->response(204);
    }
}
