<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Order.php';

class AdminOrderController extends Controller
{
    public function index()
    {
        $orderModel = new Order();
        $orders     = $orderModel->getRecent(50);

        $this->render(
            'admin/order/index',
            [
                'pageTitle' => 'Quản lý đơn hàng',
                'orders'    => $orders,
            ],
            'admin'
        );
    }

    public function cancel()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $orderModel = new Order();
            $orderModel->updateStatus($id, 'CANCELLED');
        }
        $this->redirect('admin.php?c=order&a=index');
    }
}
