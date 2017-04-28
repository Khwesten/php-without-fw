<?php

\Utils\Route::post('/user/register', function() {
    $userController = new \Controller\User();
    $userController->register();
});

\Utils\Route::get('/user/email', function() {
    $userController = new \Controller\User();
    $userController->getByEmail();
});