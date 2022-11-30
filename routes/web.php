<?php

return [
    'register' => [
        'controller' => 'RegisterController',
        'action' => 'showRegistrationForm',
        'middleware' => [],
        'http_method' => 'GET'
    ],
    'signup' => [
        'controller' => 'RegisterController',
        'action' => 'register',
        'middleware' => [],
        'http_method' => 'POST'
    ],
    'login' => [
        'controller' => 'AuthController',
        'action' => 'showLoginForm',
        'middleware' => [],
        'http_method' => 'GET'
    ],
    'signin' => [
        'controller' => 'AuthController',
        'action' => 'login',
        'middleware' => [],
        'http_method' => 'POST'
    ],
    'logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
        'middleware' => ['Authenticate'],
        'http_method' => 'GET'
    ]
];