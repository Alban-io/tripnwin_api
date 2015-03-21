<?php

require_once __DIR__.'/config.php';
require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = $debug;

// Services
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => $mysql_host,
        'dbname'   => $mysql_database,
        'user'     => $mysql_user,
        'password' => $mysql_password,
    ),
));

// @todo reenable security
// $app->register(new Silex\Provider\SecurityServiceProvider(), array(
//     'security.firewalls' => array(
//         'default' => array(
//             'stateless' => true,
//             'http'      => true,
//             'users'     => $app->share(function () use ($app) {
//                 return new TripNWin\Security\UserProvider($app['db']);
//             }),
//         ),
//     )
// ));

// $app['security.encoder.digest'] = $app->share(function ($app) {
//     return new Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha1', false, 1);
// });

$app->register(new JDesrosiers\Silex\Provider\CorsServiceProvider(), array(
    'cors.allowOrigin' => $cors_allow_origin,
));

$app['coupon_persister'] = function($app) {
    return new TripNWin\Persister\CouponPersister($app['db']);
};
$app['poi_persister'] = function($app) {
    return new TripNWin\Persister\PoiPersister($app['db']);
};
$app['question_persister'] = function($app) {
    return new TripNWin\Persister\QuestionPersister($app['db']);
};

return $app;