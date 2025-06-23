<?php

require_once BASE . "app/controllers/IndexController.php";

class Router
{
    private static $GET = [];
    private static $POST = [];

    public static function GET($uri, $action)
    {
        self::$GET[trim($uri, '/')] = $action;
    }

    public static function POST($uri, $action)
    {
        self::$POST[trim($uri, '/')] = $action;
    }

    public static function show()
    {
        var_dump(self::$GET);
        var_dump(self::$POST);
    }

    private static function execute($action, $vars = [])
    {
        // Get the controller and method from the action
        [$controller, $method] = $action;

        $cont = new $controller($vars);  // Create a new controller instance
        echo $cont->$method();  // Execute the instance
    }

    public static function dispatch()
    {
        // Get the request information
        $URI = trim($_SERVER['REQUEST_URI'], '/');
        $METHOD = $_SERVER['REQUEST_METHOD'];

        // Regular expresion for route variables
        $pattern = '/(:[a-zA-Z0-9_]+)/';  // ':' followed by letters or numbers

        // Check if the URI is saved in the current method
        foreach (self::$$METHOD as $uri => $action) {
            // Check if the current saved uri has variables
            if (preg_match_all($pattern, $uri, $matches)) {
                $new_uri = preg_replace($pattern, '([a-zA-Z0-9\-]+)', $uri);
                $uri_pattern = '#^' . $new_uri . '$#';

                if (preg_match($uri_pattern, $URI, $values)) {
                    // Get the URI variables
                    $vars = [];

                    foreach($matches[1] as $idx => $name) {
                        $vars[ltrim($name, ':')] = $values[$idx + 1];
                    }

                    // Execute the action
                    self::execute($action, $vars);
                    return;
                }
            } else {
                // Without variables it's a direct comparison
                if ($uri == $URI) {
                    self::execute($action);
                    return;
                }
            }
        }

        self::execute([IndexController::class, "notFound"]);
        return;
    }
}
