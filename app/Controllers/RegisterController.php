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

        if ($this->service::createUser($data, $this->view)) {
            return $this->view->render('auth/register.twig', ['form_message' => 'Реєстрація успішна, перейдійть на Ваш email та підтвердіть його']);
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
            
            header('Location: profile');
            exit();
        } else {
            echo 'BAD';
            exit();
        }
    }
}
