<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/models/Profile.php';
require_once BASE . 'app/enums/GeneralErrorsEnum.php';

class AccessController extends Controller
{
    private function withPrefix($array, $prefix)
    {
        return array_combine(
            array_map(fn($k) => $prefix . $k, array_keys($array)),
            array_values($array)
        );
    }

    public function access()
    {
        // Check if the username is authenticated and redirect him back
        if (isset($_SESSION['auth'])) $this->redirect('/play');

        // Otherwise show the access view
        return $this->view('access');
    }

    private function authenticate($user, $profile)
    {
        // Get the profile array
        $profileArray = $profile->toArray();
        $profileArray['avatar_name'] = $profile->avatar()->name;

        // Authenticate
        $_SESSION['auth'] = [
            'username' => $user->username,
            'updated_at' => $user->updated_at,
            'profile' => $profileArray,
        ];

        // Redirect
        $this->redirect('/play', [], []);
    }

    public function signIn()
    {
        // Get the submitted data
        $username = $this->request['username'];
        $password = $this->request['password'];

        // Validate the user and the password
        $user = User::find($username);
        if (!$user || !password_verify($password, $user->password)) {
            // Redirect back with errors
            $this->redirect(
                '/access?section=signin&animation=false',
                ['signin' => GeneralErrorsEnum::USER_OR_PASSWORD_INCORRECT->errorMessage()],
                $this->withPrefix($this->request, 'signin_')
            );
        }

        // Authenticate the user
        $this->authenticate($user, $user->profile());
    }

    public function signUp()
    {
        // Validate the submitted data
        $validation = $this->validate([
            'username' => 'required|str|minlen:5|maxlen:50',
            'password' => PASSWORD_VALIDATIONS,
        ]);

        // If the credentials are not valid we redirect back
        if (!$validation) {
            $this->redirect(
                '/access?section=signup&animation=false',
                $this->withPrefix($this->errors, 'signup_'),
                $this->withPrefix($this->request, 'signup_')
            );
        }

        // Check if the user already exists
        if (User::find($this->request['username'])) {
            // Redirect back with errors
            $this->redirect(
                '/access?section=signup&animation=false',
                ['signup' => GeneralErrorsEnum::USER_ALREADY_REGISTERED->errorMessage()],
                $this->withPrefix($this->request, 'signup_')
            );
        }

        // Create the new user profile
        $profile = Profile::createProfile();

        // Get the data and create a new user
        $user = new User;
        $user->username = $this->request['username'];
        $user->password = password_hash($this->request['password'], PASSWORD_BCRYPT);
        $user->profile_id = $profile->id;
        $user->save();

        // Authenticate the user
        $this->authenticate($user, $profile);
    }
}
