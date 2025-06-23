<?php
//--Router class
require_once BASE . 'config/routes/Router.php';
//--Controllers
require_once BASE . 'app/controllers/IndexController.php';

// Routes
Router::GET('/', [IndexController::class, 'index']);

// Dispatch the current route
Router::dispatch();