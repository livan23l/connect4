<?php

require_once BASE . "app/controllers/Controller.php";
require_once BASE . "app/models/User.php";

class IndexController extends Controller
{
    public function index()
    {
        return $this->view('home');
    }

    private function combine($array, $prefix)
    {
        return array_combine(
            array_map(fn($k) => $prefix . $k, array_keys($array)),
            array_values($array)
        );
    }

    public function signIn()
    {
        $user = new User();
        $validation = $this->validate([
            'user' => 'required|minlen:5|maxlen:50',
            'password' => 'required|minlen:8|w_number:1'
        ]);

        if (!$validation) {
            return $this->redirect(
                '/',
                $this->combine($this->errors, 'signin_'),
                $this->combine($this->request, 'signin_'),
            );
        } else {
            return $this->redirect('/success');
        }
    }

    public function signUp()
    {
        return $this->view('home');
    }

    public function notFound()
    {
        return $this->view('_404');
    }
}