<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'User::index');
$routes->post('/login', 'User::connecter');
$routes->get('/logout', 'User::logout');
$routes->get('/home', 'Home::index');
$routes->post('/home/objectif', 'Home::enregistrerObjectif');
$routes->post('/home/recharge', 'Home::rechargerPortefeuille');
$routes->post('/home/gold', 'Home::activerGold');
$routes->get('/home/export/pdf', 'Home::exporterPdf');

// Inscription
$routes->get('/inscription', 'User::inscrire');
$routes->post('/user/validerEtape1', 'User::validerEtape1');
$routes->post('/user/finaliser', 'User::finaliserInscription');
