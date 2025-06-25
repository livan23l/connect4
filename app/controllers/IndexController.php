<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/enums/LoginErrorsEnum.php';

class IndexController extends Controller
{
    public function index()
    {
        return $this->view('home');
    }

    private function withPrefix($array, $prefix)
    {
        return array_combine(
            array_map(fn($k) => $prefix . $k, array_keys($array)),
            array_values($array)
        );
    }

    public function signIn()
    {
        $id = $this->request['user'];
        $password = $this->request['password'];

        $user = new User();
        $currentUser = $user->find($id);

        // Validate the user and the password
        if (!$currentUser || !password_verify($password, $currentUser['password'])) {
            // Redirect back with errors
            $this->redirect(
                '/',
                ['signin' => LoginErrorsEnum::USER_OR_PASSWORD_INCORRECT->errorMessage()],
                $this->withPrefix($this->request, 'signin_')
            );
        }

        $this->redirect('/success');
    }

    public function signUp()
    {
        $validation = $this->validate([
            'user' => 'required|str|minlen:5|maxlen:50',
            'password' => 'required|minlen:8|w_number:1|confirmed',
        ]);

        // If the credentials are not valid we redirect back
        if (!$validation) {
            $this->redirect(
                '/',
                $this->withPrefix($this->errors, 'signup_'),
                $this->withPrefix($this->request, 'signup_')
            );
        }

        $user = new User();
        $username = $this->request['user'];
        $password = $this->request['password'];

        // Check if the user exists
        if ($user->find($username)) {
            // Redirect back with errors
            $this->redirect(
                '/',
                ['signup' => LoginErrorsEnum::USER_ALREADY_REGISTERED->errorMessage()],
                $this->withPrefix($this->request, 'signup_')
            );
        }

        // Create the new user
        $user->create([
            'user' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        $this->redirect('/success');
    }

    public function notFound()
    {
        http_response_code(404);
        return $this->view('_404');
    }
}
