<?php
require_once __DIR__ . '/../app/core/Router.php';

$routes = [
    'dashboard' => 'admin/AdminDashboardController',
    'product'   => 'admin/AdminProductController',
    'category'  => 'admin/AdminCategoryController',
    'brand'     => 'admin/AdminBrandController',
    'order'     => 'admin/AdminOrderController',
    'report'    => 'admin/AdminReportController',
];

Router::dispatch($routes, 'dashboard', 'index');