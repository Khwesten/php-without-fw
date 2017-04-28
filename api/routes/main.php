<?php

include "Database.php";
include "User.php";

\Utils\Route::get('/', function() {
    die("Welcome to test API");
});