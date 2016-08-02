<?php

use Knp\Provider\ConsoleServiceProvider;
use Lalbert\Silex\Provider\MongoDBServiceProvider;
use Monolog\Logger;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app->register(new ServiceControllerServiceProvider());

$app->register(new ConsoleServiceProvider(), [
    'console.name'              => 'MongoSQLApplication',
    'console.version'           => '2.0.0',
    'console.project_directory' => __DIR__.'/..'
]);

$app->register(new MongoDBServiceProvider(), [
    'mongodb.config' => [
        'server' => $app['mongodb.server'],
        'options' => [],
        'driverOptions' => [],
    ]
]);

$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/logs/app.log',
    'monolog.name' => 'app',
    'monolog.level' => Logger::ERROR
]);
