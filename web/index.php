<?php

$app = require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Default
$app->match('/', function (Request $request) use ($app) {
    return new Response(json_encode(array('The API to be written')));
});

$app->run();
