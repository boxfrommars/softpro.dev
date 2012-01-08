<?php

$app = new Silex\Application();

$app['starttime'] = microtime(true);
$app['debug'] = true;

$app['silex_path'] = realpath(__DIR__ . '/../../Silex');
$app['application_path'] = __DIR__;
$app['tmp_path'] = realpath($app['application_path'] . '/../tmp');
$app['vendor_path'] = realpath($app['silex_path'] . '/vendor');

$app['autoloader']->registerNamespace('Softpro', $app['application_path']);

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile'       => $app['tmp_path'] . '/sftp.log',
    'monolog.class_path'    => $app['vendor_path'] . '/monolog/src',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => $app['application_path'] . '/views',
    'twig.class_path' => $app['vendor_path'] . '/twig/lib',
    'twig.options' => array(
        'cache' => $app['tmp_path'] . '/silex_cache',
        'debug' => $app['debug']
    ),
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
        'driverOptions' => array(
            '1002' => "SET NAMES 'UTF8'",
        ),
        'host' => 'localhost',
        'dbname' => 'softpro',
        'user' => 'softpro',
        'password' => 'softpro',
    ),
    'db.dbal.class_path' => $app['vendor_path'] . '/doctrine-dbal/lib',
    'db.common.class_path' => $app['vendor_path'] . '/doctrine-common/lib',
));

$app['layout.name'] = 'layout.twig';
$app['layout'] = $app->share(function() use ($app) {
    return $app['twig']->loadTemplate($app['layout.name']);
});

$app->get('/', function () use ($app) {
    $app['layout.name'] = 'main.twig';
    return $app['layout']->render(array());
});

$app->mount('/blog', new Softpro\Controller\BlogControllerProvider());

$app->after(function() use ($app) {
    $app['monolog']->addInfo('generated for: ' . (int) ((microtime(true) - $app['starttime']) * 1000) . "ms\n\n");
});