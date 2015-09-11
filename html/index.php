<?php

require_once __DIR__.'/../vendor/autoload.php'; // Add the autoloading mechanism of Composer
require_once __DIR__.'/../app/app.php';
require_once __DIR__.'/../app/routes.php';
require_once __DIR__.'/../app/controllers.php';
require_once __DIR__.'/../app/variables.php';

$app->run();

?>
