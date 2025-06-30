<?php

//--API Controllers
require_once BASE . 'app/api/LanguageApi.php';

Router::POST('/api/change-language', [LanguageApi::class, 'change_language']);
