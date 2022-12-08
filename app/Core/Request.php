<?php

namespace App\Core;

class Request
{
    public $data = [];
    public $files = [];
    public $server = [];
    public $user;

    function __construct($user = null)
    {
        $this->server = $_SERVER;
        $this->data = $this->server['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;
        $this->files = $_FILES;
    }
}
