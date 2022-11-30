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

        if (!$user = $user_model->filter(['email' => $data['email']])) {
            return $this->view->render('auth/login.twig', ['error' => 'Email або пароль уведені невірно']);
            exit();
        } elseif (!password_verify($data['password'], $user[0]['password'])) {
            return $this->view->render('auth/login.twig', ['error' => 'Email або пароль уведені невірно']);
            exit();
        } else {
            $_SESSION['user_id'] = $user[0]['id'];
            header('Location: ' . SITE_NAME);
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