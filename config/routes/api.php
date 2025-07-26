<?php

//--API Controllers
require_once BASE . 'app/api/LanguageApi.php';
require_once BASE . 'app/api/FriendshipsApi.php';
require_once BASE . 'app/api/QuickGameApi.php';

Router::POST('/api/change-language', [LanguageApi::class, 'change_language']);
Router::GET('/api/friendship/request', [FriendshipsApi::class, 'request']);
Router::GET('/api/quickgame/match', [QuickGameApi::class, 'match']);
