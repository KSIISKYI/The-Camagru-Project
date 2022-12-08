<?php

namespace App\Controllers;

use App\Models\{User, RecoveryUser};
use App\Core\Controller;

class UserController extends Controller
{
    function showRecoveryFormMail()
    {
        return $this->view->render('auth/recoveryFormMail.twig');
    }

    function sendRecoveryMail()
    {
        $user_model = new User;

        if ($user = $user_model->get('email', $this->request->data['email'])) {
            $this->service::createRecoveryUser($user['id']);
            $this->service::sendRecoveryMail($user['id']);
        }

        return $this->view->render('auth/recoveryFormMail.twig', ['form_message' => 'Інструкція щодо відновлення паролю, була відправлена на Ваш email']);
    }

    function showRecoveryForm()
    {
        $recovery_user_model = new RecoveryUser;
        $user_model = new User;

        if (isset($this->request->data["token"]) and $recovery_user_model->get('token', $this->request->data["token"])) {
            return $this->view->render('auth/recoveryForm.twig', ['recovery_token' => $this->request->data["token"]]);
        } else {
            echo 'BAD';
            exit();
        }
    }

    function resetPassword()
    {
        $recovery_user_model = new RecoveryUser;
        $user_model = new User;

        if (isset($this->request->data["token"]) and $recovery_user = $recovery_user_model->get('token', $this->request->data["token"])) {
            if ($this->request->data['password'] !== $this->request->data['confirm_password']) {
                echo $this->view->render('auth/recoveryForm.twig', ['recovery_token' => $this->request->data["token"], 'form_message' => 'Паролі не збігаються']);
                exit();
            } else {
                $user = $user_model->update($recovery_user['user_id'], ['password' => password_hash($this->request->data['password'], PASSWORD_DEFAULT)]);
                $recovery_user_model->delete('user_id', $recovery_user['user_id']);
                header('Location: login');
                exit();
            }
        } else {
            echo 'BAD';
            exit();
        }
    }

    function profile()
    {
        return $this->view->render('profile.twig');
    }
}
