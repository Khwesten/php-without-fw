<?php

include "vendor/autoload.php";

$app = new \Utils\Application();

include "routes/main.php";

$app->start();
