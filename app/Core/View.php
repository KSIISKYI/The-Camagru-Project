<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\{Environment, TwigFunction};
use Twig\Extension\AbstractExtension;

use App\Models\User;

class View
{
    protected $loader;
    protected $twig;


    function __construct()
    {
        $this->loader = new FilesystemLoader(APP_ROOT . '/views');
        $this->twig = new Environment($this->loader);
        $this->twig->addExtension(new AssetExtension);
    }

    function render(string $view_name, array $data = [])
    {
        return $this->twig->render($view_name, $data);
    }
}


class AssetExtension extends AbstractExtension
{
    function getFunctions()
    {
        return [
            new TwigFunction('route', [$this, 'getRoute']),
            new TwigFunction('getUser', [$this, 'getUser'])
        ];
    }

    function getRoute($arr)
    {
        return route($arr);
    }

    function getUser()
    {
        if (isset($_SESSION['user_id'])) {
            $user_model = new User;
            return $user_model->get('id', $_SESSION['user_id']);
        }
    }
}
