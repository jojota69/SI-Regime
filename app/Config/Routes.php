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
$routes->post('/home/recharge', 'CodePortefeuille::recharger');
$routes->post('/home/gold', 'Home::activerGold');
$routes->get('/home/export/pdf', 'Home::exporterPdf');

// Inscription
$routes->get('/inscription', 'User::inscrire');
$routes->post('/user/validerEtape1', 'User::validerEtape1');
$routes->post('/user/finaliser', 'User::finaliserInscription');

//admin
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/codes', 'CodePortefeuille::gererCodes');
$routes->post('/admin/codes/valider', 'CodePortefeuille::validerCode');
$routes->get('/admin/regimes', 'AdminRegime::index');
$routes->get('/admin/regimes/new', 'AdminRegime::create');
$routes->post('/admin/regimes', 'AdminRegime::store');
$routes->get('/admin/regimes/(:num)/edit', 'AdminRegime::edit/$1');
$routes->post('/admin/regimes/(:num)/update', 'AdminRegime::update/$1');
$routes->post('/admin/regimes/(:num)/delete', 'AdminRegime::delete/$1');
$routes->get('/admin/activites', 'AdminActivite::index');
$routes->get('/admin/activites/new', 'AdminActivite::create');
$routes->post('/admin/activites', 'AdminActivite::store');
$routes->get('/admin/activites/(:num)/edit', 'AdminActivite::edit/$1');
$routes->post('/admin/activites/(:num)/update', 'AdminActivite::update/$1');
$routes->post('/admin/activites/(:num)/delete', 'AdminActivite::delete/$1');
$routes->get('/admin/parametres', 'AdminParametre::index');
$routes->get('/admin/parametres/new', 'AdminParametre::create');
$routes->post('/admin/parametres', 'AdminParametre::store');
$routes->get('/admin/parametres/(:segment)/edit', 'AdminParametre::edit/$1');
$routes->post('/admin/parametres/(:segment)/update', 'AdminParametre::update/$1');
$routes->post('/admin/parametres/(:segment)/delete', 'AdminParametre::delete/$1');
$routes->get('/admin/stats', 'AdminStatistiques::index');