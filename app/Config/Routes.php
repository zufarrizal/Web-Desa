<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('postingan', 'Home::posts');
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

    $routes->get('documents', 'DocumentRequestController::index');
    $routes->get('documents/generate/(:segment)', 'DocumentRequestController::generate/$1');
    $routes->get('documents/create-manual/(:segment)', 'DocumentRequestController::createManual/$1');
    $routes->post('documents/store-manual/(:segment)', 'DocumentRequestController::storeManual/$1');
    $routes->get('documents/preview/(:num)', 'DocumentRequestController::preview/$1');
    $routes->get('documents/print/(:num)', 'DocumentRequestController::print/$1');
    $routes->post('documents/status/(:num)', 'DocumentRequestController::setStatus/$1');
    $routes->post('documents/delete/(:num)', 'DocumentRequestController::delete/$1');
    $routes->get('documents/settings', 'DocumentSettingController::settings');
    $routes->post('documents/settings', 'DocumentSettingController::updateSettings');

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
    $routes->get('/', 'ProgramController::index');
    $routes->get('program', 'ProgramController::index');
    $routes->get('artikel', 'ArticleController::index');
    $routes->get('kegiatan', 'ActivityController::index');
    $routes->get('pengumuman', 'AnnouncementController::index');

    $routes->get('create', 'ProgramController::create');
    $routes->get('create/program', 'ProgramController::create');
    $routes->get('create/artikel', 'ArticleController::create');
    $routes->get('create/kegiatan', 'ActivityController::create');
    $routes->get('create/pengumuman', 'AnnouncementController::create');

    $routes->post('store', 'ProgramController::store');
    $routes->post('store/program', 'ProgramController::store');
    $routes->post('store/artikel', 'ArticleController::store');
    $routes->post('store/kegiatan', 'ActivityController::store');
    $routes->post('store/pengumuman', 'AnnouncementController::store');

    $routes->get('program/edit/(:num)', 'ProgramController::edit/$1');
    $routes->post('program/update/(:num)', 'ProgramController::update/$1');
    $routes->post('program/delete/(:num)', 'ProgramController::delete/$1');

    $routes->get('artikel/edit/(:num)', 'ArticleController::edit/$1');
    $routes->post('artikel/update/(:num)', 'ArticleController::update/$1');
    $routes->post('artikel/delete/(:num)', 'ArticleController::delete/$1');

    $routes->get('kegiatan/edit/(:num)', 'ActivityController::edit/$1');
    $routes->post('kegiatan/update/(:num)', 'ActivityController::update/$1');
    $routes->post('kegiatan/delete/(:num)', 'ActivityController::delete/$1');

    $routes->get('pengumuman/edit/(:num)', 'AnnouncementController::edit/$1');
    $routes->post('pengumuman/update/(:num)', 'AnnouncementController::update/$1');
    $routes->post('pengumuman/delete/(:num)', 'AnnouncementController::delete/$1');
});

$routes->group('settings', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('home', 'HomeSettingController::edit');
    $routes->post('home', 'HomeSettingController::update');
});

$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->post('notifications/clear', 'AdminNotificationController::clear');
});
