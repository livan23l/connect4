<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/models/Profile.php';
require_once BASE . 'app/enums/GeneralErrorsEnum.php';
require_once BASE . 'app/enums/AlertMessagesEnum.php';

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

    public function changePassword()
    {
        // Check if the user is auth
        if (!isset($_SESSION['auth'])) $this->redirectBack();

        // Get the auth user
        $User = new User();
        $userAuth = $User->find($_SESSION['auth']['username']);
        if (!$userAuth) $this->redirectBack();

        // Validate the current password
        $currentPassword = $this->request['current_password'];
        if (!password_verify($currentPassword, $userAuth['password'])) {
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
        $User->update(
            $userAuth['username'],
            ['password' => password_hash($newPassword, PASSWORD_BCRYPT),]
        );

        // Redirect with the corresponding alert
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => AlertMessagesEnum::PASSWORD_UPDATED->message()
        ];
        $this->redirect('/settings', [], []);
    }

    public function closeSession()
    {
        unset($_SESSION['auth']);
        $this->redirectBack();
    }

    public function deleteAccount()
    {
        if (!isset($_SESSION['auth'])) $this->redirectBack();

        // Check the user exists
        $User = new User();
        $userDeleted = $User->delete($_SESSION['auth']['username']);

        if (!$userDeleted) $this->redirectBack();

        // Delete the profile associated profile
        $Profile = new Profile();
        $Profile->delete($userDeleted['profile_id']);

        $this->closeSession();
    }

    public function notFound()
    {
        http_response_code(404);
        return $this->view('_404');
    }
}
