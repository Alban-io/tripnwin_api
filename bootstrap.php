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

$app['coupon_persister'] = function($app) {
    return new TripNWin\Persister\CouponPersister($app['db']);
};
$app['poi_persister'] = function($app) {
    return new TripNWin\Persister\PoiPersister($app['db']);
};

return $app;
