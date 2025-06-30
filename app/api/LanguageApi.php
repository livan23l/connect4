<?php

require_once BASE . 'app/controllers/Controller.php';

class LanguageApi extends Controller
{
    public function change_language()
    {
        $lang = $this->request['language'];

        // Verify that the language is valid
        if (!in_array($lang, LANGUAGES)) $this->response(400);

        $_SESSION['language'] = $this->request['language'];
        $this->response(204);
    }
}
