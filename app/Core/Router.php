<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $params = [];
    protected $request;
    protected $matches;

    function __construct($request)
    {
        $routes = require APP_ROOT.'/routes/web.php';

        foreach($routes as $route) {
            $this->routes = $routes;
            $this->request = $request;
        }
    }

    function match()
    {
        $url = $_REQUEST['route'];
        $matched_routes = [];

        foreach($this->routes as $route) {
            if (preg_match("#^$route->route$#", $url, $matches)) {
                $matched_routes[] = $route;
                $this->matches = $matches;
            }
        }
        
        return $matched_routes;
    }

    function run()
    {
        if ($matched_routes = $this->match()) {
            $matched_route = null;

            foreach($matched_routes as $route) {
                if ($route->http_method === $_SERVER['REQUEST_METHOD']) {
                    $matched_route = $route;
                }
            }

            if (!isset($matched_route)) {
                header("HTTP/1.0 405 Method Not Allowed");
                echo '405 Method Not Allowed';
                die(); 
            } else {
                $this->request->matches = $this->matches;

                $controller_path = 'App\Controllers\\' . $matched_route->controller;

                if (class_exists($controller_path)) {
                    $action = $matched_route->action;

                    if (method_exists($controller_path, $action)) {
                        foreach($matched_route->middleware as $middleware) {
                            $middleware_path = 'App\Middleware\\' . $middleware;
                            $middleware = new $middleware_path;
                            $this->request =  $middleware->handle($this->request);
                        }

                        $controller = new $controller_path($this->request);

                        if ($this->matches) {
                            echo $controller->$action($this->matches);
                        } else {
                            echo $controller->$action(); 
                        }
                    } else {
                        die("Не найден метод: <b>$action</b> в контроллере: <b>$controller_path");
                    }
                } else {
                    die("Не найден контроллер: <b>$$controller_path</b>");
                }
            }

        } else {
            header("HTTP/1.0 404 Not Found");
            require  APP_ROOT . '/public/html_exceptions/404.html';
            die();
        }
    }
}
