<?php

require_once BASE . 'app/controllers/Controller.php';
require_once BASE . 'app/models/User.php';
require_once BASE . 'app/models/Profile.php';
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

    private function authenticate($user, $profile)
    {
        $_SESSION['auth'] = [
            'username' => $user['username'],
            'profile' => $profile,
        ];
        $this->redirect('/play');
    }

    public function signIn()
    {
        // Get the submitted data
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

        // Get the user profile
        $Profile = new Profile();
        $userProfile = $Profile->find($currentUser['profile_id']);

        // Authenticate the user
        $this->authenticate($currentUser, $userProfile);
    }

    private function createProfile()
    {
        // Create a profile instance
        $Profile = new Profile();

        // Prepare all the attributes to generate the id
        //--Define the function to get the characters
        $characters = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y', 'z'
        ];
        $charsAmount = count($characters);
        $getCharacters = function($value, $letters) use ($characters, $charsAmount) {
            // Subtract one from the value to bring it into range
            $value--;

            // Get the word
            $word = '';
            for ($i = $letters; $i > 0; $i--) {
                // Get the character index
                $base = $charsAmount ** ($i - 1);
                $division = (int) ($value / $base);

                // Add the 'division' character to the word
                $word .= $characters[$division];

                // Update the value
                $value -= $division * $base;
            }

            return $word;
        };

        //--Get the current date
        $datetime = explode(' ', date('Y-m-d H:i:s'));
        $date = array_map(
            function($el) {return (int)$el;},
            explode('-', $datetime[0])
        );
        $time = array_map(
            function($el) {return (int)$el;},
            explode(':', $datetime[1])
        );

        //--Get the amount of days of the current year
        $DateTime_now = new DateTime("$date[0]-$date[1]-$date[2]");
        $DateTime_start = new DateTime("$date[0]-01-01");
        $days = $DateTime_now->diff($DateTime_start)->days + 1;

        //--Get the amount of seconds of today
        $seconds = ($time[0] * 3600) + ($time[1] * 60) + $time[2];

        //--Refactor the current year based on 2025
        $date[0] -= 2024;


        // Generate the id based on the characters;
        $id = '';

        //--First two letters (based on the refactored year)
        $id .= $getCharacters($date[0], 2);
        //--Two letters for the amount of days
        $id .= $getCharacters($days, 2);
        //--Three letters for the amount of seconds
        $id .= $getCharacters($seconds, 3);
        //--Two random chars
        $id .= $characters[rand(0, $charsAmount - 1)];
        $id .= $characters[rand(0, $charsAmount - 1)];
        //--Revert the id
        $id = strrev($id);

        // Get the rest of the profile data
        $avatar = 'anonymous';
        $name = "User-$id";

        // Create the profile
        $profile = $Profile->create([
            'id' => $id,
            'avatar' => $avatar,
            'name' => $name
        ]);

        return $profile;
    }

    public function signUp()
    {
        // Validate the submitted data
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

        // Get the data and create a User instance
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

        // Create the new user profile
        $profileCreated = $this->createProfile();

        // Create the new user
        $userCreated = $User->create([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'profile_id' => $profileCreated['id']
        ]);

        // Authenticate the user
        $this->authenticate($userCreated, $profileCreated);
    }
}
