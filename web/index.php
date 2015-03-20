<?php

$app = require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;

// Default
$app->match('/', function (Request $request) use ($app) {
    return json_encode(array('The API to be written'));
});

$app->run();
