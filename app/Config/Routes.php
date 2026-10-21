<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Public routes
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Authenticated routes
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    // Products
    $routes->get('products', 'Products::index');
    $routes->match(['GET', 'POST'], 'products/create', 'Products::create');
    $routes->match(['GET', 'POST'], 'products/edit/(:num)', 'Products::edit/$1');
    $routes->post('products/delete/(:num)', 'Products::delete/$1');

    // Customers
    $routes->get('customers', 'Customers::index');
    $routes->match(['GET', 'POST'], 'customers/create', 'Customers::create');
    $routes->match(['GET', 'POST'], 'customers/edit/(:num)', 'Customers::edit/$1');
    $routes->post('customers/delete/(:num)', 'Customers::delete/$1');

    // Staff
    $routes->get('staff', 'Staff::index');
    $routes->match(['GET', 'POST'], 'staff/create', 'Staff::create');
    $routes->match(['GET', 'POST'], 'staff/edit/(:num)', 'Staff::edit/$1');
    $routes->post('staff/delete/(:num)', 'Staff::delete/$1');

    // Sales
    $routes->match(['GET', 'POST'], 'sales/create', 'Sales::create');
    $routes->get('sales/history', 'Sales::history');
});
