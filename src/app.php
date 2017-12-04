<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$app['users.dao'] = function($app) {
    return new \DAO\UserDAO($app['pdo']);
};

$app['categories.dao'] = function($app) {
    return new \DAO\CategoryDAO($app['pdo']);
};

$app['loaning.dao'] = function($app) {
    return new \DAO\LoaningDAO($app['pdo']);
};

$app['games.dao'] = function($app) {
    return new \DAO\GameDAO($app['pdo']);
};

$app['pdo'] = function($app) {
    $options = $app['pdo.options'];
    return new \PDO("{$options['dbms']}://host={$options['host']};dbname={$options['dbname']};charset={$options['charset']}",
            $options['username'], 
            $options['password'], 
            array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ));
};

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => [
        'front' => array(
            'pattern' => '^/',
            'http' => true,
            'anonymous' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return $app['users.dao'];
            }
        )
    ]
));
        
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('fr'),
    'translator.domains' => [
        // http://sharemycode.fr/2yd
        'messages' => [
            'fr' => [
                'The credentials were changed from another session.' => 'Les identifiants ont été changés dans une autre session.',
                'The presented password cannot be empty.' => 'Le mot de passe ne peut pas être vide.',
                'The presented password is invalid.' => 'Le mot de passe entré est invalide.',
                'Bad credentials.' => 'Les identifiants sont incorrects'
            ]
        ]
    ]
));

$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());

return $app;
