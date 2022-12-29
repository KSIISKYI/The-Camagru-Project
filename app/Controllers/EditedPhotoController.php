<?php

namespace App\Controllers;

use App\Models\{EditedImage, Comment};
use App\Core\{Controller, Paginator};

class EditedPhotoController extends Controller
{
    function index()
    {   
        $edited_image_model = new EditedImage;
        $pgn = new Paginator($edited_image_model->filter(), 4);

        $page_number = isset($this->request->data['page']) ? $this->request->data['page'] : 1;

        $images = $pgn->getData($page_number);
        $page_obj = $pgn->getPageObj($page_number);
        

        return $this->view->render('edited_image/index.twig', ['images' => $images, 'page_obj' => $page_obj]);
    }

    function store()
    {
        $edited_image_model = new EditedImage;
        $img = saveImg($this->request->data['img_base64']);
        $edited_image_model->create(['name' => $img['file_name'], 'path' =>  $img['file_path'], 'user_id' => $this->request->user['id']]);
    }

    function show(array $arr)
    {
        $edited_image_model = new EditedImage;
        $image = $edited_image_model->get('name', $arr['edited_image_id']);

        $comment_model = new Comment;
        $comments = $comment_model->filter(['edited_image_id' => $image['name']]);

        return $this->view->render('edited_image/show.twig', ['image' => $image, 'comments' => $comments]);
    }

    function destroy(array $arr) {
        $edited_image_model = new EditedImage;

        $edited_image_model->delete('name', $arr['edited_image_id']);

        redirect(route(['name' => 'edited_images.index']));
    }
}
