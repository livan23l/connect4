<?php

require_once BASE . 'app/controllers/Controller.php';

class QuickGameApi extends Controller
{
    public function match()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        while (true) {
            echo "data: La hora es " . date('H:i:s');
            echo "\n\n";
            ob_flush();
            flush();
            sleep(1);
        }
    }
}
