<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\API\v1\WelcomeController;
use App\Controllers\API\v1\CategoryController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API Endpoints

$routes->group('api', function ($routes) {
    $routes->group('v1', function ($routes) {
        // Test
        $routes->group('hello', function ($routes) {
            $routes->get('/', [WelcomeController::class, 'index']);
            $routes->post('/', [WelcomeController::class, 'index']);
        });
        // Category Routes
        $routes->group('categories', function ($routes) {
            $routes->get('all', [CategoryController::class, 'index'], ['filter' => ['apiAuthFilter', 'permissionsFilter:read_categories']]);
            $routes->get('show/(:num)', [CategoryController::class, 'show'], ['filter' => ['apiAuthFilter', 'permissionsFilter:show_categories']]);
            $routes->post('create', [CategoryController::class, 'create'], ['filter' => ['apiAuthFilter', 'permissionsFilter:create_categories']]);
            $routes->put('update/(:num)', [CategoryController::class, 'update'], ['filter' => ['apiAuthFilter', 'permissionsFilter:update_categories']]);
            $routes->delete('delete/(:num)', [CategoryController::class, 'delete'], ['filter' => ['apiAuthFilter', 'permissionsFilter:delete_categories']]);
        });
    });
});
