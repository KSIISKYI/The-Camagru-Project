<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Models\User;

class Authenticate extends Middleware
{
    function handle($request)
    {
        if (!isset($_SESSION['user_id'])) return $this->raise_httm_error(401, 'Not Unauthorized');

        $user_model = new User;
        $user = $user_model->get($_SESSION['user_id']);

        if (!$user) return $this->raise_httm_error(401, 'Not Unauthorized');

        $request->user = $user;
        
        return $request;   
    }
}