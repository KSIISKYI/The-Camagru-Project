<?php 
namespace App\Models;

use App\Core\Models;

class EditedImage extends Models
{
    protected $table = 'edited_images';

    function get($field, $value, $table = null) {
        $data = parent::get($field, $value);
        $data['user'] = parent::get('id', $data['user_id'], 'users');
        $data['comments'] = parent::filter(['user_id' => $data['user_id']], 'comments');
        $data['likes'] = parent::filter(['edited_image_id' => $data['name']], 'likes');
        
        return $data;
    }

    function filter(array $filers = [], $table = null) {
        $data = parent::filter($filers);
        foreach($data as $key => $item) {
            $data[$key]['user'] = parent::get('id', $item['user_id'], 'users');
            $data[$key]['comments'] = parent::filter(['edited_image_id' => $item['name']], 'comments');
            $data[$key]['likes'] = parent::filter(['edited_image_id' => $item['name']], 'likes');
        }

        return $data;
    }
}
