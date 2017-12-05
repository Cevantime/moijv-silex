<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/loginadmin', function(Request $request) use ($app) {
            return $app['twig']->render('admin/login_admin.html.twig', array(
                        'error' => $app['security.last_error']($request),
                        'last_username' => $app['session']->get('_security.last_username'),
            ));
        })
        ->bind('loginadmin')
;

$adminGroup = $app['controllers_factory'];

$adminGroup->get('dashboard', function() use($app) {
            return $app['twig']->render('admin/dashboard.html.twig');
        })
        ->bind('admin_dashboard')
;

$adminGroup->get('userlist', function() use ($app) {
            $users = $app['users.dao']->findMany();
            return $app['twig']->render('admin/userlist.html.twig', [
                        'users' => $users
            ]);
        })
        ->bind('admin_userlist')
;

$adminGroup->get('/userdelete/{id}', function($id) use ($app) {
            $user = $app['users.dao']->find($id);
            $app['users.dao']->delete($user);
            return $app->redirect($app['url_generator']->generate('admin_userlist'));
        })
        ->bind('admin_userdelete');
;

$adminGroup->match('/useredit/{id}', function (Request $request, $id) use ($app) {
            $user = $app['users.dao']->find($id);

            $form = $app['form.factory']->createBuilder(\FormType\UserType::class, $user)
                    ->remove('password')
                    ->add('submit', SubmitType::class, [
                        'label' => 'Modifier'
                    ])
                    ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {

                $app['users.dao']->save($user);

                return $app->redirect($app['url_generator']->generate('admin_userlist'));
            }

            $formView = $form->createView();

            return $app['twig']->render('admin/useredit.html.twig', ['form' => $formView]);
        })
        ->bind('admin_useredit')
        ->method('GET|POST');

$app->mount('/admin', $adminGroup);
