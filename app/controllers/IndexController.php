<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/models/Profile.php';
require_once BASE . 'app/models/Friendship.php';
require_once BASE . 'app/models/Achievement.php';
require_once BASE . 'app/models/AchievementProfile.php';
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
        $Profile = new Profile();
        $id = $this->parameters['id'];
        $currentProfile = $Profile->find($id);

        if (!$currentProfile) return $this->notFound();

        // Get all the achievements
        $Achievement = new Achievement();
        $allAchievements = $Achievement->all();

        // Get the unlocked achivements of the current profile
        $AchievementProfile = new AchievementProfile();
        $unlocked = array_map(
            function($a) {
                return $a['achievement_id'];
            }, 
            $AchievementProfile->where(
                'profile_id', $currentProfile['id']
            )->all()
        );

        // Get the option to show ('edit profile', 'add fried', etc)
        $option = 0;  // The user is not auth
        if (isset($_SESSION['auth'])) {
            // Check if the current profile belongs to the auth user
            if ($_SESSION['auth']['profile']['id'] == $currentProfile['id']) {
                $option = 1;  // The own profile
            } else {
                //  Check if a friendship record exists in the database
                $profiles = ($_SESSION['auth']['profile']['id'] < $currentProfile['id'])
                    ? [$_SESSION['auth']['profile']['id'], $currentProfile['id']]
                    : [$currentProfile['id'], $_SESSION['auth']['profile']['id']];

                // Get the current friendship
                $Friendship = new Friendship();
                $curFriendship = $Friendship->whereConditions([
                    ['profile_id_1', $profiles[0]],
                    ['profile_id_2', $profiles[1]]
                ])->first();

                $option = 2;  // There is no friendship

                if ($curFriendship) {
                    // Check the status of the friendship
                    if ($curFriendship['status'] == FriendshipsStatusEnum::PENDING->value) {
                        $option = 3;  // Friendship with 'pending' status
                    } else {
                        $option = 4;  // Friendship with 'accepted' status
                    }
                }
            }
        }

        return $this->view('profile', [
            'profile' => $currentProfile,
            'option' => $option,
            'achievements' => $allAchievements,
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
        $User = new User();
        $userAuth = $User->find($_SESSION['auth']['username']);
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
        $User = new User();
        $authUser = $this->getAuthUser();

        // Validate the current password
        $currentPassword = $this->request['current_password'];
        if (!password_verify($currentPassword, $authUser['password'])) {
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
        $newPassword = $this->request['new_password'];
        $updatedUser = $User->update(
            $authUser['username'],
            ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]
        );

        // Update the session values
        $_SESSION['auth']['updated_at'] = $updatedUser['updated_at'];

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
        $User = new User();
        $authUser = $this->getAuthUser();

        // Validate the request password
        $confirmation_password = $this->request['confirmation_password'];
        if (!password_verify($confirmation_password, $authUser['password'])) {
            $this->redirect(
                '/settings?modal=true',
                ['confirmation_password' => GeneralErrorsEnum::CURRENT_PASSWORD_INCORRECT->errorMessage()]
            );
        }

        // Delete the auth user
        $User->delete($authUser['username']);

        // Delete the profile associated profile
        $Profile = new Profile();
        $Profile->delete($authUser['profile_id']);

        // Redirect back with the corresponding alert
        unset($_SESSION['auth']);
        $this->redirectWithAlert(
            '/settings',
            'success',
            AlertMessagesEnum::ACCOUNT_DELETED->message(),
        );
    }
}
