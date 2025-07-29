<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/models/Profile.php';
require_once BASE . 'app/models/Achievement.php';
require_once BASE . 'app/models/Friendship.php';
require_once BASE . 'app/enums/GeneralErrorsEnum.php';
require_once BASE . 'app/enums/AlertMessagesEnum.php';
require_once BASE . 'app/enums/FriendshipsStatusEnum.php';

class IndexController extends Controller
{
    public function notFound()
    {
        http_response_code(404);
        return $this->view('_404');
    }

    public function index()
    {
        return $this->view('home');
    }

    public function settings()
    {
        return $this->view('settings');
    }

    public function profile()
    {
        // Check the profile exists
        $id = $this->parameters['id'];
        $profile = Profile::find($id);
        if (!$profile) return $this->notFound();

        // Get all the achievements
        $achievementsAll = Achievement::all();

        // Get the unlocked achivements indexes of the current profile
        $achievementsUnlock = $profile->unlockedAchievements();
        $unlocked = array_map(
            function ($a) {
                return $a['achievement_id'];
            },
            $achievementsUnlock
        );

        // Get the option to show ('edit profile', 'add fried', etc)
        $option = 0;  // The user is not auth
        if (isset($_SESSION['auth'])) {
            // Check if the current profile belongs to the auth user
            if ($_SESSION['auth']['profile']['id'] == $profile->id) {
                $option = 1;  // The own profile
            } else {
                // Check if a friendship record exists in the database
                $profiles = ($_SESSION['auth']['profile']['id'] < $profile->id)
                    ? [$_SESSION['auth']['profile']['id'], $profile->id]
                    : [$profile->id, $_SESSION['auth']['profile']['id']];

                // Get the current friendship
                $friendship = Friendship::whereConditions([
                    ['profile_id_1', $profiles[0]],
                    ['profile_id_2', $profiles[1]]
                ])->first();

                $option = 2;  // There is no friendship
                if ($friendship) {
                    // Check the status of the friendship
                    if ($friendship->status == FriendshipsStatusEnum::PENDING->value) {
                        $option = 3;  // Friendship with 'pending' status
                    } else {
                        $option = 4;  // Friendship with 'accepted' status
                    }
                }
            }
        }

        // Set the profile array
        $profileArray = $profile->toArray();
        $profileArray['avatar_name'] = $profile->avatar()->name;

        return $this->view('profile', [
            'profile' => $profileArray,
            'option' => $option,
            'achievements' => $achievementsAll,
            'unlockedAchievements' => $unlocked,
        ]);
    }

    private function redirectWithAlert($route, $alert_type, $alert_message)
    {
        $_SESSION['alert'] = [
            'type' => $alert_type,
            'message' => $alert_message,
        ];
        $this->redirect($route, [], []);
    }

    public function closeSession()
    {
        unset($_SESSION['auth']);
        $this->redirectWithAlert(
            '/settings',
            'success',
            AlertMessagesEnum::SESSION_CLOSED->message(),
        );
    }

    private function getAuthUser()
    {
        // Check if the user is auth
        if (!isset($_SESSION['auth'])) {
            $this->redirectWithAlert(
                '/settings',
                'danger',
                AlertMessagesEnum::UNKNOWN_ERRROR->message(),
            );
        }

        // Get the auth user
        $userAuth = User::find($_SESSION['auth']['username']);
        if (!$userAuth) {
            $this->redirectWithAlert(
                '/settings',
                'danger',
                AlertMessagesEnum::UNKNOWN_ERRROR->message(),
            );
        }

        return $userAuth;
    }

    public function changePassword()
    {
        // Get the auth user
        $user = $this->getAuthUser();

        // Validate the current password
        $currentPassword = $this->request['current_password'];
        if (!password_verify($currentPassword, $user->password)) {
            $this->redirect(
                '/settings',
                ['current_password' => GeneralErrorsEnum::CURRENT_PASSWORD_INCORRECT->errorMessage()]
            );
        }

        // Validate the new password
        $validation = $this->validate([
            'new_password' => PASSWORD_VALIDATIONS,
        ]);
        if (!$validation) $this->redirect('/settings');

        // Change the password
        $user->password = password_hash(
            $this->request['new_password'],
            PASSWORD_BCRYPT
        );
        $user->save();

        // Update the session values
        $_SESSION['auth']['updated_at'] = $user->updated_at;

        // Redirect with the corresponding alert
        $this->redirectWithAlert(
            '/settings',
            'success',
            AlertMessagesEnum::PASSWORD_UPDATED->message(),
        );
    }

    public function deleteAccount()
    {
        // Get the auth user
        $user = $this->getAuthUser();

        // Validate the request password
        $confirmation_password = $this->request['confirmation_password'];
        if (!password_verify($confirmation_password, $user->password)) {
            $this->redirect(
                '/settings?modal=true',
                ['confirmation_password' => GeneralErrorsEnum::CURRENT_PASSWORD_INCORRECT->errorMessage()]
            );
        }

        // Destroy the user and delete the associated profile
        $user->destroy();
        Profile::delete($user->profile_id);

        // Redirect back with the corresponding alert
        unset($_SESSION['auth']);
        $this->redirectWithAlert(
            '/settings',
            'success',
            AlertMessagesEnum::ACCOUNT_DELETED->message(),
        );
    }
}
