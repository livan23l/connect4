<?php

require_once BASE . 'app/controllers/IndexController.php';

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

    private static function execute($action, $parameters = [])
    {
        // Get the controller and method from the action
        [$controller, $method] = $action;

        // Create a new controller instance
        $request = $_POST;
        $cont = new $controller($parameters, $request);

        // Execute the method of the instance
        echo $cont->$method();
    }

    public static function dispatch()
    {
        // Get the request information
        $URI = trim($_SERVER['REQUEST_URI'], '/');
        $METHOD = $_SERVER['REQUEST_METHOD'];

        // Ignore query parameters
        $query_pos = strpos($URI, '?');
        if ($query_pos !== false) {
            $URI = substr($URI, 0, $query_pos);
        }

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

        self::execute([IndexController::class, 'notFound']);
        return;
    }
}
