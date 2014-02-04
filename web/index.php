<?php

require __DIR__ . "/../vendor/autoload.php";

use Silex\Provider;
use Silex\Application;

$app = new Application();
$app["debug"] = true;

if ($app["debug"]) {
    $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
}

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app["sessions.controller"] = $app->share(function () use ($app) {
    return new SQLBoss\Controller\Sessions;
});

$app->get("/", "sessions.controller:indexAction");

$app->run();
