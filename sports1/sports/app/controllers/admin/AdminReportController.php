<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Order.php';

class AdminReportController extends Controller
{
    public function index()
    {
        $productModel = new Product();
        $orderModel   = new Order();

        // Lấy thống kê sản phẩm
        $productStats = $productModel->getStats();
        
        // Lấy thống kê đơn hàng
        $orderSummary = $orderModel->getSummary();
        
        // Lấy doanh thu theo tháng
        $monthlyRevenue = $orderModel->getMonthlyRevenue();
        
        // Lấy top 5 sản phẩm bán chạy
        $topProducts = $orderModel->getTopProducts(5);

        $this->render(
            'admin/report/index',
            [
                'pageTitle'      => 'Báo cáo thống kê',
                'productStats'   => $productStats,
                'orderSummary'   => $orderSummary,
                'monthlyRevenue' => $monthlyRevenue,
                'topProducts'    => $topProducts,
            ],
            'admin'
        );
    }
}
    