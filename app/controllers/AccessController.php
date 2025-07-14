<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/enums/LoginErrorsEnum.php';

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

    private function authenticate($user)
    {
        $_SESSION['auth'] = [
            'username' => $user['username'],
        ];
        $this->redirect('/play');
    }

    public function signIn()
    {
        $username = $this->request['username'];
        $password = $this->request['password'];

        $User = new User();
        $currentUser = $User->find($username);

        // Validate the user and the password
        if (!$currentUser || !password_verify($password, $currentUser['password'])) {
            // Redirect back with errors
            $this->redirect(
                '/access?section=signin&animation=false',
                ['signin' => LoginErrorsEnum::USER_OR_PASSWORD_INCORRECT->errorMessage()],
                $this->withPrefix($this->request, 'signin_')
            );
        }

        // Authenticate the user
        $this->authenticate($currentUser);
    }

    public function signUp()
    {
        $validation = $this->validate([
            'username' => 'required|str|minlen:5|maxlen:50',
            'password' => 'required|minlen:8|w_number:1|confirmed',
        ]);

        // If the credentials are not valid we redirect back
        if (!$validation) {
            $this->redirect(
                '/access?section=signup&animation=false',
                $this->withPrefix($this->errors, 'signup_'),
                $this->withPrefix($this->request, 'signup_')
            );
        }

        $User = new User();
        $username = $this->request['username'];
        $password = $this->request['password'];

        // Check if the user exists
        if ($User->find($username)) {
            // Redirect back with errors
            $this->redirect(
                '/access?section=signup&animation=false',
                ['signup' => LoginErrorsEnum::USER_ALREADY_REGISTERED->errorMessage()],
                $this->withPrefix($this->request, 'signup_')
            );
        }

        // Create the new user
        $userCreated = $User->create([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        // Authenticate the user
        $this->authenticate($userCreated);
    }
}
