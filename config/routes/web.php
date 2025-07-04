<?php
//--Router class
require_once BASE . 'config/routes/Router.php';
//--Controllers
require_once BASE . 'app/controllers/IndexController.php';

// Routes
Router::GET('/', [IndexController::class, 'index']);
Router::POST('/signin', [IndexController::class, 'signIn']);
Router::POST('/signup', [IndexController::class, 'signUp']);

// Include the api routes
require_once BASE . 'config/routes/api.php';

// Dispatch the current route
Router::dispatch();