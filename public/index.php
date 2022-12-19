<?php

require_once '../vendor/autoload.php';
require_once '../config/config.php';
require_once APP_ROOT . '/app/Core/funcs.php';

use App\Core\{Router, Request};

session_start();

$router = new Router(new Request);
$router->run();
