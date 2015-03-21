<?php

$app = require_once __DIR__.'/../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// List of POIS (GET)
$app->get('/pois', function (Request $request) use ($app) {

    if (null !== ($latitude = $request->get('latitude')) &&
        null !== ($longitude = $request->get('longitude'))) {

        return $app->json($app['poi_persister']->findNearBy(
            $latitude,
            $longitude,
            $request->get('radius', 50)
        ));
    }

    return $app->json($app['poi_persister']->findAll());
});


// POI (GET)
$app->get('/pois/{poiId}', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        return $app->json(array('error' => "POI $poiId does not exist."), 404);
    }

    return $app->json($poi);
});


// List of Coupons (GET)
$app->get('/pois/{poiId}/coupons', function (Request $request, $poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        return $app->json(array('error' => "POI $poiId does not exist."), 404);
    }

    // @todo reeanble security
    // return $app->json($app['coupon_persister']->findAllByPoiIdWithUserStatus($poiId, $app['security']->getToken()->getUser()->getId()));
    return $app->json($app['coupon_persister']->findAllByPoiIdWithUserStatus($poiId, $request->get('user_id')));
});


// Random question of a POI (GET)
$app->get('/pois/{poiId}/questions/random', function ($poiId) use ($app) {
    $poi = $app['poi_persister']->findOneById($poiId);

    if (false === $poi) {
        return $app->json(array('error' => "POI $poiId does not exist."), 404);
    }

    $question = $app['question_persister']->findOneRandomByPoiId($poiId);
    if (false === $question) {
        return $app->json(array('error' => "POI $poiId has no question."), 404);
    }

    // Removes wich is the good answer and shuffle
    $choices = [$question['right_answer'], $question['wrong_answer1']];
    $question['wrong_answer2'] and $choices[] = $question['wrong_answer2'];
    $question['wrong_answer3'] and $choices[] = $question['wrong_answer3'];
    unset(
        $question['right_answer'],
        $question['wrong_answer1'],
        $question['wrong_answer2'],
        $question['wrong_answer3']
    );
    shuffle($choices);
    $question['choices'] = $choices;

    return $app->json($question);
});


// Play for a coupon (POST)
$app->post('/pois/{poiId}/coupons/{couponId}/play', function (Request $request, $poiId, $couponId) use ($app) {
    $coupon = $app['coupon_persister']->findOneByIdAndPoiId($couponId, $poiId);

    if (false === $coupon) {
        return $app->json(array('error' => "POI $poiId and/or coupon $couponId does not exist."), 404);
    }

    $data = json_decode($request->getContent(), true);
    $question = $app['question_persister']->findOneById($data['question_id']);

    if (false === $question) {
        return $app->json(array('error' => sprintf('The question %s does not exist.', $data['question_id'])), 400);
    }

    if ($poiId !== $question['poi_id']) {
        return $app->json(sprintf("The question %s is not linked to POI $poiId.", $data['question_id']), 400);
    }

    // @todo reeanble security
    // if ($question['right_answer'] == $request->get('answer')) {
    //     $user = $app['security']->getToken()->getUser();
    //     $app['coupon_persister']->addWinner($couponId, $user->getId());
    //     return $app->json(array(
    //         'return'      => 'won',
    //         'coupon_code' => $couponId.'|'.$user->getId()
    //     ));
    // }
    if ($question['right_answer'] == $data['answer']) {
        $app['coupon_persister']->addWinner($couponId, $request->get('user_id'));
        return $app->json(array(
            'result'      => 'won',
            'coupon_code' => $couponId.'|'.$request->get('user_id')
        ));
    }

    return $app->json(array('result' => 'lost'));
});

$app->after($app['cors']);

$app->run();