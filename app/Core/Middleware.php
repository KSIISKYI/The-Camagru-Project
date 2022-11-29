<?php 

namespace App\Core;

abstract class Middleware
{
    function raise_httm_error (int $status_code, string $error_message)
    {
        header("HTTP/1.0 $status_code $error_message");
        echo $status_code . ' ' . $error_message;
        die();
    }
}