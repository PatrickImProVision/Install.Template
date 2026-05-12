<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Installer URLs never run the "unfinished install" gate — avoids redirecting POSTs away from Install::database.
$routes->group('install', ['filter' => 'installationdone'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Install::index');
    $routes->get('health', 'Install::health');
    // No CSRF during one-time install: cookie/session mismatches (path, base URL, portable stacks)
    // abort the request before the controller runs (blank page / uncaught SecurityException).
    $routes->match(['get', 'post'], 'database', 'Install::database');
    $routes->match(['get', 'post'], 'schema', 'Install::schema');
    $routes->match(['get', 'post'], 'admin', 'Install::admin');
    $routes->match(['get', 'post'], 'site', 'Install::site');
});

// Rest of the site: until installed, redirect here into the wizard (InstallationRequired filter).
$routes->group('', ['filter' => 'installationrequired'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Home::index');
    $routes->get('about-us', 'Home::aboutUs');
    $routes->get('services', 'Home::services');
    $routes->get('products', 'Home::products');
    $routes->get('tech-stack', 'Home::techStack');
    $routes->get('values', 'Home::values');
    $routes->get('contact', 'Home::contact');

    $routes->match(['get', 'post'], 'login', 'Auth::login', ['filter' => 'csrf']);
    $routes->get('logout', 'Auth::logout');

    // DashBoard/* — administrator-only (adminauth + CSRF). PascalCase segments mirror CMS sections.
    $routes->group('DashBoard', ['filter' => ['adminauth', 'csrf']], static function (RouteCollection $routes): void {
        $routes->get('/', 'Admin\\Dashboard::index');
        $routes->get('Index', 'Admin\\Status::index');

        $routes->get('About_Us', 'Admin\\AboutUs::index');
        $routes->match(['get', 'post'], 'About_Us/Create', 'Admin\\AboutUs::create');
        $routes->match(['get', 'post'], 'About_Us/Edit/(:num)', 'Admin\\AboutUs::edit/$1');
        $routes->post('About_Us/Delete/(:num)', 'Admin\\AboutUs::delete/$1');

        $routes->get('Services', 'Admin\\ServicesItems::index');
        $routes->match(['get', 'post'], 'Services/Create', 'Admin\\ServicesItems::create');
        $routes->match(['get', 'post'], 'Services/Edit/(:num)', 'Admin\\ServicesItems::edit/$1');
        $routes->post('Services/Delete/(:num)', 'Admin\\ServicesItems::delete/$1');

        $routes->get('Tech_Stack', 'Admin\\TechStackItems::index');
        $routes->match(['get', 'post'], 'Tech_Stack/Create', 'Admin\\TechStackItems::create');
        $routes->match(['get', 'post'], 'Tech_Stack/Edit/(:num)', 'Admin\\TechStackItems::edit/$1');
        $routes->post('Tech_Stack/Delete/(:num)', 'Admin\\TechStackItems::delete/$1');

        $routes->get('Products', 'Admin\\ProductsItems::index');
        $routes->match(['get', 'post'], 'Products/Create', 'Admin\\ProductsItems::create');
        $routes->match(['get', 'post'], 'Products/Edit/(:num)', 'Admin\\ProductsItems::edit/$1');
        $routes->post('Products/Delete/(:num)', 'Admin\\ProductsItems::delete/$1');

        $routes->get('Values', 'Admin\\ValuesItems::index');
        $routes->match(['get', 'post'], 'Values/Create', 'Admin\\ValuesItems::create');
        $routes->match(['get', 'post'], 'Values/Edit/(:num)', 'Admin\\ValuesItems::edit/$1');
        $routes->post('Values/Delete/(:num)', 'Admin\\ValuesItems::delete/$1');
        $routes->get('Site_Contacts', 'Admin\\ContactItems::index');
        $routes->match(['get', 'post'], 'Site_Contact/Create', 'Admin\\ContactItems::create');
        $routes->match(['get', 'post'], 'Site_Contact/Edit/(:num)', 'Admin\\ContactItems::edit/$1');
        $routes->post('Site_Contact/Delete/(:num)', 'Admin\\ContactItems::delete/$1');

        $routes->get('SEO_Settings', 'Admin\\SeoSettings::index');
        $routes->match(['get', 'post'], 'SEO_Settings/Edit/(:segment)', 'Admin\\SeoSettings::edit/$1');

        $routes->get('Web_Settings', 'Admin\\SiteSettings::index');
        $routes->match(['get', 'post'], 'Web_Settings/Edit/(:num)', 'Admin\\SiteSettings::edit/$1');
    });
});
