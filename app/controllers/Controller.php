<?php

class Controller
{
    protected array $parameters;

    protected function view($view, $variables = [])
    {
        // Extract all the variables
        extract($variables);

        // Get and save the view
        ob_start();
        $view = str_replace(".", "/", $view);
        require_once BASE . "resources/views/$view.php";
        $file = ob_get_clean();  // Get the buffer data and close it

        // Return the view file
        return $file;
    }

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }
}