<?php

function checkPassword($password, $confirm_password)
{
    $errors = [];
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (strlen($password) < 8 or strlen($confirm_password) < 8) {
        $errors[] = 'Password must contain at least 8 characters';
    }

    if (!preg_match('#[0-9]#', $password)) {
        $errors[] = 'Password must contain at least one digit';
    }

    if (!preg_match('#[а-я a-z]#', $password)) {
        $errors[] = 'Password must contain at least one letter';
    }
    
    return $errors;
}

function route($arr)
{
    $routes = require APP_ROOT . '/routes/web.php';
    $url_prefix = 'http://' .$_SERVER['HTTP_HOST'] . URL_ROOT;

    $route_pattern = null;

    foreach($routes as $route) {
        if (isset($route->name) && $route->name === $arr['name']) {
            $route_pattern = $route->route;
        }
    }

    if (!isset($route_pattern)) {
        return '#';
    } else {
        unset($arr['name']);
        foreach($arr as $key => $value) {
            $route_pattern = str_replace("\/(?P<$key>[^\/]+)", "/$value", $route_pattern);
        }

        return $url_prefix . $route_pattern;
    }
}

function redirect(string $route)
{
    header("location: $route");
    exit();
}
