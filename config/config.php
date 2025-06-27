<?php

// Start the session
session_start();

// Define the time zone
date_default_timezone_set('America/Mexico_City');

// Define global constants
//-General
define('BASE', str_replace('\\', '/', rtrim(__DIR__, 'config')));
define('HTML_BASE', 'http://connect4.test/');

//-Database
define('DB_MANAGER', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_DATABASE', 'connect4');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Define the global functions

/**
 * Returns the previously submitted value for a given form field.
 *
 * @param string $field The name of the form field to retrieve the old value for.
 * @return mixed|null The previously submitted value for the field, or null if not set.
 */
function old($field)
{
    return $_SESSION['old'][$field] ?? null;
}

/**
 * Retrieves the error message for a specific form field from the session.
 *
 * @param string $field The name of the form field to check for an error message.
 * @return string|null The error message for the specified field, or null if none exists.
 */

function error($field)
{
    return $_SESSION['error'][$field] ?? null;
}
