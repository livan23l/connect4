<?php

// Require the config file to get the configuration and global constants
require_once __DIR__ . '/../config/config.php';

// Require the web routes
require_once BASE . 'config/routes/web.php';

// Unset the 'error' and 'old' session variables
unset($_SESSION['error']);
unset($_SESSION['old']);