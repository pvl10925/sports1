<?php
// Tệp: index.php
session_start();
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Controller.php'; // Quan trọng: Đảm bảo có

// Khai báo các route (controller)
$routes = [
    'home'    => 'HomeController',
    'product' => 'ProductController',
    // ... các controller khác
    'auth'    => 'AuthController', // PHẢI CÓ DÒNG NÀY ĐỂ ĐĂNG KÝ/ĐĂNG NHẬP HOẠT ĐỘNG
];

// Định tuyến
// Mặc định sẽ gọi HomeController@index nếu không có tham số c
Router::dispatch($routes, 'home', 'index');