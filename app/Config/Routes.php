<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Authentication routes
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::attemptRegister');

// Magic link routes
$routes->post('/auth/send-magic-link', 'Auth::sendMagicLink');
$routes->get('/auth/magic-login/(:any)', 'Auth::magicLogin/$1');

// Guest order tracking routes
$routes->get('/track-order', 'Auth::trackOrder');
$routes->get('/track-order-details', 'Auth::guestOrderDetails');

// Admin routes
$routes->group('/admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('/dashboard', 'Admin\Dashboard::index');
    $routes->get('/orders', 'Admin\Orders::index');
    $routes->get('/orders/create', 'Admin\Orders::create');
    $routes->post('/orders/store', 'Admin\Orders::store');
    $routes->get('/orders/view/(:num)', 'Admin\Orders::view/$1');
    $routes->get('/orders/edit/(:num)', 'Admin\Orders::edit/$1');
    $routes->post('/orders/update/(:num)', 'Admin\Orders::update/$1');
    $routes->delete('/orders/delete/(:num)', 'Admin\Orders::delete/$1');
    
    $routes->get('/products', 'Admin\Products::index');
    $routes->get('/products/create', 'Admin\Products::create');
    $routes->post('/products/store', 'Admin\Products::store');
    $routes->get('/products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('/products/update/(:num)', 'Admin\Products::update/$1');
    $routes->delete('/products/delete/(:num)', 'Admin\Products::delete/$1');
    $routes->post('/products/import', 'Admin\Products::import');
    $routes->get('/products/export', 'Admin\Products::export');
    
    $routes->get('/customers', 'Admin\Customers::index');
    $routes->get('/customers/view/(:num)', 'Admin\Customers::view/$1');
    $routes->get('/customers/edit/(:num)', 'Admin\Customers::edit/$1');
    $routes->post('/customers/update/(:num)', 'Admin\Customers::update/$1');
    
    $routes->get('/reports', 'Admin\Reports::index');
    $routes->get('/reports/sales', 'Admin\Reports::sales');
    $routes->get('/reports/inventory', 'Admin\Reports::inventory');
    $routes->get('/reports/customers', 'Admin\Reports::customers');
    
    $routes->get('/settings', 'Admin\Settings::index');
    $routes->post('/settings/update', 'Admin\Settings::update');
});

// Customer routes
$routes->group('/customer', ['filter' => 'auth:customer'], function($routes) {
    $routes->get('/', 'Customer\Dashboard::index');
    $routes->get('/dashboard', 'Customer\Dashboard::index');
    $routes->get('/orders', 'Customer\Orders::index');
    $routes->get('/orders/view/(:num)', 'Customer\Orders::view/$1');
    $routes->get('/orders/create', 'Customer\Orders::create');
    $routes->post('/orders/store', 'Customer\Orders::store');
    $routes->get('/profile', 'Customer\Profile::index');
    $routes->post('/profile/update', 'Customer\Profile::update');
});

// Staff routes
$routes->group('/staff', ['filter' => 'auth:staff'], function($routes) {
    $routes->get('/', 'Staff\Dashboard::index');
    $routes->get('/dashboard', 'Staff\Dashboard::index');
    $routes->get('/orders', 'Staff\Orders::index');
    $routes->get('/orders/view/(:num)', 'Staff\Orders::view/$1');
    $routes->post('/orders/update-status/(:num)', 'Staff\Orders::updateStatus/$1');
    $routes->get('/products', 'Staff\Products::index');
    $routes->get('/inventory', 'Staff\Inventory::index');
});

// API routes
$routes->group('/api', ['filter' => 'cors'], function($routes) {
    $routes->get('/products', 'Api\Products::index');
    $routes->get('/products/(:num)', 'Api\Products::show/$1');
    $routes->get('/orders/status/(:num)', 'Api\Orders::status/$1');
    $routes->post('/orders/track', 'Api\Orders::track');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in place. Dealing with
 * these can be done in several ways, but the simplest is to use
 * the following:
 *
 * $routes->add('/path', 'Controller::method');
 *
 * You can also use placeholders in your routes:
 *
 * $routes->add('/users/(:id)', 'Users::show/$1');
 *
 * The $1 will be replaced with the captured parameter.
 *
 * You can also use named parameters:
 *
 * $routes->add('/users/(:id)', 'Users::show/$1', ['as' => 'user_profile']);
 *
 * For more information please see:
 * https://codeigniter.com/user_guide/incoming/routing.html
 */
