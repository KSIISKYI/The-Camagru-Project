<?php

return [
    'register' => [
        'controller' => 'RegisterController',
        'action' => 'showRegistrationForm',
        'middleware' => [],
        'http_method' => 'GET',
        'name' => 'register'
    ],
    'signup' => [
        'controller' => 'RegisterController',
        'action' => 'register',
        'middleware' => [],
        'http_method' => 'POST',
        'name' => 'signup'
    ],
    'login' => [
        'controller' => 'AuthController',
        'action' => 'showLoginForm',
        'middleware' => [],
        'http_method' => 'GET',
        'name' => 'login'
    ],
    'signin' => [
        'controller' => 'AuthController',
        'action' => 'login',
        'middleware' => [],
        'http_method' => 'POST',
        'name' => 'signin'
    ],
    'logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
        'middleware' => ['Authenticate'],
        'http_method' => 'GET',
        'name' => 'logout'
    ],
    'activate' => [
        'controller' => 'RegisterController',
        'action' => 'activate',
        'middleware' => [],
        'http_method' => 'POST',
        'name' => 'activate'
    ],
    'recovery_email' => [
        'controller' => 'UserController',
        'action' => 'showRecoveryFormMail',
        'middleware' => [],
        'http_method' => 'GET',
        'name' => 'recovery_email'
    ],
    'send_recovery_mail' => [
        'controller' => 'UserController',
        'action' => 'sendRecoveryMail',
        'middleware' => [],
        'http_method' => 'GET',
        'name' => 'send_recovery_mail'
    ],
    'recovery_form' => [
        'controller' => 'UserController',
        'action' => 'showRecoveryForm',
        'middleware' => [],
        'http_method' => 'POST',
        'name' => 'recovery_form'
    ],
    'reset_password' => [
        'controller' => 'UserController',
        'action' => 'resetPassword',
        'middleware' => [],
        'http_method' => 'POST',
        'name' => 'reset_password'
    ],
    'profile' => [
        'controller' => 'UserController',
        'action' => 'profile',
        'middleware' => ['Authenticate'],
        'http_method' => 'GET',
        'name' => 'profile',
    ],
    '' => [
        'controller' => 'HomeController',
        'action' => 'index',
        'middleware' => [],
        'http_method' => 'GET',
        'name' => 'home',
    ],
    'settings' => [
        'controller' => 'UserController',
        'action' => 'getSettingProfile',
        'middleware' => ['Authenticate'],
        'http_method' => 'GET',
        'name' => 'settings',  
    ],
    'update_settings' => [
        'controller' => 'UserController',
        'action' => 'updateSettingProfile',
        'middleware' => ['Authenticate'],
        'http_method' => 'POST',
        'name' => 'update_settings',  
    ],
];
