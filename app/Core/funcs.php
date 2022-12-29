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

function addWatermark(string $filename)
{
    $img = imagecreatefromjpeg(APP_ROOT . '/public/img/edited_images/' . $filename);
    $logo = imagecreatefrompng(APP_ROOT . '/public/img/logo_icon.png');
    $logo_w = getimagesize(APP_ROOT . '/public/img/edited_images/' . $filename)[0] / 15;
    $logo_h = getimagesize(APP_ROOT . '/public/img/edited_images/' . $filename)[1] / 15;

    imagecopyresampled(
        $img,
        $logo,
        (getimagesize(APP_ROOT . '/public/img/edited_images/' . $filename)[0] - $logo_w) - 10,
        (getimagesize(APP_ROOT . '/public/img/edited_images/' . $filename)[1] - $logo_h) - 10,
        0,
        0,
        $logo_w,
        $logo_h,
        getimagesize(APP_ROOT . '/public/img/logo_icon.png')[0],
        getimagesize(APP_ROOT . '/public/img/logo_icon.png')[1],
    );

    imagejpeg($img, APP_ROOT . '/public/img/edited_images/' . $filename);
	imagedestroy($img);
}

function saveImg(string $data_base64)
{
    $data = base64_decode($data_base64);
    $file_name = time() . '.jpeg';
    $file_path = 'edited_images/' . $file_name;
    file_put_contents(APP_ROOT . '/public/img/' . $file_path, $data);
    
    addWatermark($file_name);
    
    return compact('file_name', 'file_path');
}

function route($arr)
{
    $routes = require APP_ROOT . '/routes/web.php';
    $url_prefix = 'http://' .$_SERVER['HTTP_HOST'] . URL_ROOT;

    $route_pattern = null;

    foreach($routes as $route) {
        if (isset($route->name) && $route->name === $arr['name']) {
            $route_pattern = $route->route;
        }
    }

    if (!isset($route_pattern)) {
        return '#';
    } else {
        unset($arr['name']);
        foreach($arr as $key => $value) {
            $route_pattern = str_replace("\/(?P<$key>[^\/]+)", "/$value", $route_pattern);
        }

        return $url_prefix . $route_pattern;
    }
}

function redirect(string $route)
{
    header("location: $route");
    exit();
}
