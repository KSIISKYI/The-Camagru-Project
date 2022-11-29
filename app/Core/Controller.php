<?php 

namespace App\Core;

class Controller
{
    protected $request;
    protected $view;

    function __construct($request)
    {
        $this->request = $request;
        $this->view = new View;
    }
}