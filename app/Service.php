<?php

namespace App;

use PHPMailer\PHPMailer\{PHPMailer, Exception, SMTP};

use App\Models\{User, PendingUser, RecoveryUser};
use App\Core\View;

require APP_ROOT . '/app/Core/funcs.php';

class Service
{
    static function createUser(array $data, $view)
    {
        $user_model = new User;

        $form_messages = [];

        if ($user_model->filter(['user_name' => $data['user_name']])) {
            $form_messages[] = 'A user with this name already exists';
        }

        if ($user_model->filter(['email' => $data['email']])) {
            $form_messages[] = 'A user with this email already exists';
        }

        if ($errors = checkPassword($data['password'], $data['confirm_password'])) {
            $form_messages = array_merge($form_messages, $errors);
        }

        if ($form_messages) {
            return $form_messages;
        } else {
            unset($data['confirm_password']);
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $user_model->create($data);
            self::createPendingUser($data['user_name']);
            self::sendActivationMail($data['user_name']);
    
            return [];
        }
    }

    static function createPendingUser($username)
    {
        $user_model = new User;
        $user = $user_model->get('user_name', $username);

        $pending_user_model = new PendingUser;
        $pending_user_model->create([
            'token' => sha1(uniqid($username, true)),
            'user_id' => $user['id'],
            'tstamp' => $_SERVER['REQUEST_TIME']
        ]);
    }

    static function sendMail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.ukr.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'oleksiyyy882@ukr.net';
            $mail->Password   = 'aDqKAMv4M4EfWg8M';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 2525;             
        
            //Recipients
            $mail->setFrom('oleksiyyy882@ukr.net', 'The Camagru Project');
            $mail->addAddress($to);
        
            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->msgHTML($body);
  
            $mail->send();
        
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    static function getActivationToken($user_id)
    {
        $pending_user_model = new PendingUser;
        $pending_user = $pending_user_model->get('user_id', $user_id);

        return $pending_user['token'];
    }

    static function sendActivationMail($username)
    {
        $user_model = new User;
        $user = $user_model->get('user_name', $username);

        $viem = new View;
        $body = $viem->render('auth/activationMail.twig', ['activation_token' => self::getActivationToken($user['id'])]);

        self::sendMail($user['email'], 'The Camagru', $body);
    }

    static function createRecoveryUser($user_id)
    {
        $recover_user_model = new RecoveryUser;
        $recover_user_model->create([
            'token' => sha1(uniqid($user_id, true)),
            'user_id' => $user_id,
            'tstamp' => $_SERVER['REQUEST_TIME']
        ]);
    }

    static function getRecoveryToken($user_id)
    {
        $recover_user_model = new RecoveryUser;
        $recover_user = $recover_user_model->get('user_id', $user_id);

        return $recover_user['token'];
    }

    static function sendRecoveryMail(int $user_id)
    {
        $user_model = new User;
        $user = $user_model->get('id', $user_id);

        $viem = new View;
        $body = $viem->render('auth/recoveryMail.twig', ['recovery_token' => self::getRecoveryToken($user['id'])]);

        self::sendMail($user['email'], 'The Camagru', $body);
    }
}
