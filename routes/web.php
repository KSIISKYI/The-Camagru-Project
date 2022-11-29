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
];