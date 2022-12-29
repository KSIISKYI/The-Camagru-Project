<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{
    protected $loader;
    protected $twig;


    function __construct()
    {
        $this->loader = new FilesystemLoader(APP_ROOT . '/views');
        $this->twig = new Environment($this->loader);
    }

    function render(string $view_name, array $data = [])
    {
        return $this->twig->render($view_name, $data);
    }
}