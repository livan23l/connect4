<?php
//--Router class
require_once BASE . 'config/routes/Router.php';
//--Controllers
require_once BASE . 'app/controllers/IndexController.php';

// Routes
Router::GET('/', [IndexController::class, 'index']);
Router::POST('/signin', [IndexController::class, 'signIn']);
Router::POST('/signup', [IndexController::class, 'signUp']);

// Dispatch the current route
Router::dispatch();