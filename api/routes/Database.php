<?php

\Utils\Route::get('/database/create', function() {
    $databaseController = new \Controller\Database();
    $databaseController->create();
});