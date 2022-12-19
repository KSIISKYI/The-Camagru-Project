<?php

namespace App\Core;

class Route
{
    public $route;
    public $controller;
    public $action;
    public $http_method;
    public $middleware;
    public $name;

    function __construct(string $route, string $controller, string $action, string $http_method, string $name = null, array $middleware = [])
    {
        $this->route = $route;
        $this->controller = $controller;
        $this->action = $action;
        $this->http_method = $http_method;
        $this->middleware = $middleware;
        $this->name = $name ? $name : $route;
    }
}
