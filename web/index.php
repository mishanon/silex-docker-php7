<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/hello', function () use ($app) {

    return $app->json([
        'status'    => 1,
        'data'      => [
            'msg'   => 'Hello World !'
        ]
    ], 200);
});

$app['debug'] = true;

$app->run();
