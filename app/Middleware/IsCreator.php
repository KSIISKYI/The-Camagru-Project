<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Models\EditedImage;

class IsCreator extends Middleware
{
    function handle($request)
    {
        $edited_image_model = new EditedImage;
        $edited_image = $edited_image_model->get('name', ($request->matches['edited_image_id']));

        if (!$edited_image) $this->raise_httm_error(404, 'Page Not Found');

        if ($edited_image['user_id'] !== $request->user['id'] ) {
            $this->raise_httm_error(403, 'Forbidden');
        }
        
        return $request;   
    }
}
