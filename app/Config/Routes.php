<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'User::index');
$routes->post('/login', 'User::connecter');
$routes->get('/logout', 'User::logout');
$routes->get('/home', 'Home::index');

// Inscription
$routes->get('/inscription', 'User::inscrire');
$routes->post('/user/validerEtape1', 'User::validerEtape1');
$routes->post('/user/finaliser', 'User::finaliserInscription');
