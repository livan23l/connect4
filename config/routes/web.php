<?php
//--Router class
require_once BASE . 'config/routes/Router.php';
//--Controllers
require_once BASE . 'app/controllers/IndexController.php';
require_once BASE . 'app/controllers/GameController.php';
require_once BASE . 'app/controllers/StoreController.php';

// Routes
Router::GET('/', [IndexController::class, 'index']);
Router::POST('/signin', [IndexController::class, 'signIn']);
Router::POST('/signup', [IndexController::class, 'signUp']);

Router::GET('/play', [GameController::class, 'lobby']);
Router::GET('/play/offline/robot', [GameController::class, 'robot']);
Router::GET('/play/offline/local', [GameController::class, 'local']);

Router::GET('/store', [StoreController::class, 'lobby']);

// Include the api routes
require_once BASE . 'config/routes/api.php';

// Dispatch the current route
Router::dispatch();