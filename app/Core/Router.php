<?php

namespace App\Core;


class Router
{
    protected $routes = [];
    protected $params = [];
    protected $request;

    function __construct($request)
    {
        $routes = require APP_ROOT.'/routes/web.php';
        foreach($routes as $route => $params) {
            $this->routes["#^$route$#"] = $params;
            $this->request = $request;
        }
    }

    function match()
    {
        $url = $_REQUEST['route'];
        foreach($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    function run()
    {
        if ($this->match()) {
            $controller_path = 'App\Controllers\\' . $this->params['controller'];

            //If method not allowed
            if ($_SERVER['REQUEST_METHOD'] !== $this->params['http_method']) {
                header("HTTP/1.0 405 Method Not Allowed");
                echo '405 Method Not Allowed';
                die();
            } elseif (class_exists($controller_path)) {
                $action = $this->params['action'];
                if (method_exists($controller_path, $action)) {
                    
                    foreach($this->params['middleware'] as $middleware) {
                        $middleware_path = 'App\Middleware\\' . $middleware;
                        $middleware = new $middleware_path;
                        $this->request =  $middleware->handle($this->request);
                    }

                    $controller = new $controller_path($this->request);
                    echo $controller->$action();
                } else {
                    echo "Не найден метод: <b>$$action</b> в контроллере: <b>$controller_path</b>";
                }
            } else {
                echo "Не найден контроллер: <b>$$controller_path</b>"; 
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            require  APP_ROOT . '/public/html_exceptions/404.html';
            die();
        }
    }
}