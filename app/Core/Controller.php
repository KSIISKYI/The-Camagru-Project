<?php 

namespace App\Core;

use App\Service;

class Controller
{
    protected $request;
    protected $view;
    protected $service;

    function __construct($request)
    {
        $this->request = $request;
        $this->view = new View;
        $this->service = Service::class;
    }
}
