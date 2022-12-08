<?php

require_once '../vendor/autoload.php';
require_once '../config/config.php';

use App\Core\{Router, Request};

session_start();

$router = new Router(new Request);
$router->run();
