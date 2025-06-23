<?php

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