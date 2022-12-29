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
            $errors = checkPassword($this->request->data['password'], $this->request->data['confirm_password']);

            if ($errors) {
                return $this->view->render('auth/recoveryForm.twig', ['recovery_token' => $this->request->data["token"], 'form_messages' => $errors]);
            } else {
                $user_model->update($recovery_user['user_id'], ['password' => password_hash($this->request->data['password'], PASSWORD_DEFAULT)]);
                $recovery_user_model->delete('user_id', $recovery_user['user_id']);
                
                redirect(route(['name' => 'login']));
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

    function getSettingProfile()
    {
        $user_model = new User;
        $user = $user_model->get('id', $_SESSION['user_id']);

        return $this->view->render('setting_profile.twig', compact('user'));
    }

    function updateSettingProfile()
    {
        $user_model = new User;
        $user = $user_model->get('id', $_SESSION['user_id']);

        $data = $this->request->data;
        $form_mesages = [];

        if ($data['user_name'] !== $user['user_name'] and $user_model->get('user_name', $data['user_name'])) {
            $form_mesages[] = 'A user with this name already exists';
        } else {
            if ($data['user_name'] !== $user['user_name']) {
                $user_model->update($user['id'], ['user_name' => $data['user_name']]);
                $form_mesages[] = 'Username changed';
            } 
        }

        if (!empty($data['old_password'])) {
            if (password_verify($data['old_password'], $user['password'])) {
                if (!$errors = checkPassword($data['new_password'], $data['confirm_new_password'])) {
                    $user_model->update($user['id'], ['password' => password_hash($data['new_password'], PASSWORD_DEFAULT)]);
                    $form_mesages[] = 'Password changed';
                } else {
                    $form_mesages = array_merge($form_mesages, $errors);
                }
            } else {
                $form_mesages[] = 'The old password is entered incorrectly';
            }
        }

        $user_model->update($user['id'], ['is_notificate' => (int) isset($data['is_notificate'])]);

        if ($data['email'] !== $user['email'] and !$user_model->get('email', $data['email'])) {
            $user_model->update($user['id'], ['email' => $data['email']]);
            $this->service::createPendingUser($user['user_name']);
            $this->service::sendActivationMail($user['user_name']);
            $form_mesages[] = 'We sent form email activation on your new email';
        }
        
        $update_user = $user_model->get('id', $user['id']);

        return $this->view->render('setting_profile.twig', ['user' => $update_user, 'form_messages' => $form_mesages]);
    }
}
