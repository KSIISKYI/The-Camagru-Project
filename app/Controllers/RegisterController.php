<?php

namespace App\Controllers;

use App\Models\PendingUser;
use App\Core\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    function showRegistrationForm()
    {   
        return $this->view->render('auth/register.twig');
    }

    function register()
    {
        $data = $this->request->data;

        if (!$errors = $this->service::createUser($data, $this->view)) {
            return $this->view->render('auth/register.twig', ['form_messages' => ['Registration is successful, go to your email and confirm it']]);
        } else {
            return $this->view->render('auth/register.twig', ['form_messages' => $errors]);
        }
    }

    function activate()
    {
        $pending_user_model = new PendingUser;
        $user_model = new User;

        if (isset($this->request->data["token"]) and $pending_user = $pending_user_model->get('token', $this->request->data["token"])) {
            $_SESSION['user_id'] = $pending_user['user_id'];
            $user_model->update($pending_user['user_id'], ['is_active' => True]);
            $pending_user_model->delete('token', $this->request->data["token"]);
            
            redirect(route(['name' => 'profile']));
        } else {
            echo 'BAD';
            exit();
        }
    }
}
