<?php

$app = require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// List of POIS (GET)
$app->match('/pois', function () use ($app) {
    return new Response(json_encode($app['poi_persister']->findAll()));
});


// POI (GET)
$app->match('/pois/{poiId}', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        $app->abort(404, "POI $poiId does not exist.");
    }

    return new Response(json_encode($poi));
});


// List of Coupons (GET)
// @todo: tell if the user has already won a coupon
$app->match('/pois/{poiId}/coupons', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        $app->abort(404, "POI $poiId does not exist.");
    }

    return new Response(json_encode($app['coupon_persister']->findAllByPoiId($poiId)));
});


// Random question of a POI (GET)
$app->match('/pois/{poiId}/questions/random', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        $app->abort(404, "POI $poiId does not exist.");
    }

    $question = $app['question_persister']->findOneRandomByPoiId($poiId);
    if (false === $question) {
        $app->abort(404, "POI $poiId has no question.");
    }

    // Removes wich is the good answer and shuffle
    $answers = [$question['right_answer'], $question['wrong_answer1']];
    $question['wrong_answer2'] and $answers[] = $question['wrong_answer2'];
    $question['wrong_answer3'] and $answers[] = $question['wrong_answer3'];
    unset(
        $question['right_answer'],
        $question['wrong_answer1'],
        $question['wrong_answer2'],
        $question['wrong_answer3']
    );
    shuffle($answers);
    $question['choices'] = $answers;

    return new Response(json_encode($question));
});


// Play for a coupon (POST)
$app->match('/pois/{poiId}/coupons/{couponId}/play', function ($poiId, $couponId) use ($app) {
    // @todo
});


$app->run();
