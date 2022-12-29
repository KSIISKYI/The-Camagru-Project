<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Like};

class LikeController extends Controller
{
    function store()
    {
        $like_model = new Like;

        $data = $this->request->data;
        $data['user_id'] = $_SESSION['user_id'];

        $like_model->create($data);

        redirect(route(['name' => 'edited_images.index']));
    }

    function destroy($arr)
    {
        $like_model = new Like;
        
        $like_model->delete('id', $arr['like_id']);
    }
}
