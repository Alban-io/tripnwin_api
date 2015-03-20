<?php

$app = require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// List of POIS
$app->match('/pois', function () use ($app) {
    return new Response(json_encode($app['poi_persister']->findAll()));
});

// POI
$app->match('/pois/{poiId}', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        $app->abort(404, "POI $poiId does not exist.");
    }

    return new Response(json_encode($poi));
});

$app->run();
