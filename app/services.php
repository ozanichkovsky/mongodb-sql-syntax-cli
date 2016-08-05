<?php

use Knp\Provider\ConsoleServiceProvider;
use Lalbert\Silex\Provider\MongoDBServiceProvider;
use Monolog\Logger;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use PHPSQLParser\PHPSQLParser;

use MongoSql\PHPSQLTree\CollectionParser;
use MongoSql\PHPSQLTree\LimitParser;
use MongoSql\PHPSQLTree\ProjectionParser;
use MongoSql\PHPSQLTree\QueryParser;
use MongoSql\PHPSQLTree\SkipParser;
use MongoSql\PHPSQLTree\SortParser;
use MongoSql\Service\MongoRunnerService;
use MongoSql\Service\SqlToMongoService;

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

$app['mongoRunnerService'] = function () use ($app) {
    return new MongoRunnerService($app['mongodb'], $app['mongodb.name']);
};

$app['sql.parser'] = function () {
    return new PHPSQLParser();
};

$app['sql.collection'] = function () {
    return new CollectionParser();
};

$app['sql.query'] = function () {
    return new QueryParser();
};

$app['sql.projection'] = function () {
    return new ProjectionParser();
};

$app['sql.sort'] = function () {
    return new SortParser();
};

$app['sql.limit'] = function () {
    return new LimitParser();
};

$app['sql.skip'] = function () {
    return new SkipParser();
};

$app['sql.facade'] = function () use ($app) {
    $facade = new MongoSql\PHPSQLTree\PHPSQLFacade();
    $facade->setSqlParser($app['sql.parser']);
    $facade->setCollectionParser($app['sql.collection']);
    $facade->setProjectionParser($app['sql.projection']);
    $facade->setQueryParser($app['sql.query']);
    $facade->setSortParser($app['sql.sort']);
    $facade->setLimitParser($app['sql.limit']);
    $facade->setSkipParser($app['sql.skip']);
    return $facade;
};

$app['sqlToMongoService'] = function () use ($app) {
    return new SqlToMongoService($app['sql.facade']);
};