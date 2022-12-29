<?php

use App\Core\Route;

return [
    // REGISTRATION
    new Route('register', 'RegisterController', 'showRegistrationForm', 'GET'),
    new Route('signup', 'RegisterController', 'register', 'POST'),
    new Route('activate', 'RegisterController', 'activate', 'POST'),

    // Authorization
    new Route('login', 'AuthController', 'showLoginForm', 'GET'),
    new Route('signin', 'AuthController', 'login', 'POST'),
    new Route('logout', 'AuthController', 'logout', 'GET', 'logout', ['Authenticate']),
    
    //RECOVERY PASSWORD
    new Route('recovery_email', 'UserController', 'showRecoveryFormMail', 'GET'),
    new Route('send_recovery_mail', 'UserController', 'sendRecoveryMail', 'GET'),
    new Route('recovery_form', 'UserController', 'showRecoveryForm', 'POST'),
    new Route('reset_password', 'UserController', 'resetPassword', 'POST'),

    // PROFILE
    new Route('profile', 'UserController', 'profile', 'GET', 'profile', ['Authenticate']),

    // HOME
    new Route('', 'HomeController', 'index', 'GET', 'home'),

    // USER SETTINGS
    new Route('settings', 'UserController', 'getSettingProfile', 'GET', 'settings', ['Authenticate']),
    new Route('update_settings', 'UserController', 'updateSettingProfile', 'POST', 'update_settings', ['Authenticate']),

    // GALLERY
    new Route('edited_images', 'EditedPhotoController', 'index', 'GET', 'edited_images.index', ['Authenticate']),
    new Route('edited_images', 'EditedPhotoController', 'store', 'POST', 'edited_images.store', ['Authenticate']),
    new Route('edited_images\/(?P<edited_image_id>[^\/]+)', 'EditedPhotoController', 'destroy', 'DELETE', 'edited_images.destroy', ['Authenticate', 'IsCreator']),
    new Route('edited_images\/(?P<edited_image_id>[^\/]+)', 'EditedPhotoController', 'show', 'GET', 'edited_images.show', ['Authenticate']),

    //COMMENT
    new Route('comments', 'CommentController', 'store', 'POST', 'comments.store', ['Authenticate']),
    new Route('comments\/(?P<comment_id>[^\/]+)', 'CommentController', 'destroy', 'DELETE', 'comments.destroy', ['Authenticate']),

    //LIKES
    new Route('likes', 'likeController', 'store', 'POST', 'likes.store', ['Authenticate']),
    new Route('likes\/(?P<like_id>[^\/]+)', 'likeController', 'destroy', 'DELETE', 'likes.destroy', ['Authenticate', 'IsLikeCreator']),
];
