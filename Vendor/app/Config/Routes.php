<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('install', static function (RouteCollection $routes): void {
    $routes->get('/', 'Install::index');
    $routes->get('new', 'Install::fresh');
    $routes->get('restore', 'Install::restore');
    $routes->post('backup/delete', 'Install::deleteBackup');
    $routes->post('database', 'Install::saveDatabase');
    $routes->post('test-connection', 'Install::testConnection');
    $routes->get('schema', 'Install::schema');
    $routes->post('schema', 'Install::runSchema');
    $routes->get('restore/schema', 'Install::schema');
    $routes->post('restore/schema', 'Install::runSchema');
    $routes->get('admin', 'Install::admin');
    $routes->post('admin', 'Install::saveAdmin');
    $routes->get('complete', 'Install::complete');
    $routes->post('finish', 'Install::finish');
    $routes->get('uninstall', 'Install::uninstall');
    $routes->post('uninstall/confirm', 'Install::uninstallConfirm');
    $routes->get('uninstall/next', 'Install::uninstallNext');
});

$routes->get('/', 'Home::index');
