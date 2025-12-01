<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Order.php';

class AdminDashboardController extends Controller
{
    public function index()
    {
        $productModel = new Product();
        $orderModel   = new Order();

        $productStats = $productModel->getStats();
        $orderSummary = $orderModel->getSummary();
        $recentOrders = $orderModel->getRecent(5);

        $this->render(
            'admin/dashboard/index',
            [
                'pageTitle'    => 'Dashboard admin',
                'productStats' => $productStats,
                'orderSummary' => $orderSummary,
                'recentOrders' => $recentOrders,
            ],
            'admin'
        );
    }
}
