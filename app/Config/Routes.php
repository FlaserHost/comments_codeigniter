<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// GET
$routes->get('/', 'Comments::index');
$routes->get('/modal', 'Comments::modal');
$routes->get('/logout', 'Comments::logout');
$routes->get('/sort', 'Comments::sort');

// POST
$routes->post('/modalParts', 'Comments::modalParts');
$routes->post('/auth', 'Comments::auth');
$routes->post('/sort', 'Comments::sort');
$routes->post('/add', 'Comments::add');
$routes->post('/DelEdit', 'Comments::DelEdit');
$routes->post('/registration', 'Comments::registration');
