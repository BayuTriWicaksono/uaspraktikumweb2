<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->options('(:any)', function() {
    return service('response')
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->setStatusCode(200);
});


// REST API resource
$routes->resource('post');

// Halaman statis
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');

// Artikel publik
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

// Admin group
$routes->group('admin', function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->get('artikel/json', 'Artikel::admin_json'); // <- route data JSON
    $routes->match(['GET', 'POST'], 'artikel/add', 'Artikel::add');
    $routes->match(['GET', 'POST'], 'artikel/edit/(:any)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
});


// User authentication
$routes->get('/user/login', 'User::login');
$routes->post('/user/login', 'User::login');
$routes->get('/user/logout', 'User::logout');
$routes->get('/user', 'User::index');

// AJAX controller
$routes->get('/ajax', 'AjaxController::index');
$routes->get('/ajax/getData', 'AjaxController::getData');
$routes->get('/ajax/delete/(:num)', 'AjaxController::delete/$1');
$routes->get('/admin/artikel/detail/(:num)', 'Artikel::admin_detail/$1');
