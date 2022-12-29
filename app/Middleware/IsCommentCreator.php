<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Models\Comment;

class IsCommentCreator extends Middleware
{
    function handle($request)
    {
        $comment_model = new Comment;
        $comment = $comment_model->get('id', ($request->matches['comment_id']));

        if (!$comment) $this->raise_httm_error(404, 'Page Not Found');

        if ($comment['user_id'] !== $request->user['id'] ) {
            $this->raise_httm_error(403, 'Forbidden');
        }
        
        return $request;   
    }
}
