<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

$app->match('/register', function(Request $request) use ($app) {
    $user = new \Entity\User();
    
    $form = $app['form.factory']->createBuilder(\FormType\UserType::class, $user)
            ->getForm();
    
    $form->handleRequest($request);
    
    if($form->isValid()) {
        $user->setRoles('ROLE_USER');
        
        $salt = md5(time());
        
        $user->setSalt($salt);
        
        $encodedPassword = $app['security.encoder_factory']
                ->getEncoder($user)
                ->encodePassword($password, $user->getSalt());
        
        $user->setPassword($encodedPassword);
        
        $app['users.dao']->save($user);
    }
    
    $formView = $form->createView();
    
    return $app['twig']->render('register.html.twig', ['form' => $formView]);
})
->method('GET|POST')
;

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
