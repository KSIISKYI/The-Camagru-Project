<?php

namespace App\Controllers;


use App\Models\User;
use App\Core\Controller;


class RegisterController extends Controller
{
    function showRegistrationForm()
    {
        return $this->view->render('auth/register.twig');
    }

    function register() {
        $data = $this->request->data;
        if ($data['password'] !== $data['confirm_password']) {
            return $this->view->render('auth/register.twig', ['error' => 'Паролі не збігаються']);
            die();
        }
        $user = new User;
        if ($user->filter(['user_name' => $data['user_name']])) {
            return $this->view->render('auth/register.twig', ['error' => 'Користувач з такий username існує']);
        }
        unset($data['confirm_password']);
        $data['password'] = md5($data['password']);
        $user->create($data);

        header('Location: login');
        exit();
    }
}