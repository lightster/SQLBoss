<?php

require __DIR__ . "/../vendor/autoload.php";

use Silex\Provider;
use Silex\Application;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\Yui\JsCompressorFilter as YuiCompressorFilter;

$app = new Application();
$app["debug"] = true;

if ($app["debug"]) {
    $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
}

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app["sessions.controller"] = $app->share(function () use ($app) {
    return new SQLBoss\Controller\Sessions;
});

$config = include __DIR__ . '/../config/sprockets.php';

$parser = new Codesleeve\Sprockets\SprocketsParser($config);
var_dump($parser->javascriptFiles('application')); exit;

$app->get("/", "sessions.controller:indexAction");

$app->get("assets/{file}.js", function (Application $app, $file) {

    $file = file_get_contents(__DIR__ . "/../assets/javascripts/{$file}.js");
    
    var_dump($file); exit;

    $js = new AssetCollection(array(
        new FileAsset(__DIR__ . '/../assets/javascripts/application.js'),
    ), array(
        new YuiCompressorFilter('/var/www/html/codebase-illuminate/allthedata-libraries/libraries/yuicompressor-2.4.8.jar'),
    ));

    header('Content-Type: application/javascript');
    echo $js->dump();
    exit;
});

$app->run();
