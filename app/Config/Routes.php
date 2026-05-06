<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'User::index');
$routes->post('/login', 'User::connecter');
$routes->get('/home', 'Home::index');
