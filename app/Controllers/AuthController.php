<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Controller;

class AuthController extends Controller
{
    function showLoginForm()
    {
        return $this->view->render('auth/login.twig', []);
    }

    function login()
    {
        $data = $this->request->data;
        $user_model = new User;

        if (!$user = $user_model->get('email', $data['email']) or !password_verify($data['password'], $user['password'])) {
            return $this->view->render('auth/login.twig', ['form_message' => 'Email or password entered incorrectly']);
            exit();
        } elseif (!$user['is_active']) {
            return $this->view->render('auth/login.twig', ['form_message' => 'We have sent you an email with information about mail verification']);
            exit();
        } else {
            $_SESSION['user_id'] = $user['id'];
            header('Location: profile');
            exit();
        }
    }
    
    function logout()
    {
        unset($_SESSION['user_id']);
        header('Location: login');
        exit();
    }
}
