<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Comment, EditedImage, User};

class CommentController extends Controller
{
    function store()
    {
        $comment_model = new Comment;
        $edited_image_model = new EditedImage;
        $user_model = new User;

        $data = $this->request->data;
        $data['user_id'] = $_SESSION['user_id'];
        $comment_model->create($data);

        $comment = $comment_model->raw("SELECT * FROM Camagru.comments 
                                        WHERE edited_image_id = '{$this->request->data['edited_image_id']}' AND user_id = {$_SESSION['user_id']}
                                        ORDER BY id DESC");

        $edited_image = $edited_image_model->get('name', $this->request->data['edited_image_id']);
        $user = $user_model->get('id', $edited_image['user_id']);

        if ($user['is_notificate']) {
            $this->service::sendEmailNotification($edited_image['name'], $comment['id'], $user);
        }

        redirect(route(['name' => 'edited_images.show', 'edited_image_id' => $this->request->data['edited_image_id']]));
    }

    function destroy($arr)
    {
        $comment_model = new Comment;

        $img = $comment_model->get('id', $arr['comment_id'])['edited_image_id'];
        $comment_model->delete('id', $arr['comment_id']);

        echo route(['name' => 'edited_images.show', 'edited_image_id' => $img]);
        exit();
    }
}
