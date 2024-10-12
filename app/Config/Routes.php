<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');


$routes->post('/login', 'UserController::login');
$routes->post('/signup', 'UserController::register');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/user/view', 'UserController::index');
    $routes->post('/user/logout', 'UserController::logout');
    $routes->put('/user/update/(:num)', 'UserController::update/$1');
    $routes->delete('/user/delete/(:num)', 'UserController::delete/$1');

    $routes->get('/post/view', 'BlogPostController::index');
    $routes->get('/post/view/(:num)', 'BlogPostController::show/$1');
    $routes->post('/post/create', 'BlogPostController::create');
    $routes->put('/post/update/(:num)', 'BlogPostController::update/$1');
    $routes->delete('/post/delete/(:num)', 'BlogPostController::delete/$1');
});

$routes->get('/weather', 'WeatherController::index');

