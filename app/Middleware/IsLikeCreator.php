<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Models\Like;

class IsLikeCreator extends Middleware
{
    function handle($request)
    {
        $like_model = new Like;
        $like = $like_model->get('id', $request->matches['like_id']);

        if (!$like) $this->raise_httm_error(404, 'Page Not Found');

        if ($like['user_id'] !== $request->user['id'] ) {
            $this->raise_httm_error(403, 'Forbidden');
        }
        
        return $request;   
    }
}
