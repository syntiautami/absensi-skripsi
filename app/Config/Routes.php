<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('login/', 'Auth::login');
$routes->post('login/', 'Auth::attemptLogin');
$routes->get('logout/', 'Auth::logout');


// admin
$routes->get('admin/', 'Admin\Main::index', ['filter' => 'auth']);

