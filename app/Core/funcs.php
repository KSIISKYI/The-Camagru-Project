<?php

function checkPassword($password, $confirm_password)
{
    $errors = [];
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (strlen($password) < 8 or strlen($confirm_password) < 8) {
        $errors[] = 'Password must contain at least 8 characters';
    }

    if (!preg_match('#[0-9]#', $password)) {
        $errors[] = 'Password must contain at least one digit';
    }

    if (!preg_match('#[а-я a-z]#', $password)) {
        $errors[] = 'Password must contain at least one letter';
    }
    
    return $errors;
}
