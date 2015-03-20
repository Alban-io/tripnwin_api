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
$app->match('/pois/{poiId}/coupons', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        $app->abort(404, "POI $poiId does not exist.");
    }

    return new Response(json_encode($app['coupon_persister']->findAllByPoiIdWithUserStatus($poiId, $app['security']->getToken()->getUser()->getId())));
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
$app->match('/pois/{poiId}/coupons/{couponId}/play', function (Request $request, $poiId, $couponId) use ($app) {
    if ('POST' !== $request->getMethod()) {
        $app->abort(404, "This is a POST method");
    }

    $coupon = $app['coupon_persister']->findOneByIdAndPoiId($couponId, $poiId);

    if (false === $coupon) {
        $app->abort(404, "POI $poiId and/or coupon $couponId does not exist.");
    }

    $question = $app['question_persister']->findOneById($request->get('question_id'));

    if (false === $question) {
        $app->abort(400, sprintf('The question %s does not exist.', $request->get('question_id')));
    }

    if ($poiId !== $question['poi_id']) {
        $app->abort(400, sprintf("The question %s is not linked to POI $poiId.", $request->get('question_id')));
    }

    if ($question['right_answer'] == $request->get('answer')) {
        $user = $app['security']->getToken()->getUser();
        $app['coupon_persister']->addWinner($couponId, $user->getId());
        return new Response(json_encode(array(
            'return'      => 'won',
            'coupon_code' => $couponId.'|'.$user->getId()
        )));
    }

    return new Response(json_encode(array('return' => 'wrong')));
});


$app->run();
