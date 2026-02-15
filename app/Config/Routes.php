<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('program/(:segment)', 'Home::show/$1');
$routes->get('sitemap.xml', 'Home::sitemapXml');
$routes->get('robots.txt', 'Home::robotsTxt');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::attemptRegister');
$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('forgot-password', 'AuthController::sendResetLink');
$routes->get('reset-password/(:segment)', 'AuthController::resetPassword/$1');
$routes->post('reset-password/(:segment)', 'AuthController::attemptResetPassword/$1');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('logout', 'AuthController::logout');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile', 'ProfileController::edit');
    $routes->post('profile', 'ProfileController::update');

    $routes->get('documents', 'DocumentServiceController::index');
    $routes->get('documents/generate/(:segment)', 'DocumentServiceController::generate/$1');
    $routes->get('documents/create-manual/(:segment)', 'DocumentServiceController::createManual/$1');
    $routes->post('documents/store-manual/(:segment)', 'DocumentServiceController::storeManual/$1');
    $routes->get('documents/preview/(:num)', 'DocumentServiceController::preview/$1');
    $routes->get('documents/print/(:num)', 'DocumentServiceController::print/$1');
    $routes->post('documents/status/(:num)', 'DocumentServiceController::setStatus/$1');
    $routes->post('documents/delete/(:num)', 'DocumentServiceController::delete/$1');
    $routes->get('documents/settings', 'DocumentServiceController::settings');
    $routes->post('documents/settings', 'DocumentServiceController::updateSettings');

    $routes->get('complaints', 'ComplaintController::index');
    $routes->get('complaints/create', 'ComplaintController::create');
    $routes->post('complaints/store', 'ComplaintController::store');
    $routes->get('complaints/edit/(:num)', 'ComplaintController::edit/$1');
    $routes->post('complaints/update/(:num)', 'ComplaintController::update/$1');
    $routes->post('complaints/delete/(:num)', 'ComplaintController::delete/$1');
});

$routes->group('users', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->post('delete/(:num)', 'UserController::delete/$1');
});

$routes->group('programs', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('/', 'ProgramController::index/program');
    $routes->get('program', 'ProgramController::index/program');
    $routes->get('artikel', 'ProgramController::index/artikel');
    $routes->get('kegiatan', 'ProgramController::index/kegiatan');
    $routes->get('create', 'ProgramController::create/program');
    $routes->get('create/(:segment)', 'ProgramController::create/$1');
    $routes->post('store', 'ProgramController::store/program');
    $routes->post('store/(:segment)', 'ProgramController::store/$1');
    $routes->get('edit/(:num)', 'ProgramController::edit/$1');
    $routes->post('update/(:num)', 'ProgramController::update/$1');
    $routes->post('delete/(:num)', 'ProgramController::delete/$1');
});
